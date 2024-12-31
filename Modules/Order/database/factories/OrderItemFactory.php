<?php

declare(strict_types=1);

namespace Modules\Order\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderItem;
use Modules\Product\Models\Product;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        return [
            'order_id'   => Order::inRandomOrder()->value('id') ?? Order::factory()->create()->id,
            'product_id' => Product::inRandomOrder()->value('id') ?? Product::factory()->create()->id,
            'price'      => fake()->randomFloat(2, 10, 1000),
            'quantity'   => fake()->numberBetween(1, 1000),
        ];
    }
}
