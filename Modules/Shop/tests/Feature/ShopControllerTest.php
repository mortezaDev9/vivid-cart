<?php

declare(strict_types=1);

namespace Modules\Shop\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Modules\Category\Models\Category;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ShopControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_displays_the_shop_page_with_products_and_categories(): void
    {
        $categories = Category::factory(3)->hasProducts(5)->create();

        $response = $this->get(route('shop'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('shop::index')
            ->assertViewHasAll([
                'products',
                'categories',
                'sortOption',
                'selectedCategorySlugs',
            ]);

        $responseCategories = $response->viewData('categories');

        $this->assertCount(3, $responseCategories);
        $this->assertTrue($categories->pluck('id')->contains($responseCategories->first()->id));
    }

    #[Test]
    public function it_filters_products_by_category(): void
    {
        $firstCategory = Category::factory()->hasProducts(3)->create();
        Category::factory()->hasProducts(2)->create();

        $response = $this->get(route('shop', ['category' => [$firstCategory->slug]]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHas('products', function ($products) {
                return $products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->count() > 0;
            });

        $products = $response->viewData('products');

        $this->assertCount(3, $products->items());
        $this->assertTrue($products->pluck('category_id')->every(fn ($id) => $id === $firstCategory->id));
    }

    #[Test]
    public function it_displays_empty_result_if_no_products_match_category_filter(): void
    {
        $category = Category::factory()->create();

        Product::factory()->create(['category_id' => Category::factory()->create()->id]);

        $response = $this->get(route('shop', ['category' => [$category->slug]]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHas('products', function ($products) {
                return $products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->count() === 0;
            });
    }

    #[Test]
    public function it_filters_products_by_price_range(): void
    {
        Product::factory()->create(['price' => 50]);
        Product::factory()->create(['price' => 100]);
        Product::factory()->create(['price' => 200]);

        $response = $this->get(route('shop', ['min' => 75, 'max' => 150]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHas('products');

        $products = $response->viewData('products');

        $this->assertCount(1, $products);
        $this->assertTrue($products->pluck('price')->every(fn ($price) => $price >= 75 && $price <= 150));
    }

    #[Test]
    public function it_displays_empty_result_if_no_products_match_price_range(): void
    {
        Product::factory()->create(['price' => 500]);
        Product::factory()->create(['price' => 600]);
        Product::factory()->create(['price' => 700]);

        $response = $this->get(route('shop', ['min' => 1000, 'max' => 2000]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHas('products', function ($products) {
                return $products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->count() === 0;
            });
    }

    #[Test]
    public function it_sorts_products_by_price_low_to_high(): void
    {
        Product::factory()->create(['price' => 300]);
        Product::factory()->create(['price' => 100]);
        Product::factory()->create(['price' => 200]);

        $response = $this->get(route('shop', ['sort' => 'price-low-to-high']));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHas('products');

        $products     = $response->viewData('products');
        $sortedPrices = $products->pluck('price')->sort()->values();

        $this->assertEquals($sortedPrices, $products->pluck('price')->values());
    }

    #[Test]
    public function it_sorts_products_by_price_high_to_low(): void
    {
        Product::factory()->create(['price' => 300]);
        Product::factory()->create(['price' => 100]);
        Product::factory()->create(['price' => 200]);

        $response = $this->get(route('shop', ['sort' => 'price-high-to-low']));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHas('products');

        $products     = $response->viewData('products');
        $sortedPrices = $products->pluck('price')->sortDesc()->values();

        $this->assertEquals($sortedPrices, $products->pluck('price')->values());
    }

    #[Test]
    public function it_searches_products_by_title(): void
    {
        Product::factory()->create(['title' => 'Samsung Galaxy']);
        Product::factory()->create(['title' => 'Apple iPhone']);
        Product::factory()->create(['title' => 'Google Pixel']);

        $response = $this->get(route('shop', ['q' => 'Samsung']));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHas('products');

        $products = $response->viewData('products');

        $this->assertCount(1, $products);
        $this->assertEquals('Samsung Galaxy', $products->first()->title);
    }

    #[Test]
    public function it_displays_empty_result_if_no_products_match_search_query(): void
    {
        Product::factory()->create(['title' => 'Samsung Galaxy']);
        Product::factory()->create(['title' => 'Apple iPhone']);
        Product::factory()->create(['title' => 'Google Pixel']);

        $response = $this->get(route('shop', ['q' => 'Nonexistent Product']));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHas('products', function ($products) {
                return $products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->count() === 0;
            });
    }

    #[Test]
    public function it_displays_result_for_multiple_filters(): void
    {
        $category = Category::factory()->create();
        Product::factory()->create(['title' => 'Samsung Galaxy', 'price' => 500, 'category_id' => $category->id]);

        $response = $this->get(route('shop', [
            'min'      => 200,
            'max'      => 700,
            'category' => [$category->slug],
            'q'        => 'Samsung'
        ]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHas('products', function ($products) {
                return $products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->count() === 1;
            });
    }


    #[Test]
    public function it_displays_empty_result_if_no_products_match_combined_filters(): void
    {
        $category = Category::factory()->create();
        Product::factory()->create(['title' => 'Samsung Galaxy', 'price' => 500]);

        $response = $this->get(route('shop', [
            'min'      => 1000,
            'max'      => 2000,
            'category' => [$category->slug],
            'q'        => 'Nonexistent Product'
        ]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHas('products', function ($products) {
                return $products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->count() === 0;
            });
    }
}
