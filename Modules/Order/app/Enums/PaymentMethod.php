<?php

declare(strict_types=1);

namespace Modules\Order\Enums;

enum PaymentMethod: string
{
    case CREDIT_CARD      = 'credit card';
    case PAYPAL           = 'paypal';
    case CASH_ON_DELIVERY = 'cash on delivery';

    public static function getValues(): array
    {
        return array_map(fn($rule) => $rule->value, self::cases());
    }
}
