<?php

namespace Modules\Payment\Enums;

enum PaymentStatus: string
{
    case PENDING   = 'pending';
    case SUCCESS   = 'success';
    case CANCELLED = 'cancelled';
    case FAILED    = 'failed';

    public static function getValues(): array
    {
        return array_map(fn($rule) => $rule->value, self::cases());
    }
}
