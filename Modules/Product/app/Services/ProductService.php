<?php

declare(strict_types=1);

namespace Modules\Product\Services;

readonly class ProductService
{
    public function isWishlisted(int $productId): bool
    {
        $wishlist = auth()->check() ? auth()->user()->wishlist : null;

        if (is_null($wishlist)) {
            return false;
        }

        return $wishlist->products()->firstWhere('id', $productId) !== null;
    }

    public function isInCart(int $productId): bool
    {
        $cart = auth()->check() ? auth()->user()->cart : null;

        if (is_null($cart)) {
            return false;
        }

        return $cart->products()->firstWhere('id', $productId) !== null;
    }
}
