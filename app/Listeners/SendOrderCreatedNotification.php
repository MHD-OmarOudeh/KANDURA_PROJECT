<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Services\FirebaseNotificationService;

class SendOrderCreatedNotification
{
    public function __construct(
        protected FirebaseNotificationService $notificationService
    ) {
    }

    public function handle(OrderCreated $event): void
    {
        $order = $event->order;
        $order->load('orderItems.design.user'); // Load relationships

        \Log::info('🔔 SendOrderCreatedNotification: Starting', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
        ]);

        // 1. Push notification to admins (Firebase + DB)
        $admins = \App\Models\User::role(['admin', 'super_admin'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\OrderNotification(
                'New Order Created',
                "A new order #{$order->order_number} has been created. Tap to view the order details.",
                $order
            ));
        }
        \Log::info('✅ Database notifications sent to admins', ['count' => $admins->count()]);

        $this->notificationService->sendToAdmins(
            'New Order Created',
            "A new order #{$order->order_number} has been created.",
            [
                'type' => 'new_order_admin',
                'order_id' => (string) $order->id,
                'order_number' => $order->order_number,
                'click_action' => '/dashboard/orders'
            ]
        );
        \Log::info('📱 Push notification sent to admins');

        // 2. DB notification only for design owners (NO push)
        // Get unique design owners from order items
        $designOwnerIds = [];
        foreach ($order->orderItems as $item) {
            if ($item->design && $item->design->user) {
                $ownerId = $item->design->user_id;
                // Avoid duplicate notifications to same owner
                if (!in_array($ownerId, $designOwnerIds)) {
                    $designOwnerIds[] = $ownerId;
                    $designOwner = $item->design->user;
                    $designOwner->notify(new \App\Notifications\OrderNotification(
                        'New Order Created',
                        "A new order has been placed for your design. Tap to view the order details.",
                        $order
                    ));
                    \Log::info('✅ Database notification sent to design owner (no push)', [
                        'design_owner_id' => $ownerId,
                        'design_id' => $item->design_id
                    ]);
                }
            }
        }

        \Log::info('✅ SendOrderCreatedNotification: Completed');
    }
}
