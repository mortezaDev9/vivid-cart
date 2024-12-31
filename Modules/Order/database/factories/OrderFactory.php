<?php

declare(strict_types=1);

namespace Modules\Order\Database\Factories;

use Modules\Order\Models\Order;
use Modules\User\Models\User;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id'          => User::inRandomOrder()->value('id') ?? User::factory()->create()->id,
            'status'           => fake()->randomElement(OrderStatus::getValues()),
            'amount'           => fake()->randomFloat(2, 50, 1000),
            'shipping_address' => fake()->address(),
            'payment_method'   => fake()->randomElement(PaymentMethod::getValues()),
        ];
    }
}
