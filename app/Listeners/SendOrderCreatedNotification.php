<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Support\Facades\Notification;

class SendOrderCreatedNotification
{
    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        // Send notification to admin users (optional)
        // You can implement this later based on your notification system

        // Example: Notify user about order creation
        // $order->user->notify(new \App\Notifications\OrderCreatedNotification($order));
    }
}
