<?php

declare(strict_types=1);

namespace Modules\Order\Enums;

enum DiscountType: string
{
    case PERCENT = 'percentage';
    case AMOUNT  = 'amount';

    public static function getValues(): array
    {
        return array_map(fn($rule) => $rule->value, self::cases());
    }
}
