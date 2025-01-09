<?php

declare(strict_types=1);

namespace Modules\Product\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderItem;
use Modules\Product\Models\Product;
use Modules\Review\Models\Review;
use Modules\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->user->cart()->create();
        $this->user->wishlist()->create();
    }

    #[Test]
    public function it_displays_product_details_and_related_products(): void
    {
        $product = Product::factory()->create();
        $relatedProducts = Product::factory(4)->create(['category_id' => $product->category_id]);

        $response = $this->get(route('products.index', $product));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('product::index')
            ->assertViewHasAll([
                'product'            => $product,
                'hasBoughtProduct'   => false,
                'relatedProducts'    => $relatedProducts,
                'hasReviewedProduct' => false,
            ])
            ->assertDontSee('Write a Review');
    }

    #[Test]
    public function it_displays_has_bought_product_as_true_when_user_has_bought_the_product(): void
    {
        $product = Product::factory()->create();

        $order = Order::factory()->create(['user_id' => $this->user->id, 'status' => OrderStatus::VERIFIED->value]);
        OrderItem::factory()->create(['order_id' => $order->id, 'product_id' => $product->id]);

        $response = $this->get(route('products.index', $product));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('product::index')
            ->assertViewHas('hasBoughtProduct', true)
            ->assertSee('Write a Review');
    }

    #[Test]
    public function it_displays_has_reviewed_product_as_true_when_user_has_reviewed_the_product(): void
    {
        $product = Product::factory()->create();

        Review::factory()->create([
            'user_id'    => $this->user->id,
            'product_id' => $product->id,
            'rating'     => 5,
            'comment'    => 'Great product!',
        ]);

        $response = $this->get(route('products.index', $product));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('product::index')
            ->assertViewHas('hasReviewedProduct', true)
            ->assertDontSee('Write a Review');
    }

    #[Test]
    public function it_handles_no_related_products(): void
    {
        $product = Product::factory()->create();

        $response = $this->get(route('products.index', $product));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('product::index')
            ->assertViewHas('relatedProducts', new \Illuminate\Database\Eloquent\Collection());
    }

    #[Test]
    public function it_handles_guest_user(): void
    {
        auth()->logout();
        $product = Product::factory()->create();

        $response = $this->get(route('products.index', $product));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('product::index')
            ->assertViewHas('hasBoughtProduct', false)
            ->assertDontSee('Write a Review');
    }

    #[Test]
    public function it_throws_not_found_when_product_does_not_exist(): void
    {
        $response = $this->get(route('products.index', 9999));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    #[Test]
    public function it_handles_related_products_in_different_categories(): void
    {
        $product = Product::factory()->create();
        Product::factory(2)->create(['category_id' => $product->category_id]);
        Product::factory(2)->create(['category_id' => 9999]);

        $response = $this->get(route('products.index', $product));

        $relatedProducts = $response->viewData('relatedProducts');

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('product::index');

        $this->assertCount(2, $relatedProducts);
    }
}
