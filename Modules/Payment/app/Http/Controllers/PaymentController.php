<?php

namespace Modules\Payment\Http\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderItem;
use Modules\Payment\Enums\PaymentStatus;
use Modules\Payment\Gateways\PaymentGatewayInterface;
use Modules\Payment\Models\Payment;

readonly class PaymentController
{
    public function __construct(private PaymentGatewayInterface $paymentGateway)
    {
    }

    public function handle(Order $order): RedirectResponse
    {
        Gate::authorize('handle', $order);

        try {
            $description = sprintf(
                'Payment for order #%d: %d totaling %s',
                $order->id,
                $order->items()->count(),
                '$'.number_format($order->amount / 100, 2)
            );

            $paymentRequestResponse = $this->paymentGateway->request(
                (int) $order->amount,
                route(config('payment.callback')),
                $description,
                [
                    'email'  => auth()->user()->email,
                    'phone'  => auth()->user()->phone,
                ],
            );

            if (isset($paymentRequestResponse['data']['code']) && $paymentRequestResponse['data']['code'] === 100) {
                Payment::create([
                    'user_id'         => auth()->id(),
                    'order_id'        => $order->id,
                    'amount'          => $order->amount,
                    'transaction_id'  => $paymentRequestResponse['data']['authority'],
                    'payment_gateway' => config('payment.payment_gateway'),
                ]);

                return $this->paymentGateway->pay($paymentRequestResponse['data']['authority']);
            } else {
                Log::error('Payment request failed: ', $paymentRequestResponse);

                return back()->with('error', __('Payment request failed, please try again.'));
            }
        } catch (Exception $e) {
            Log::error('Payment handling error: ' . $e->getMessage());

            return back()->with('error', __('An error occurred while processing payment, please try again.'));
        }
    }

    public function callback(Request $request): RedirectResponse
    {
        $authority = $request->query('Authority');
        $status    = $request->query('Status');

        if ($status !== 'OK') {
            return to_route('orders.index')->with(
                'error',
                __('Payment was not successful, please contact support if you have been charged.')
            );
        }

        $paymentRecord = Payment::whereTransactionId($authority)->first();

        if (! $paymentRecord) {
            return to_route('orders.index')->with('error', __('Payment record not found, please contact support.'));
        }

        Gate::authorize('verify', $paymentRecord);

        $paymentVerificationResponse = $this->paymentGateway->verify((int) $paymentRecord->amount, $authority);

        if (isset($paymentVerificationResponse['data']['code']) && $paymentVerificationResponse['data']['code'] === 100) {
            try {
                DB::transaction(function () use ($paymentRecord, $paymentVerificationResponse) {
                    Order::whereId($paymentRecord->order_id)->update(['status' => OrderStatus::VERIFIED->value]);

                    $paymentRecord->update([
                        'status'          => PaymentStatus::SUCCESS->value,
                        'reference_id'    => $paymentVerificationResponse['data']['ref_id'],
                    ]);

                    $orderItems = OrderItem::with('product')->whereOrderId($paymentRecord->order_id)->get();

                    foreach ($orderItems as $orderItem) {
                        $product = $orderItem->product;
                        $product->update(['quantity' => $product->quantity - $orderItem->quantity]);
                    }
                });
            } catch (Exception $e) {
                Log::error('Payment verification transaction failed: ' . $e->getMessage());

                $paymentRecord->update(['status' => PaymentStatus::FAILED->value]);

                return to_route('orders.index')->with('error', __('Payment verification failed. Please contact support.'));
            }

            toast('success', __('Your purchase has been set and will be shipped to you in the next 7 days.'));

            return to_route('orders.index');
        }

        if (isset($paymentVerificationResponse['data']['code']) && $paymentVerificationResponse['data']['code'] === 101) {
            toast('info', __('You have already paid for this order.'));

            return to_route('orders.index');
        }

        $paymentRecord->update(['status' => PaymentStatus::FAILED->value]);

        return to_route('orders.index')->with('error', __('Payment verification failed. Please contact support.'));
    }
}
