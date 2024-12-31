<?php

declare(strict_types=1);

namespace Modules\Home\Http\Controllers;

use Illuminate\View\View;
use Modules\Category\Models\Category;
use Modules\Product\Models\Product;

class HomeController
{
    public function __invoke(): View
    {
        $products    = Product::with('reviews')->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->limit(10)
            ->get();
        $newProducts = Product::with('reviews')->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->whereBetween('created_at', [now()->subDays(7), now()])
            ->limit(4)
            ->get();

        $categories  = Category::all();

        return view('home::index', [
            'products'    => $products,
            'newProducts' => $newProducts,
            'categories'  => $categories,
        ]);
    }
}
