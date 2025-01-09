<?php

declare(strict_types=1);

namespace Modules\Payment\Policies;

use Modules\Order\Enums\OrderStatus;
use Modules\Payment\Models\Payment;
use Modules\User\Models\User;

class PaymentPolicy
{
    public function verify(User $user, Payment $payment): bool
    {
        return $user->id === $payment->user_id && $payment->order->status === OrderStatus::PENDING->value;
    }
}
