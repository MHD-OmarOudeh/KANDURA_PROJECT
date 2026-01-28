<?php

namespace App\Observers;

use App\Models\Design;
use App\Models\User;
use App\Notifications\DesignCreatedNotification;
use Illuminate\Support\Facades\Log;

class DesignObserver
{
    public function created(Design $design): void
    {
        Log::info('Design created, sending notifications', ['design_id' => $design->id]);
        
        // Notify all admins
        $admins = User::role(['admin', 'super-admin'])->get();
        
        Log::info('Found admins', ['count' => $admins->count()]);
        
        foreach ($admins as $admin) {
            $admin->notify(new DesignCreatedNotification($design));
        }
        
        Log::info('Notifications sent to admins');
    }
}
