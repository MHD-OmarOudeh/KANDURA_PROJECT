<?php

namespace App\Listeners;

use App\Events\DesignCreated;
use App\Services\FirebaseNotificationService;

class SendDesignCreatedNotification
{
    public function __construct(
        protected FirebaseNotificationService $notificationService
    ) {
    }

    public function handle(DesignCreated $event): void
    {
        \Log::info('🎨 SendDesignCreatedNotification: Starting');

        $design = $event->design;

        \Log::info('Design details', [
            'design_id' => $design->id,
            'design_name' => $design->name_en,
            'user_id' => $design->user_id ?? null,
        ]);

        // Store notification in database for admins
        $admins = \App\Models\User::role(['admin', 'super_admin'])->get();

        \Log::info('Found admins', ['count' => $admins->count()]);

        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\DesignNotification(
                'New Design Created',
                "A new design has been created by a user. Tap to review it in the design list",
                $design
            ));
        }

        \Log::info('✅ Database notifications sent to admins');

        // Push notification to admins
        $result = $this->notificationService->sendToAdmins(
            'New Design Created',
            "A new design '{$design->name_en}' has been created.",
            [
                'type' => 'design_created',
                'design_id' => (string) $design->id,
                'click_action' => '/dashboard/designs'
            ]
        );

        \Log::info('📱 Push notifications sent to admins', ['results' => $result]);
        \Log::info('✅ SendDesignCreatedNotification: Completed');
    }
}
