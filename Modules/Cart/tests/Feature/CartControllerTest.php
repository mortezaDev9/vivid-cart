<?php

namespace Modules\Cart\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Modules\Cart\Models\Cart;
use Modules\Product\Models\Product;
use Modules\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    private Cart $cart;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);

        $this->cart = $user->cart()->create();
        $user->wishlist()->create();
    }

    #[Test]
    public function it_shows_the_cart_page_with_products_and_quantities(): void
    {
        $products = Product::factory(3)->create();
        $this->cart->products()->attach($products->pluck('id')->toArray(), ['quantity' => 2]);

        $response = $this->get(route('cart.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('cart::index')
            ->assertViewHasAll([
                'products'   => $this->cart->products,
                'subtotal'   => $this->cart->products->sum(fn($product) => $product->pivot->quantity * $product->price),
                'quantities' => $this->cart->products->pluck('pivot.quantity', 'id')->toArray(),
            ]);
    }

    #[Test]
    public function it_shows_an_empty_cart_page_if_no_products_exist(): void
    {
        $response = $this->get(route('cart.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHasAll([
                'products'   => new \Illuminate\Database\Eloquent\Collection(),
                'subtotal'   => 0,
                'quantities' => [],
            ]);
    }

    #[Test]
    public function it_denies_access_to_cart_if_user_is_unauthenticated(): void
    {
        auth()->logout();

        $response = $this->get(route('cart.index'));

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('login.form');
    }

    #[Test]
    public function it_does_not_show_other_users_cart(): void
    {
        $otherUser = User::factory()->create();
        $otherCart = Cart::factory()->create(['user_id' => $otherUser->id]);
        $product   = Product::factory()->create();
        $otherCart->products()->attach($product->id, ['quantity' => 1]);

        $response = $this->get(route('cart.index'));

        $response->assertStatus(Response::HTTP_OK)->assertViewMissing($product);
    }

    #[Test]
    public function it_adds_a_product_to_the_cart(): void
    {
        $product = Product::factory()->create();

        $response = $this->postJson(route('cart.toggle', $product->id));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'type'      => 'success',
                'message'   => __('Product added to your cart'),
                'isInCart'  => true,
                'cartCount' => 1,
            ]);

        $this->assertTrue($this->cart->products()->whereId($product->id)->exists());
    }

    #[Test]
    public function it_fails_to_add_product_with_zero_quantity_to_cart(): void
    {
        $product = Product::factory()->create(['quantity' => 0]);

        $response = $this->postJson(route('cart.toggle', $product->id));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertFalse($this->cart->products()->whereId($product->id)->exists());
    }

    #[Test]
    public function it_removes_a_product_from_the_cart_if_it_is_already_in_cart(): void
    {
        $product = Product::factory()->create();
        $this->cart->products()->attach($product->id, ['quantity' => 1]);

        $response = $this->postJson(route('cart.toggle', $product->id));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'type'      => 'info',
                'message'   => __('Product removed from your cart'),
                'isInCart'  => false,
                'cartCount' => 0,
            ]);

        $this->assertFalse($this->cart->products()->whereId($product->id)->exists());
    }

    #[Test]
    public function it_fails_to_toggle_a_product_with_invalid_id(): void
    {
        $response = $this->postJson(route('cart.toggle', 9999));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    #[Test]
    public function it_removes_a_product_from_cart(): void
    {
        $product = Product::factory()->create();
        $this->cart->products()->attach($product->id, ['quantity' => 1]);

        $response = $this->deleteJson(route('cart.remove', $product->id));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'type' => 'info',
                'message' => __('Product removed from your cart'),
                'isInCart' => false,
                'cartCount' => 0,
            ]);

        $this->assertFalse($this->cart->products()->whereId($product->id)->exists());
    }

    #[Test]
    public function it_fails_to_remove_a_product_not_in_cart(): void
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson(route('cart.remove', $product->id));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                'type'    => 'error',
                'message' => __('Product not found in the cart'),
            ]);
    }

    #[Test]
    public function it_fails_to_remove_a_product_with_invalid_id(): void
    {
        $response = $this->deleteJson(route('cart.remove', 9999));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    #[Test]
    public function it_updates_a_product_quantity_in_the_cart(): void
    {
        $product = Product::factory()->create(['quantity' => 10]);
        $this->cart->products()->attach($product->id, ['quantity' => 1]);

        $response = $this->patchJson(route('cart.update-quantity', $product->id), ['quantity' => 5]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'type'    => 'success',
                'message' => __('Product quantity updated in cart'),
            ]);

        $this->assertEquals(5, $this->cart->products()->firstWhere('id', $product->id)->pivot->quantity);
    }

    #[Test]
    public function it_fails_to_update_quantity_with_invalid_data(): void
    {
        $product = Product::factory()->create(['quantity' => 10]);
        $this->cart->products()->attach($product->id, ['quantity' => 2]);

        $response = $this->patchJson(route('cart.update-quantity', $product->id), ['quantity' => -1]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['quantity']);
    }

    #[Test]
    public function it_denies_quantity_update_for_a_product_not_in_cart(): void
    {
        $product = Product::factory()->create(['quantity' => 10]);

        $response = $this->patchJson(route('cart.update-quantity', $product->id), ['quantity' => 5]);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                'type'    => 'error',
                'message' => __('Product not found in the cart'),
            ]);
    }
}
