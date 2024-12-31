<?php

declare(strict_types=1);

namespace Modules\User\Enums;

enum UserRole: string
{
    case CUSTOMER = 'customer';
    case STAFF    = 'staff';
    case ADMIN    = 'admin';

    public static function getValues(): array
    {
        return array_map(fn($rule) => $rule->value, self::cases());
    }
}
