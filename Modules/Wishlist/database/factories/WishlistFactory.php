<?php

namespace Modules\Wishlist\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Models\User;
use Modules\Wishlist\Models\Wishlist;

class WishlistFactory extends Factory
{
    protected $model = Wishlist::class;

    public function definition(): array
    {
        $existingUserIds = User::all()->pluck('id')->toArray();

        $userId = User::whereNotIn('id', $existingUserIds)->inRandomOrder()
            ->value('id') ?? User::factory()->create()->id;

        return ['user_id'  => $userId];
    }
}

