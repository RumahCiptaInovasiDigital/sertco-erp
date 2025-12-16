<?php

namespace App\Models\Enum;

enum TypeInformation:string
{
    case GENERAL = 'general';
    case URGENT = 'urgent';
    case EVENT = 'event';
    case REMINDER = 'reminder';

    public static function values(): array
    {
        return array_map(function($type){
            return $type->value;
        }, self::cases());
    }
}
