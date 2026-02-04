<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;

class SendOrderStatusChangedNotification
{
    public function handle(OrderStatusChanged $event): void
    {
        \Log::info('🔔 SendOrderStatusChangedNotification: Starting');

        $order = $event->order;
        $user = $order->user;
        $newStatus = $event->newStatus;

        \Log::info('Order status change details', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'new_status' => $newStatus,
            'user_id' => $user->id,
        ]);

        $statusMessages = [
            'pending' => 'is pending',
            'confirmed' => 'has been confirmed',
            'processing' => 'is being processed',
            'completed' => 'has been completed',
            'cancelled' => 'has been cancelled',
        ];

        $message = $statusMessages[$newStatus] ?? "status changed to {$newStatus}";

        // Store notification in database only (no push notification)
        $user->notify(new \App\Notifications\OrderNotification(
            'Order Status Updated',
            "The status of your order #{$order->order_number} has been updated to {$newStatus}. Tap to view details",
            $order
        ));

        \Log::info('✅ SendOrderStatusChangedNotification: Completed - DB notification sent (no push)');
    }
}
