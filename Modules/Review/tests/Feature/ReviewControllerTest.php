<?php

declare(strict_types=1);

namespace Modules\Review\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Modules\Product\Models\Product;
use Modules\Review\Models\Review;
use Modules\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->user->cart()->create();
        $this->user->wishlist()->create();

        $this->product = Product::factory()->create();
    }

    #[Test]
    public function it_displays_reviews_for_the_authenticated_user(): void
    {
        Review::factory(5)->create([
            'user_id'    => $this->user->id,
            'product_id' => $this->product->id,
        ]);

        $paginator = Review::with('product')
            ->whereUserId(auth()->id())
            ->latest()
            ->paginate(5);

        $response = $this->get(route('reviews.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('review::index')
            ->assertViewHas('reviews', function ($viewReviews) use ($paginator) {
                return $viewReviews instanceof \Illuminate\Pagination\LengthAwarePaginator
                    && $viewReviews->items() == $paginator->items();
            });
    }

    #[Test]
    public function it_denies_user_from_submitting_multiple_reviews_for_the_same_product(): void
    {
        Review::factory()->create([
            'user_id'    => $this->user->id,
            'product_id' => $this->product->id,
        ]);

        $payload = ['rating' => 4, 'comment' => 'Great product!'];

        $response = $this->post(route('reviews.store', $this->product), $payload);

        $response->assertRedirect()
            ->assertSessionHas('error', __('You have already submitted a review for this product.'));

        $this->assertDatabaseMissing('reviews', [
            'user_id'    => $this->user->id,
            'product_id' => $this->product->id,
            'rating'     => $payload['rating'],
            'comment'    => $payload['comment'],
        ]);
    }

    #[Test]
    public function it_allows_user_to_submit_review_for_a_product(): void
    {
        $payload = ['rating' => 5, 'comment' => 'Excellent product!'];

        $response = $this->post(route('reviews.store', $this->product), $payload);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(url()->previous())
            ->assertSessionHas('success', __('Your review has been submitted successfully.'));

        $this->assertDatabaseHas('reviews', [
            'user_id'    => $this->user->id,
            'product_id' => $this->product->id,
            'rating'     => $payload['rating'],
            'comment'    => $payload['comment'],
        ]);
    }

    #[Test]
    public function it_cannot_submit_review_with_invalid_data(): void
    {
        $testCases = [
            'missing_rating'           => [
                'payload' => ['comment' => 'Great product!'],
                'field'   => __('rating'),
            ],
            'less_than_zero_rating'    => [
                'payload' => ['rating' => -1, 'comment' => 'Great product!'],
                'field'   => __('rating'),
            ],
            'greater_than_five_rating' => [
                'payload' => ['rating' => 6, 'comment' => 'Great product!'],
                'field'   => __('rating'),
            ],
            'missing_comment'          => [
                'payload' => ['rating' => 4],
                'field'   => __('comment'),
            ],
            'long_comment'             => [
                'payload' => ['rating' => 4, 'comment' => str_repeat('a', 256)],
                'field'   => __('comment'),
            ],
        ];

        foreach ($testCases as $testCase => $data) {
            $response = $this->post(route('reviews.store', $this->product), $data['payload']);

            $response->assertStatus(Response::HTTP_FOUND)
                ->assertRedirect(url()->previous());
            $response->assertSessionHasErrors([$data['field']]);

            $this->assertDatabaseMissing('reviews', [
                'user_id'    => $this->user->id,
                'product_id' => $this->product->id,
                'rating'     => $payload['rating'] ?? null,
                'comment'    => $payload['comment'] ?? null,
            ]);
        }
    }

    #[Test]
    public function it_redirects_to_login_if_user_is_not_authenticated(): void
    {
        auth()->logout();

        $response = $this->post(route('reviews.store', $this->product), [
            'rating' => 5,
            'comment' => 'Amazing product!',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('login');
    }
}
