<?php

declare(strict_types=1);

namespace Modules\Review\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Models\Product;
use Modules\Review\Models\Review;
use Modules\User\Models\User;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'user_id'    => User::inRandomOrder()->value('id') ?? User::factory()->create()->id,
            'product_id' => Product::inRandomOrder()->value('id') ?? Product::factory()->create()->id,
            'rating'     => fake()->numberBetween(0, 5),
            'comment'    => fake()->text(),
        ];
    }
}
