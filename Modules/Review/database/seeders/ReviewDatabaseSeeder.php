<?php

declare(strict_types=1);

namespace Modules\Review\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Review\Models\Review;

class ReviewDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Review::factory(100)->create();
    }
}
