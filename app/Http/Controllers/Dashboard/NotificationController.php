<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get notifications for the authenticated admin
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Get unread notifications
        $unreadNotifications = $user->unreadNotifications()
            ->whereIn('type', [
                'App\Notifications\OrderNotification',
                'App\Notifications\DesignNotification'
            ])
            ->take(10)
            ->get();

        // Get read notifications
        $readNotifications = $user->notifications()
            ->whereIn('type', [
                'App\Notifications\OrderNotification',
                'App\Notifications\DesignNotification'
            ])
            ->whereNotNull('read_at')
            ->take(10)
            ->get();

        return response()->json([
            'unread' => $unreadNotifications,
            'read' => $readNotifications,
            'unread_count' => $user->unreadNotifications()
                ->whereIn('type', [
                    'App\Notifications\OrderNotification',
                    'App\Notifications\DesignNotification'
                ])
                ->count()
        ]);
    }

    /**
     * Get unread notifications count
     */
    public function unreadCount(Request $request)
    {
        $user = auth()->user();

        $count = $user->unreadNotifications()
            ->whereIn('type', [
                'App\Notifications\OrderNotification',
                'App\Notifications\DesignNotification'
            ])
            ->count();

        return response()->json([
            'count' => $count
        ]);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        $user = auth()->user();

        $notification = $user->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        $user = auth()->user();

        $user->unreadNotifications()
            ->whereIn('type', [
                'App\Notifications\OrderNotification',
                'App\Notifications\DesignNotification'
            ])
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }
}
