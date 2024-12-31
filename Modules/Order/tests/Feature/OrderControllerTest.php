<?php

declare(strict_types=1);

namespace Modules\Order\Tests\Feature;

use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Order\Enums\PaymentMethod;
use Modules\Order\Models\Order;
use Modules\Order\Enums\OrderStatus;
use Modules\Product\Models\Product;
use Modules\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderControllerTest extends TestCase
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
    public function it_displays_orders_for_authenticated_user(): void
    {
        $orders = Order::factory(3)->create(['user_id' => $this->user->id, 'status' => OrderStatus::PENDING->value]);

        $response = $this->get(route('orders.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('order::index')
            ->assertViewHas('orders');

        $viewOrders = $response->viewData('orders');

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $viewOrders);

        $this->assertEquals(
            $orders->pluck('id')->sort()->values()->toArray(),
            $viewOrders->pluck('id')->sort()->values()->toArray(),
        );
    }

    #[Test]
    public function it_creates_order(): void
    {
        $cart = $this->user->cart;
        $cart->products()->attach(Product::factory()->create(), ['quantity' => 1]);

        $payload = [
            'first_name'     => 'Morteza',
            'last_name'      => 'Ayashi',
            'address'        => '123 Main St',
            'payment_method' => 'credit card',
            'phone'          => '1234567890',
        ];

        $response = $this->post(route('orders.store'), $payload);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirectToRoute('orders.confirmation', ['order' => Order::first()]);

        $this->assertDatabaseHas('orders', [
            'user_id'          => $this->user->id,
            'shipping_address' => $payload['address'],
            'payment_method'   => $payload['payment_method'],
        ]);
    }

    #[Test]
    public function it_cannot_submit_order_with_invalid_data(): void
    {
        $testCases = [
            'missing_first_name' => [
                'payload' => [
                    'first_name'     => null,
                    'last_name'      => 'Doe',
                    'address'        => '123 Main St',
                    'payment_method' => PaymentMethod::CREDIT_CARD->value,
                    'phone'          => '1234567890',
                ],
                'field' => __('first_name'),
            ],
            'missing_last_name' => [
                'payload' => [
                    'first_name'     => 'John',
                    'last_name'      => null,
                    'address'        => '123 Main St',
                    'payment_method' => PaymentMethod::CREDIT_CARD->value,
                    'phone'          => '1234567890',
                ],
                'field' => __('last_name'),
            ],
            'missing_address' => [
                'payload' => [
                    'first_name'     => 'John',
                    'last_name'      => 'Doe',
                    'address'        => null,
                    'payment_method' => PaymentMethod::CREDIT_CARD->value,
                    'phone'          => '1234567890',
                ],
                'field' => __('address'),
            ],
            'address_too_short' => [
                'payload' => [
                    'first_name'     => 'John',
                    'last_name'      => 'Doe',
                    'address'        => 'Short',
                    'payment_method' => PaymentMethod::CREDIT_CARD->value,
                    'phone'          => '1234567890',
                ],
                'field' => __('address'),
            ],
            'invalid_payment_method' => [
                'payload' => [
                    'first_name'     => 'John',
                    'last_name'      => 'Doe',
                    'address'        => '123 Main St',
                    'payment_method' => 'invalid_method',
                    'phone'          => '1234567890',
                ],
                'field' => __('payment_method'),
            ],
            'missing_phone' => [
                'payload' => [
                    'first_name'     => 'John',
                    'last_name'      => 'Doe',
                    'address'        => '123 Main St',
                    'payment_method' => PaymentMethod::CREDIT_CARD->value,
                    'phone'          => null,
                ],
                'field' => __('phone'),
            ],
            'phone_too_short' => [
                'payload' => [
                    'first_name'     => 'John',
                    'last_name'      => 'Doe',
                    'address'        => '123 Main St',
                    'payment_method' => PaymentMethod::CREDIT_CARD->value,
                    'phone'          => '12345',
                ],
                'field' => __('phone'),
            ],
            'phone_too_long' => [
                'payload' => [
                    'first_name'     => 'John',
                    'last_name'      => 'Doe',
                    'address'        => '123 Main St',
                    'payment_method' => PaymentMethod::CREDIT_CARD->value,
                    'phone'          => '12345678901234567',
                ],
                'field' => __('phone'),
            ],
        ];


        foreach ($testCases as $testCase => $data) {
            $response = $this->post(route('orders.store'), $data['payload']);

            $response->assertStatus(Response::HTTP_FOUND)
                ->assertRedirect(url()->previous());
            $response->assertSessionHasErrors([$data['field']]);

            $this->assertDatabaseMissing('orders', [
                'user_id'        => $this->user->id,
                'first_name'     => $data['payload']['first_name'] ?? null,
                'last_name'      => $data['payload']['last_name'] ?? null,
                'address'        => $data['payload']['address'] ?? null,
                'payment_method' => $data['payload']['payment_method'] ?? null,
                'phone'          => $data['payload']['phone'] ?? null,
            ]);
        }
    }

    #[Test]
    public function it_displays_order_confirmation_for_authenticated_user(): void
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $response = $this->get(route('orders.confirmation', ['order' => $order]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('order::confirmation')
            ->assertViewHas('order', $order);
    }

    #[Test]
    public function it_denies_access_to_order_confirmation_if_user_does_not_own_order(): void
    {
        $otherUser = User::factory()->create();
        $order     = Order::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->get(route('orders.confirmation', ['order' => $order->id]));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    #[Test]
    public function it_allows_user_to_cancel_order(): void
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $response = $this->patch(route('orders.cancel', ['order' => $order->id]));

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('orders.index');

        $this->assertDatabaseHas('orders', [
            'id'     => $order->id,
            'status' => OrderStatus::CANCELLED->value,
        ]);
    }

    #[Test]
    public function it_denies_user_from_cancelling_other_users_orders(): void
    {
        $otherUser = User::factory()->create();
        $order     = Order::factory()->create(['user_id' => $otherUser->id, 'status' => OrderStatus::PENDING->value]);

        $response = $this->patch(route('orders.cancel', ['order' => $order->id]));

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseMissing('orders', [
            'id'      => $order->id,
            'user_id' => $otherUser->id,
            'status'  => OrderStatus::CANCELLED->value,
        ]);
    }

    #[Test]
    public function it_displays_cancellations_for_authenticated_user(): void
    {
        $cancellations = Order::factory(3)->create([
            'user_id' => $this->user->id,
            'status' => OrderStatus::CANCELLED->value,
        ]);

        $response = $this->get(route('orders.cancellations'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('order::cancellations')
            ->assertViewHas('cancellations');

        $viewCancellations = $response->viewData('cancellations');

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $viewCancellations);

        $this->assertEquals(
            $cancellations->pluck('id')->sort()->values()->toArray(),
            $viewCancellations->pluck('id')->sort()->values()->toArray()
        );
    }

    #[Test]
    public function it_redirects_to_login_if_user_is_not_authenticated(): void
    {
        auth()->logout();

        $response = $this->post(route('orders.store'), [
            'address' => '123 Main St',
            'payment_method' => 'credit_card',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('login');
    }
}
