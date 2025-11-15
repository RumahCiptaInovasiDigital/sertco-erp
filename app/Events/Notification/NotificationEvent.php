<?php

namespace App\Events\Notification;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationEvent implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $notification_id;
    public $karyawan_id;
    public $sent_at;
    public $is_sent;
    public $read_at;
    public $url;

    public function __construct($notification_id, $karyawan_id, $is_sent, $url)
    {
        $this->notification_id = $notification_id;
        $this->karyawan_id = $karyawan_id;
        $this->sent_at = now();
        $this->is_sent = $is_sent;
        $this->read_at = null;
        $this->url = $url;
    }

    public function broadcastOn()
    {
        return [
            new Channel('user-notification-'.$this->karyawan_id),
        ];
    }
}
