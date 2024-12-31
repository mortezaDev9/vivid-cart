<?php

namespace Modules\Order\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Cart\Models\Cart;
use Modules\Order\Services\OrderService;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function calculate_total_amount_returns_correct_sum(): void
    {
        $cart = Cart::factory()->create();
        $products = Product::factory(10)->create();

        $cart->products()->attach($products->pluck('id')->toArray(), ['quantity' => 1]);

        $totalAmount = 0;

        foreach ($cart->products as $product) {
            $totalAmount += $product->pivot->quantity * $product->price;
        }

        $this->assertEquals($totalAmount, (new OrderService())->calculateTotalAmount($cart));
    }

    #[Test]
    public function calculate_total_amount_returns_zero_on_empty_cart_items(): void
    {
        $cart = Cart::factory()->create();

        $this->assertEquals(0, (new OrderService())->calculateTotalAmount($cart));
    }
}
