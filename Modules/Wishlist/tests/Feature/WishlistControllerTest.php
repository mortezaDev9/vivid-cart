<?php


namespace Modules\Wishlist\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Modules\Product\Models\Product;
use Modules\User\Models\User;
use Modules\Wishlist\Models\Wishlist;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class WishlistControllerTest extends TestCase
{
    use RefreshDatabase;

    private Wishlist $wishlist;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);

        $this->wishlist = $user->wishlist()->create();
        $user->cart()->create();
    }

    #[Test]
    public function it_shows_the_wishlist_page_with_products(): void
    {
        $products = Product::factory(3)->create();
        $this->wishlist->products()->attach($products->pluck('id')->toArray(), ['quantity' => 1]);

        $response = $this->get(route('wishlist.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('wishlist::index')
            ->assertViewHas('products', $this->wishlist->products);
    }

    #[Test]
    public function it_shows_an_empty_wishlist_page_if_no_products_exist(): void
    {
        $response = $this->get(route('wishlist.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHas('products', new \Illuminate\Database\Eloquent\Collection());
    }

    #[Test]
    public function it_denies_access_to_wishlist_if_user_is_unauthenticated(): void
    {
        auth()->logout();

        $response = $this->get(route('wishlist.index'));

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('login.form');
    }

    #[Test]
    public function it_does_not_show_other_users_wishlist(): void
    {
        $otherUser = User::factory()->create();
        $otherWishlist = Wishlist::factory()->create(['user_id' => $otherUser->id]);
        $product = Product::factory()->create();
        $otherWishlist->products()->attach($product->id, ['quantity' => 1]);

        $response = $this->get(route('wishlist.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewMissing($product);
    }

    #[Test]
    public function it_adds_a_product_to_the_wishlist(): void
    {
        $product = Product::factory()->create();

        $response = $this->postJson(route('wishlist.toggle', $product->id));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'type' => 'success',
                'message' => __('Product added to your wishlist'),
                'isWishlisted' => true,
                'wishlistCount' => 1,
            ]);

        $this->assertTrue($this->wishlist->products()->whereId($product->id)->exists());
    }

    #[Test]
    public function it_removes_a_product_from_the_wishlist_if_it_is_already_in_wishlist(): void
    {
        $product = Product::factory()->create();
        $this->wishlist->products()->attach($product->id, ['quantity' => 1]);

        $response = $this->postJson(route('wishlist.toggle', $product->id));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'type' => 'info',
                'message' => __('Product removed from your wishlist'),
                'isWishlisted' => false,
                'wishlistCount' => 0,
            ]);

        $this->assertFalse($this->wishlist->products()->whereId($product->id)->exists());
    }

    #[Test]
    public function it_fails_to_toggle_a_product_with_invalid_id(): void
    {
        $response = $this->postJson(route('wishlist.toggle', 9999));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    #[Test]
    public function it_removes_a_product_from_the_wishlist(): void
    {
        $product = Product::factory()->create();
        $this->wishlist->products()->attach($product->id, ['quantity' => 1]);

        $response = $this->deleteJson(route('wishlist.remove', $product->id));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'type' => 'info',
                'message' => __('Product removed from your wishlist'),
                'isWishlisted' => false,
                'wishlistCount' => 0,
            ]);

        $this->assertFalse($this->wishlist->products()->whereId($product->id)->exists());
    }

    #[Test]
    public function it_fails_to_remove_a_product_not_in_wishlist(): void
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson(route('wishlist.remove', $product->id));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                'type'    => 'error',
                'message' => __('Product not found in the wishlist'),
            ]);
    }

    #[Test]
    public function it_fails_to_remove_a_product_with_invalid_id(): void
    {
        $response = $this->deleteJson(route('wishlist.remove', 9999));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
