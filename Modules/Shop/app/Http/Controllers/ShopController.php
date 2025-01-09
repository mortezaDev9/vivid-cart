<?php

declare(strict_types=1);

namespace Modules\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Category\Models\Category;
use Modules\Product\Models\Product;

class ShopController
{
    public function __invoke(Request $request): View
    {
        $search              = $request->validate(['q' => ['string']]);
        $categories          = Category::withCount('products')->get();
        $sortOption          = $request->validate(['sort' => ['string']]);
        $categorySlugs       = $request->query('category', []);
        $validatedMinMax     = $request->validate([
            'min' => ['nullable', 'numeric'],
            'max' => ['nullable', 'numeric'],
        ]);
        $validatedCategories = $request->validate([
            'category.*' => ['string', 'exists:categories,slug']
        ]);

        $categorySlugs = array_unique(array_merge($categorySlugs, $validatedCategories['category'] ?? []));

        $query = Product::with('reviews')->withCount('reviews')
            ->withAvg('reviews', 'rating');

        if (! empty($search)) {
            $query->where('title','LIKE', '%'.$search['q'].'%');
        }

        if (! empty($sortOption)) {
            $query = match ($sortOption['sort']) {
                'price-low-to-high' => $query->orderBy('price'),
                'price-high-to-low' => $query->orderBy('price', 'desc'),
                'latest'            => $query->latest(),
                default             => $query->orderBy('created_at'),
            };
        }

        if (! empty($validatedMinMax)) {
            $minPrice = $validatedMinMax['min'];
            $maxPrice = $validatedMinMax['max'];

            if (! is_null($minPrice) && ! is_null($maxPrice)) {
                $query->whereBetween('price', [(float) $minPrice, (float) $maxPrice]);
            } elseif (! is_null($minPrice)) {
                $query->where('price', '>=', (float) $minPrice);
            } elseif (! is_null($maxPrice)) {
                $query->where('price', '<=', (float) $maxPrice);
            }
        }

        if (! empty($categorySlugs)) {
            $selectedCategories = Category::whereIn('slug', $categorySlugs)->get();

            if ($selectedCategories) {
                $products = $query->whereIn('category_id', $selectedCategories->pluck('id'))->paginate(15);
            } else {
                $products = $query->paginate(15);
            }
        } else {
            $products = $query->paginate(15);
        }

        return view('shop::index', [
            'products'              => $products,
            'categories'            => $categories,
            'sortOption'            => $sortOption,
            'selectedCategorySlugs' => $categorySlugs,
        ]);
    }
}
