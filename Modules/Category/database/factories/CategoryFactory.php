<?php

declare(strict_types=1);

namespace Modules\Category\Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Category\Models\Category;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $categories = [
            'Electronics' => 'resources/images/icons/phone.svg',
            'Books'       => 'resources/images/icons/office.svg',
            'Grocery'     => 'resources/images/icons/outdoor-cafe.svg',
            'Furniture'   => 'resources/images/icons/sofa.svg',
            'Watches'     => 'resources/images/icons/service-hours.svg',
        ];

        static $index  = 0;
        $categoryNames = array_keys($categories);

        $name  = $categoryNames[$index];
        $index = ($index + 1) % count($categories);

        return [
            'name' => $name,
            'icon' => $categories[$name],
            'slug' => Str::slug($name),
        ];
    }
}
