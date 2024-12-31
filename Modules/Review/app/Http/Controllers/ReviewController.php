<?php

declare(strict_types=1);

namespace Modules\Review\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Modules\Product\Models\Product;
use Modules\Review\Http\Requests\StoreReviewRequest;
use Modules\Review\Models\Review;

class ReviewController
{
    public function index()
    {
        return view('review::index', [
            'reviews' => Review::with('product')
                ->whereUserId(auth()->id())
                ->latest()
                ->paginate(5),
        ]);
    }

    public function store(StoreReviewRequest $request, Product $product): RedirectResponse
    {
        if (Review::whereUserId(auth()->id())->whereProductId($product->id)->exists()) {
            return redirect()->back()->with('error', __('You have already submitted a review for this product.'));
        }

        $validated = $request->validated();

        Review::create([
            'user_id'    => auth()->id(),
            'product_id' => $product->id,
            'rating'     => $validated['rating'],
            'comment'    => $validated['comment'],
        ]);

        return redirect()->back()->with('success', __('Your review has been submitted successfully.'));
    }
}
