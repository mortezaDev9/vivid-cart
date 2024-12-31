<?php

declare(strict_types=1);

namespace Modules\Wishlist\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Modules\Product\Models\Product;

readonly class WishlistController
{
    public function index(): View
    {
        return view('wishlist::index', [
            'products' => auth()->user()->wishlist()->with('products')->first()->products,
        ]);
    }

    public function toggle(Product $product): JsonResponse
    {
        $wishlist     = auth()->user()->wishlist;
        $wishlistItem = $wishlist->products()->whereId($product->id)->exists();

        if (! $wishlistItem) {
            $wishlist->products()->attach($product->id, ['quantity' => 1]);

            return response()->json([
                'type'          => 'success',
                'message'       => __('Product added to your wishlist'),
                'isWishlisted'  => true,
                'wishlistCount' => $wishlist->products()->count(),
            ]);
        }

        return $this->remove($product);
    }

    public function remove(Product $product): JsonResponse
    {
        $wishlist = auth()->user()->wishlist;

        if (! $wishlist->products->contains($product)) {
            return response()->json([
                'type'    => 'error',
                'message' => __('Product not found in the wishlist'),
            ], Response::HTTP_NOT_FOUND);
        }

        $wishlist->products()->detach($product->id);

        return response()->json([
            'type'          => 'info',
            'message'       => __('Product removed from your wishlist'),
            'isWishlisted'  => false,
            'wishlistCount' => $wishlist->products()->count(),
        ]);
    }
}
