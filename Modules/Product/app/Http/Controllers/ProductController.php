<?php

declare(strict_types=1);

namespace Modules\Product\Http\Controllers;

use Illuminate\View\View;
use Modules\Order\Enums\OrderStatus;
use Modules\Product\Models\Product;
use Modules\Review\Models\Review;

class ProductController
{
    public function index(Product $product): View
    {
        $product->load(['images', 'reviews' => function ($query) {
            $query->latest();
        }, 'reviews.user'])
            ->loadCount('reviews')
            ->loadAvg('reviews', 'rating');

        $hasBoughtProduct = auth()->user()?->orders()
            ->whereHas('items', function ($query) use ($product) {
                $query->whereProductId($product->id);
            })
            ->whereIn('status', [OrderStatus::VERIFIED, OrderStatus::SHIPPED])
            ->exists();

        $relatedProducts = Product::with('reviews')->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->whereCategoryId($product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('product::index', [
            'product'            => $product,
            'hasBoughtProduct'   => $hasBoughtProduct,
            'hasReviewedProduct' => Review::whereUserId(auth()->id())->whereProductId($product->id)->exists(),
            'relatedProducts'    => $relatedProducts,
        ]);
    }
}
