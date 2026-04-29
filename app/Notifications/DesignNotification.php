<?php

namespace App\Notifications;

use App\Models\Design;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DesignNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $message,
        public Design $design
    ) {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'design_id' => $this->design->id,
            'design_name' => $this->design->name_en,
            'type' => 'design',
            'action' => 'go_to_design_list',
            'action_url' => '/designs/' . $this->design->id,
        ];
    }
}
