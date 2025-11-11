<?php

namespace App\Listeners\Notification;

use App\Events\Notification\NotificationEvent;

class SendNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(NotificationEvent $event)
    {
        // Jika user_id null, notifikasi dikirim ke semua user
        if ($event->user_id) {
            Notification::create([
                'user_id' => $event->user_id,
                'title' => $event->title,
                'message' => $event->message,
                'url' => $event->url,
            ]);
        } else {
            $users = User::all();
            foreach ($users as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => $event->title,
                    'message' => $event->message,
                    'url' => $event->url,
                ]);
            }
        }
    }
}
