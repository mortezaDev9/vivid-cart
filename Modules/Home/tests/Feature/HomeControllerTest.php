<?php

namespace Modules\Home\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Modules\Category\Models\Category;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_displays_the_home_page_with_products_and_categories(): void
    {
        $categories  = Category::factory(3)->create();
        $products    = Product::factory(10)->create(['created_at' => now()->subDays(8)]);
        $newProducts = Product::factory(4)->create();

        $response = $this->get(route('home'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('home::index')
            ->assertViewHasAll([
                'categories'  => $categories,
                'products'    => $products,
                'newProducts' => $newProducts,
            ]);

        $response->assertSee($products->first()->name)
            ->assertSee($categories->first()->name)
            ->assertSee($newProducts->first()->name);
    }
}
