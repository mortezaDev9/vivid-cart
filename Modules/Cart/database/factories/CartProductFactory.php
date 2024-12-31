<?php

namespace Modules\Cart\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Cart\Models\Cart;
use Modules\Product\Models\Product;
use Modules\Cart\Models\CartProduct;

class CartProductFactory extends Factory
{
    protected $model = CartProduct::class;

    public function definition(): array
    {
        $cartId = Cart::inRandomOrder()->value('id') ?? Cart::factory()->create()->id;

        $existingProductIds = CartProduct::whereCartId($cartId)->pluck('product_id')->toArray();

        $product = Product::whereNotIn('id', $existingProductIds)
            ->inRandomOrder()
            ->first() ?? Product::factory()->create();

        return [
            'cart_id'    => $cartId,
            'product_id' => $product->id,
            'quantity'   => fake()->numberBetween(1, $product->quantity),
        ];
    }
}
