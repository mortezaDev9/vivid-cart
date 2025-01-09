<?php

declare(strict_types=1);

namespace Modules\Order\Services;

use Modules\Cart\Models\Cart;

class OrderService
{
    public function calculateTotalAmount(Cart $cart): int|float
    {
        return $cart->products->sum(function ($product) {
            return $product->pivot->quantity * $product->price;
        });
    }

    public function calculateShippingCost(Cart $cart): int|float
    {
        // TODO: calculate shipping cost
        return 100;
    }
}
