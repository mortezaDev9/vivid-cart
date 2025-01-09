<?php

declare(strict_types=1);

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Cart\Models\Cart;
use Modules\User\Models\User;
use Modules\Wishlist\Models\Wishlist;

class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'username' => 'Morteza',
            'slug'     => 'morteza',
            'email'    => 'morteza.ayashi@gmail.com',
            'password' => Hash::make('12'),
        ]);

        Cart::create(['user_id' => $user->id]);
        Wishlist::create(['user_id' => $user->id]);

        User::factory(10)->create();
    }
}
