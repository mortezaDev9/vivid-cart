<?php

declare(strict_types=1);

namespace Modules\Shop\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Modules\Order\Enums\PaymentMethod;
use Modules\Product\Models\Product;
use Modules\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $cart = $this->user->cart()->create();
        $cart->products()->attach(Product::factory(3)->create(), ['quantity' => 2]);

        $this->user->wishlist()->create();
    }

    #[Test]
    public function it_displays_the_checkout_view_for_authenticated_user(): void
    {
        $response = $this->get(route('checkout'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('shop::checkout')
            ->assertViewHasAll([
                'products',
                'subtotal',
                'shippingCost',
                'total',
                'paymentMethods',
            ]);
    }

    #[Test]
    public function it_calculates_correct_totals_on_checkout(): void
    {
        $cart = $this->user->cart;
        $cart->products()->each(function (Product $product) {
            $product->price = 100; // Set a fixed price for predictable test results
            $product->save();
        });

        $response = $this->get(route('checkout'));

        $subtotal = $cart->products->sum(fn ($product) => $product->pivot->quantity * $product->price);
        $shippingCost = app('Modules\Order\Services\OrderService')->calculateShippingCost($cart);

        $response->assertViewHas('subtotal', $subtotal);
        $response->assertViewHas('shippingCost', $shippingCost);
        $response->assertViewHas('total', $subtotal + $shippingCost);
    }

    #[Test]
    public function it_provides_payment_methods_in_checkout_view(): void
    {
        $response = $this->get(route('checkout'));

        $response->assertViewHas('paymentMethods', PaymentMethod::getValues());
    }

    #[Test]
    public function it_redirects_unauthenticated_users_to_login(): void
    {
        auth()->logout();

        $response = $this->get(route('checkout'));

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('login');
    }

    #[Test]
    public function it_shows_empty_cart_message_if_cart_has_no_products(): void
    {
        $this->user->cart->products()->detach();

        $response = $this->get(route('checkout'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('shop::checkout')
            ->assertViewHas('products', new \Illuminate\Database\Eloquent\Collection())
            ->assertSee(__('Your cart is empty'));
    }
}
