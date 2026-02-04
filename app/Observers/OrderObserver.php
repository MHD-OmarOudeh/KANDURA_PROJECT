<?php

namespace App\Observers;

use App\Models\Order;
use App\Events\OrderCreated;
use App\Events\OrderCompleted;
use App\Events\OrderStatusChanged;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    public function created(Order $order): void
    {
        Log::info('Order created', ['order_id' => $order->id]);

        event(new OrderCreated($order));
    }

    public function updated(Order $order): void
    {
        // Check if status was changed
        if ($order->wasChanged('status')) {
            $newStatus = $order->status;

            Log::info('Order status changed', [
                'order_id' => $order->id,
                'old_status' => $order->getOriginal('status'),
                'new_status' => $newStatus,
            ]);

            // Fire generic status changed event
            event(new OrderStatusChanged($order, $newStatus));

            // Fire completed event for invoice generation
            if ($newStatus === 'completed') {
                Log::info('Order completed, dispatching event', ['order_id' => $order->id]);
                event(new OrderCompleted($order));
            }
        }
    }
}
