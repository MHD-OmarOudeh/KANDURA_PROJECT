<?php

namespace App\Notifications;

use App\Models\Design;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DesignCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(public Design $design)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'New Design Created',
            'body' => 'A new design has been created by a user. Tap to review it in the design list',
            'design_id' => $this->design->id,
            'type' => 'design_created',
        ];
    }
}
