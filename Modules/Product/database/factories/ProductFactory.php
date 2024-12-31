<?php

declare(strict_types=1);

namespace Modules\Product\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Category\Models\Category;
use Modules\Order\Enums\DiscountType;
use Modules\Product\Models\Product;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $title         = fake()->unique()->words(5, true);
        $discountType  = fake()->optional()->randomElement(DiscountType::getValues());
        $discountValue = null;

        if ($discountType !== null) {
            $discountValue = $discountType === DiscountType::PERCENT
                ? fake()->numberBetween(1, 100)
                : fake()->randomFloat(2, 1, 100);
        }

        return [
            'category_id'    => Category::inRandomOrder()->value('id') ?? Category::factory()->create()->id,
            'title'          => $title,
            'slug'           => Str::slug($title),
            'description'    => fake()->paragraph(),
            'picture'        => fake()->imageUrl(),
            'price'          => fake()->randomFloat(2, 10, 1000),
            'quantity'       => fake()->numberBetween(1, 1000),
            'discount_type'  => $discountType,
            'discount_value' => $discountValue,
        ];
    }
}
