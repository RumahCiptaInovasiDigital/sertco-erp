<?php

namespace App\Services;

use App\Events\Notification\NotificationEvent;
use App\Models\Notification;

/**
 * Class SendNotifToEmployee.
 */
class SendNotifToEmployee
{
    public function handle($Employee, $notification, $url)
    {
        foreach ($Employee as $item) {
            Notification::create([
                'notification_id' => $notification->id,
                'karyawan_id' => $item,
                'sent_at' => now(),
                'is_sent' => true,
                'is_read' => false,
                'read_at' => null,
                'url' => $url,
            ]);

            event(new NotificationEvent($notification->id, $item, $url));
        }
    }
}
