<?php

namespace App\Services;

use App\Events\Notification\NotificationEvent;
use App\Models\Notification;

/**
 * Class SendNotifToEmployee.
 */
class SendNotifToEmployee
{
    public function handle($Employee, $notification, $is_sent, $url)
    {
        Notification::where('notification_id', $notification->id)->delete();
        foreach ($Employee as $item) {
            Notification::create([
                'notification_id' => $notification->id,
                'karyawan_id' => $item,
                'sent_at' => now(),
                'is_sent' => $is_sent,
                'is_read' => false,
                'read_at' => null,
                'url' => $url,
            ]);

            if ($is_sent == true) {
                event(new NotificationEvent($notification->id, $item, $is_sent, $url));
            }
        }
    }
}
