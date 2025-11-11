<?php

namespace App\Events\Notification;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationEvent
{
    use Dispatchable;
    use SerializesModels;

    public $karyawan_id;
    public $title;
    public $message;
    public $url;

    public function __construct($karyawan_id, $title, $message, $url)
    {
        $this->karyawan_id = $karyawan_id;  // Bisa null untuk semua user
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;
    }
}
