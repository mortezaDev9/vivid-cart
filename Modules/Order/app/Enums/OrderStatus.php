<?php

declare(strict_types=1);

namespace Modules\Order\Enums;

enum OrderStatus: string
{
    case PENDING   = 'pending';
    case VERIFIED  = 'verified';
    case SHIPPED   = 'shipped';
    case DECLINED  = 'declined';
    case CANCELLED = 'cancelled';

    public static function getValues(): array
    {
        return array_map(fn($rule) => $rule->value, self::cases());
    }
}
