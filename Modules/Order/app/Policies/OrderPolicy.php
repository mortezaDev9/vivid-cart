<?php

declare(strict_types=1);

namespace Modules\Order\Policies;

use Modules\Order\Models\Order;
use Modules\User\Models\User;

class OrderPolicy
{
    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->user_id;
    }

    public function cancel(User $user, Order $order): bool
    {
        return $user->id === $order->user_id;
    }
}
