<?php

declare(strict_types=1);

namespace Modules\Payment\Tests\Feature;

use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\MockInterface;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderItem;
use Modules\Payment\Models\Payment;
use Modules\Payment\Enums\PaymentStatus;
use Modules\Payment\Gateways\PaymentGatewayInterface;
use Modules\Product\Models\Product;
use Modules\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    private Order $order;
    private MockInterface $paymentGateway;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->paymentGateway = $this->mock(PaymentGatewayInterface::class);

        $this->order = Order::factory()->create([
            'user_id' => $this->user->id,
            'amount'  => rand(1000, 10000),
            'status'  => OrderStatus::PENDING->value,
        ]);
    }

    #[Test]
    public function it_processes_payment_successfully(): void
    {
        $this->paymentGateway->shouldReceive('request')->once()->andReturn([
            'data' => [
                'code' => 100,
                'authority' => 'test-authority-id',
            ]
        ]);
        $this->paymentGateway->shouldReceive('pay')->once()->andReturn(new RedirectResponse('/'));

        $response = $this->post(route('payments.handle', $this->order));

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect('/');

        $this->assertDatabaseHas('payments', [
            'user_id'        => auth()->id(),
            'order_id'       => $this->order->id,
            'amount'         => $this->order->amount,
            'transaction_id' => 'test-authority-id',
        ]);
    }

    #[Test]
    public function it_fails_when_payment_gateway_request_fails(): void
    {
        $this->paymentGateway->shouldReceive('request')->once()->andReturn(['data' => ['code' => 0]]);

        $response = $this->post(route('payments.handle', $this->order));

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect('/');
        $response->assertSessionHas('error', __('Payment request failed, please try again.'));
    }

    #[Test]
    public function it_handles_payment_processing_exception(): void
    {
        $this->paymentGateway->shouldReceive('request')
            ->once()
            ->andThrow(new Exception('Simulated payment gateway error'));
        Log::shouldReceive('error')->once()->with('Payment handling error: Simulated payment gateway error');

        $response = $this->post(route('payments.handle', $this->order));

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect('/');
        $response->assertSessionHas('error', __('An error occurred while processing payment, please try again.'));
    }

    #[Test]
    public function it_handles_successful_payment_in_callback_correctly(): void
    {
        Payment::factory()->create([
            'order_id'       => $this->order->id,
            'transaction_id' => 'test-authority-id',
            'status'         => PaymentStatus::PENDING->value,
        ]);

        $response = [
            'data' => [
                'code' => 100,
                'ref_id' => 'payment-ref-id',
            ]
        ];

        $this->paymentGateway->shouldReceive('verify')->once()->andReturn($response);

        $response = $this->get(route('payments.callback', [
            'Authority' => 'test-authority-id',
            'Status'    => 'OK',
        ]));

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('orders.index');

        $this->assertDatabaseHas('payments', [
            'transaction_id' => 'test-authority-id',
            'reference_id'   => 'payment-ref-id',
            'status'         => PaymentStatus::SUCCESS->value,
        ]);

        $this->assertDatabaseHas('orders', [
            'id'     => $this->order->id,
            'status' => OrderStatus::VERIFIED->value,
        ]);
    }

    #[Test]
    public function it_fails_payment_callback_when_status_is_not_ok(): void
    {
        $response = $this->get(route('payments.callback', [
            'Authority' => 'non-existing-id',
            'Status'    => 'NOT_OK',
        ]));

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('orders.index');
        $response->assertSessionHas('error', __('Payment was not successful, please contact support if you have been charged.'));
    }

    #[Test]
    public function it_fails_payment_callback_when_payment_record_not_found(): void
    {
        $response = $this->get(route('payments.callback', [
            'Authority' => 'non-existing-id',
            'Status'    => 'OK',
        ]));

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('orders.index');
        $response->assertSessionHas('error', __('Payment record not found, please contact support.'));
    }

    #[Test]
    public function it_fails_payment_verification_when_gateway_fails(): void
    {
        Payment::factory()->create([
            'order_id'       => $this->order->id,
            'transaction_id' => 'test-authority-id',
            'status'         => PaymentStatus::PENDING->value,
        ]);

        $this->paymentGateway->shouldReceive('verify')->once()->andReturn(['data' => ['code' => 0]]);

        $response = $this->get(route('payments.callback', [
            'Authority' => 'test-authority-id',
            'Status'    => 'OK',
        ]));

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('orders.index');
        $response->assertSessionHas('error', __('Payment verification failed. Please contact support.'));
    }

    #[Test]
    public function it_handles_already_paid_orders(): void
    {
        Payment::factory()->create([
            'order_id'       => $this->order->id,
            'transaction_id' => 'test-authority-id',
            'status'         => PaymentStatus::SUCCESS->value,
        ]);

        $this->paymentGateway->shouldReceive('verify')->once()->andReturn(['data' => ['code'   => 101]]);

        $response = $this->get(route('payments.callback', [
            'Authority' => 'test-authority-id',
            'Status'    => 'OK',
        ]));

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('orders.index');
        $response->assertSessionHas('info', __('You have already paid for this order.'));
    }

    #[Test]
    public function it_updates_product_quantity_after_successful_payment(): void
    {
        $product = Product::factory()->create(['quantity' => 100]);
        OrderItem::factory()->create([
            'order_id'   => $this->order->id,
            'product_id' => $product->id,
            'quantity'   => 2,
        ]);

        Payment::factory()->create([
            'order_id'       => $this->order->id,
            'transaction_id' => 'test-authority-id',
            'status'         => PaymentStatus::PENDING->value,
        ]);

        $this->paymentGateway->shouldReceive('verify')->once()->andReturn([
            'data' => [
                'code'   => 100,
                'ref_id' => 'payment-ref-id',
            ]
        ]);

        $response = $this->get(route('payments.callback', [
            'Authority' => 'test-authority-id',
            'Status'    => 'OK',
        ]));

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('orders.index');
        $this->assertDatabaseHas('products', [
            'id'       => $product->id,
            'quantity' => 98,
        ]);
    }
}
