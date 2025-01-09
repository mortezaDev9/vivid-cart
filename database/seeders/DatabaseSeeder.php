<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Cart\Database\Seeders\CartDatabaseSeeder;
use Modules\Category\Database\Seeders\CategoryDatabaseSeeder;
use Modules\Order\Database\Seeders\OrderDatabaseSeeder;
use Modules\Order\Database\Seeders\OrderItemDatabaseSeeder;
use Modules\Payment\Database\Seeders\PaymentDatabaseSeeder;
use Modules\Product\Database\Seeders\ProductDatabaseSeeder;
use Modules\Review\Database\Seeders\ReviewDatabaseSeeder;
use Modules\User\Database\Seeders\UserDatabaseSeeder;
use Modules\Wishlist\Database\Seeders\WishlistDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserDatabaseSeeder::class);
        $this->call(CategoryDatabaseSeeder::class);
        $this->call(ProductDatabaseSeeder::class);
        $this->call(ReviewDatabaseSeeder::class);
        $this->call(WishlistDatabaseSeeder::class);
        $this->call(CartDatabaseSeeder::class);
        $this->call(OrderDatabaseSeeder::class);
        $this->call(PaymentDatabaseSeeder::class);
    }
}
