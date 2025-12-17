<?php

namespace App\Models\Enum;

enum StatusNotif: string {
    case SENT = 'sent';
    case PENDING = 'pending';
    case FAILED = 'failed';
    case READ = 'read';

    public static function values(): array {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
