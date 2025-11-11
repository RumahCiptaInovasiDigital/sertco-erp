<?php

namespace App\Services;

use App\Models\Notification;

/**
 * Class SendNotifToEmployee.
 */
class SendNotifToEmployee
{
    public function handle($Employee, $notification)
    {
        foreach ($Employee as $item) {
            Notification::create([
                'notification_id' => $notification->id,
                'karyawan_id' => $item,
                'sent_at' => now(),
                'is_sent' => true,
                'is_read' => false,
                'read_at' => null,
                'url' => $url ?? null,
            ]);
        }
    }
}
