<?php

namespace Modules\Wishlist\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;
use Modules\Product\Models\Product;
use Modules\Wishlist\Models\ProductWishlist;
use Modules\Wishlist\Models\Wishlist;

class ProductWishlistFactory extends Factory
{
    protected $model = ProductWishlist::class;

    public function definition(): array
    {
        $wishlistId = Wishlist::inRandomOrder()->value('id') ?? Wishlist::factory()->create()->id;

        $existingProductIds = ProductWishlist::whereWishlistId($wishlistId)->pluck('product_id')->toArray();

        $product = Product::whereNotIn('id', $existingProductIds)
            ->inRandomOrder()
            ->first() ?? Product::factory()->create();

        return [
            'wishlist_id' => $wishlistId,
            'product_id'  => $product->id,
            'quantity'    => fake()->numberBetween(1, $product->quantity),
        ];
    }
}
