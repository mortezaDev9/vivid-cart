<?php

declare(strict_types=1);

namespace Modules\Cart\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Modules\Order\Services\OrderService;
use Modules\Product\Models\Product;

readonly class CartController
{
    public function __construct(private OrderService $orderService)
    {
    }

    public function index(): View
    {
        $cart = auth()->user()->cart()->with(['products' => function ($query) {
            $query->withPivot('quantity');
        }])->first();

        return view('cart::index', [
            'products'   => $cart->products,
            'subtotal'   => $this->orderService->calculateTotalAmount($cart),
            'quantities' => $cart->products->pluck('pivot.quantity', 'id')->toArray(),
        ]);
    }

    public function toggle(Product $product): JsonResponse
    {
        $cart     = auth()->user()->cart;
        $cartItem = $cart->products()->whereId($product->id)->exists();

        if ($product->quantity < 1) {
            return response()->json([
                'type'    => 'error',
                'message' => __('Failed to add product to cart'),
                'errors'  => ['quantity' => __('Product is out of stock')],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (! $cartItem) {
            $cart->products()->attach($product->id, ['quantity' => 1]);

            return response()->json([
                'type'      => 'success',
                'message'   => __('Product added to your cart'),
                'isInCart'  => true,
                'cartCount' => $cart->products->count(),
            ]);
        }

        return $this->remove($product);
    }

    public function remove(Product $product): JsonResponse
    {
        $cart = auth()->user()->cart;

        if (! $cart->products->contains($product)) {
            return response()->json([
                'type'    => 'error',
                'message' => __('Product not found in the cart'),
            ], Response::HTTP_NOT_FOUND);
        }

        $cart->products()->detach($product->id);

        return response()->json([
            'type'      => 'info',
            'message'   => __('Product removed from your cart'),
            'isInCart'  => false,
            'cartCount' => $cart->products()->count(),
        ]);
    }

    public function updateProductQuantity(Request $request, Product $product): JsonResponse
    {
        $cart     = auth()->user()->cart;
        $cartItem = $cart->products()->firstWhere('id', $product->id);

        if (! $cartItem) {
            return response()->json([
                'type'    => 'error',
                'message' => __('Product not found in the cart'),
                'data'    => $cart,
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $validated = $request->validate([
                'quantity' => ['required', 'integer', 'min:1', 'max:'.$product->quantity],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'type'    => 'error',
                'message' => __('Validation failed'),
                'errors'  => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ((int) $validated['quantity'] === $cartItem->pivot->quantity) {
            return response()->json([
                'type'    => 'info',
                'message' => __('No changes detected in quantity'),
            ]);
        }

        $cart->products()->updateExistingPivot($product, ['quantity' => $validated['quantity']]);

        return response()->json([
            'type'    => 'success',
            'message' => __('Product quantity updated in cart'),
        ]);
    }
}
