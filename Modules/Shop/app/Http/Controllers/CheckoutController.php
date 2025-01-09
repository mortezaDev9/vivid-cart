<?php

declare(strict_types=1);

namespace Modules\Shop\Http\Controllers;

use Illuminate\View\View;
use Modules\Order\Enums\PaymentMethod;
use Modules\Order\Services\OrderService;
use Modules\Product\Models\Product;

readonly class CheckoutController
{
    public function __construct(private OrderService $orderService)
    {
    }

    public function __invoke(): View
    {
        $cart         = auth()->user()->cart()->with('products')->first();
        $subtotal     = $this->orderService->calculateTotalAmount($cart);
        $shippingCost = $this->orderService->calculateShippingCost($cart);

        return view('shop::checkout', [
            'products'       => $cart->products,
            'subtotal'       => $subtotal,
            'shippingCost'   => $shippingCost,
            'total'          => $subtotal + $shippingCost,
            'paymentMethods' => PaymentMethod::getValues(),
        ]);
    }
}
