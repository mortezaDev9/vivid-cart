<?php

namespace Modules\Wishlist\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Wishlist\Models\ProductWishlist;
use Modules\Wishlist\Models\Wishlist;

class WishlistDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Wishlist::factory(10)->create();
        ProductWishlist::factory(100)->create();
    }
}
