<?php

namespace App\Observers;

use App\Models\Order;
use App\Notifications\OrderStatusChangedNotification;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    public function updated(Order $order): void
    {
        // Check if status was changed
        if ($order->isDirty('status')) {
            $newStatus = $order->status;

            Log::info('Order status changed', [
                'order_id' => $order->id,
                'new_status' => $newStatus,
            ]);

            // Notify user about status change
            $order->user->notify(new OrderStatusChangedNotification($order, $newStatus));

            Log::info('Notification sent to user', ['user_id' => $order->user_id]);
        }
    }
}
