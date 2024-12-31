<?php

namespace Modules\Payment\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Order\Models\Order;
use Modules\Payment\Enums\PaymentStatus;
use Modules\Payment\Models\Payment;
use Modules\User\Models\User;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $order = Order::inRandomOrder()->first() ?? Order::factory()->create();
        return [
            'user_id'         => User::inRandomOrder()->value('id') ?? User::factory()->create()->id,
            'order_id'        => Order::inRandomOrder()->value('id') ?? Order::factory()->create()->id,
            'amount'          => $order->amount,
            'transaction_id'  => fake()->optional()->uuid(),
            'reference_id'    => fake()->optional()->uuid(),
            'status'          => fake()->randomElement(PaymentStatus::getValues()),
            'payment_gateway' => fake()->randomElement(['ZarinPal', 'PayPal', 'Stripe']),
            'payment_data'    => null,
        ];
    }
}
