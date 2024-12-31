<?php

declare(strict_types=1);

namespace Modules\Order\Http\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Http\Requests\StoreOrderRequest;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderItem;
use Modules\Order\Services\OrderService;

readonly class OrderController
{

    public function __construct(private OrderService $orderService)
    {
    }

    public function index(): View
    {
        return view('order::index', [
            'orders' => Order::with('items.product')->whereUserId(auth()->id())
                ->where('status', '!=', OrderStatus::CANCELLED)
                ->latest()
                ->paginate(3)
        ]);
    }

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $validated  = $request->validated();
        $cart       = auth()->user()->cart()->with('products')->first();
        $products   = $cart->products;
        $quantities = $products->pluck('pivot.quantity', 'id');

        try {
            $order = DB::transaction(function () use ($validated, $cart, $products, $quantities) {
                $order = Order::create([
                    'user_id'          => auth()->id(),
                    'amount'           => $this->orderService->calculateTotalAmount($cart),
                    'shipping_address' => $validated['address'],
                    'payment_method'   => $validated['payment_method'],
                ]);

                foreach ($products as $product) {
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $product->id,
                        'price'      => $product->price,
                        'quantity'   => $quantities[$product->id],
                    ]);
                }

                return $order;
            });
        } catch (Exception $exception) {
            Log::error('Transaction for creating order failed: ' . $exception->getMessage());

            return back()->with('error', __('Placing order failed, please try again.'));
        }

        $cart->products()->detach();

        return to_route('orders.confirmation', ['order' => $order]);
    }

    public function confirmationView(Order $order): View
    {
        Gate::authorize('view', $order);

        return view('order::confirmation', ['order' => $order]);
    }

    public function cancel(Order $order): RedirectResponse
    {
        Gate::authorize('cancel', $order);

        $order->update(['status' => OrderStatus::CANCELLED->value]);

        return to_route('orders.index');
    }

    public function cancellations(): View
    {
        return view('order::cancellations',[
            'cancellations' => Order::with('items.product')->whereUserId(auth()->id())
                ->whereStatus(OrderStatus::CANCELLED)
                ->latest()
                ->paginate(3)
        ]);
    }
}
