<?php

namespace App\Models\Enum;


enum StatusDevice: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case BLOCKED = 'blocked';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
