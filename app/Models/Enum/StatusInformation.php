<?php

namespace App\Models\Enum;

enum StatusInformation:string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public static function values(): array{
        return [
            self::ACTIVE,
            self::INACTIVE,
        ];
    }
}
