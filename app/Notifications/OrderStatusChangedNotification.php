<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order, public string $newStatus)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Order Status Updated',
            'body' => "The status of your order #{$this->order->order_number} has been updated to {$this->newStatus}. Tap to view details",
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'new_status' => $this->newStatus,
            'type' => 'order_status_changed',
        ];
    }
}
