<?php

declare(strict_types=1);

namespace Modules\Product\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Product\Models\Product;
use Modules\Product\Services\ProductService;
use Modules\User\Models\User;
use Modules\Cart\Models\Cart;
use Modules\Wishlist\Models\Wishlist;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private ProductService $productService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        $this->productService = new ProductService();
    }

    #[Test]
    public function it_returns_true_if_product_is_in_wishlist(): void
    {
        $product  = Product::factory()->create();
        $wishlist = Wishlist::create(['user_id' => $this->user->id]);
        $wishlist->products()->attach($product, ['quantity' => 1]);

        $result = $this->productService->isWishlisted($product->id);

        $this->assertTrue($result);
    }

    #[Test]
    public function it_returns_false_if_product_is_not_in_wishlist(): void
    {
        $product = Product::factory()->create();
        Wishlist::create(['user_id' => $this->user->id]);

        $result = $this->productService->isWishlisted($product->id);

        $this->assertFalse($result);
    }

    #[Test]
    public function it_returns_false_if_user_is_not_logged_in_when_checking_wishlist(): void
    {
        auth()->logout();
        $product = Product::factory()->create();

        $result = $this->productService->isWishlisted($product->id);

        $this->assertFalse($result);
    }

    #[Test]
    public function it_returns_false_if_user_has_no_wishlist(): void
    {
        $product = Product::factory()->create();
        $this->user->wishlist()->delete();

        $result = $this->productService->isWishlisted($product->id);

        $this->assertFalse($result);
    }

    #[Test]
    public function it_returns_true_if_product_is_in_cart(): void
    {
        $product = Product::factory()->create();
        $cart    = Cart::create(['user_id' => $this->user->id]);
        $cart->products()->attach($product, ['quantity' => 1]);

        $result = $this->productService->isInCart($product->id);

        $this->assertTrue($result);
    }

    #[Test]
    public function it_returns_false_if_product_is_not_in_cart(): void
    {
        $product = Product::factory()->create();
        Cart::create(['user_id' => $this->user->id]);

        $result = $this->productService->isInCart($product->id);

        $this->assertFalse($result);
    }

    #[Test]
    public function it_returns_false_if_user_is_not_logged_in_when_checking_cart(): void
    {
        auth()->logout();
        $product = Product::factory()->create();

        $result = $this->productService->isInCart($product->id);

        $this->assertFalse($result);
    }

    #[Test]
    public function it_returns_false_if_user_has_no_cart(): void
    {
        $product = Product::factory()->create();
        $this->user->cart()->delete();

        $result = $this->productService->isInCart($product->id);

        $this->assertFalse($result);
    }
}
