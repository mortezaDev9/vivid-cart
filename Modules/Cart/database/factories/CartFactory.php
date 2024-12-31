<?php

namespace Modules\Cart\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Cart\Models\Cart;
use Modules\User\Models\User;

class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition(): array
    {
        $existingUserIds = User::all()->pluck('id')->toArray();

        $userId = User::whereNotIn('id', $existingUserIds)->inRandomOrder()
            ->value('id') ?? User::factory()->create()->id;

        return ['user_id'  => $userId];
    }
}

