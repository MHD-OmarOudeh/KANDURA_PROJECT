<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'New Order Created',
            'body' => "A new order has been placed for your design. Tap to view the order details.",
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'type' => 'order_created',
            'action' => 'go to order list',
            'methods' => 'DB / Push notifications (for admin)',
        ];
    }
}
