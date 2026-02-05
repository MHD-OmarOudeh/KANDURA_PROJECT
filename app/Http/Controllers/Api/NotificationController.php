<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Update user's FCM token (Admin & Super Admin only)
     * POST /api/update-fcm-token
     */
    public function updateFCMToken(Request $request)
    {
        try {
            $user = $request->user();

            // Only allow admins and super admins to update FCM token
            if (!$user->hasAnyRole(['admin', 'super_admin'])) {
                return $this->error(
                    'Unauthorized',
                    'FCM notifications are only available for administrators',
                    403
                );
            }

            $request->validate([
                'fcm_token' => 'required|string',
            ]);

            $user->fcm_token = $request->fcm_token;
            $user->save();

            return $this->success(null, 'FCM token updated successfully');

        } catch (\Exception $e) {
            return $this->error('Failed to update FCM token', $e->getMessage(), 500);
        }
    }

    /**
     * Get user notifications
     * GET /api/notifications
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 15);

            $notifications = $request->user()
                ->notifications()
                ->paginate($perPage);

            return $this->success([
                'notifications' => $notifications->items(),
                'unread_count' => $request->user()->unreadNotifications->count(),
                'pagination' => [
                    'current_page' => $notifications->currentPage(),
                    'total' => $notifications->total(),
                    'per_page' => $notifications->perPage(),
                    'last_page' => $notifications->lastPage(),
                ]
            ], 'Notifications retrieved successfully');

        } catch (\Exception $e) {
            return $this->error('Failed to retrieve notifications', $e->getMessage(), 500);
        }
    }

    /**
     * Get unread notifications
     * GET /api/notifications/unread
     */
    public function unread(Request $request)
    {
        try {
            $notifications = $request->user()
                ->unreadNotifications()
                ->take(50)
                ->get();

            return $this->success([
                'notifications' => $notifications,
                'count' => $notifications->count(),
            ], 'Unread notifications retrieved successfully');

        } catch (\Exception $e) {
            return $this->error('Failed to retrieve notifications', $e->getMessage(), 500);
        }
    }

    /**
     * Mark notification as read
     * PUT /api/notifications/{id}/read
     */
    public function markAsRead(Request $request, string $id)
    {
        try {
            $notification = $request->user()
                ->notifications()
                ->where('id', $id)
                ->first();

            if (!$notification) {
                return $this->error('Notification not found', null, 404);
            }

            $notification->markAsRead();

            return $this->success(null, 'Notification marked as read');

        } catch (\Exception $e) {
            return $this->error('Failed to mark notification as read', $e->getMessage(), 500);
        }
    }

    /**
     * Mark all notifications as read
     * PUT /api/notifications/read-all
     */
    public function markAllAsRead(Request $request)
    {
        try {
            $request->user()->unreadNotifications->markAsRead();

            return $this->success(null, 'All notifications marked as read');

        } catch (\Exception $e) {
            return $this->error('Failed to mark notifications as read', $e->getMessage(), 500);
        }
    }

    /**
     * Delete notification
     * DELETE /api/notifications/{id}
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $notification = $request->user()
                ->notifications()
                ->where('id', $id)
                ->first();

            if (!$notification) {
                return $this->error('Notification not found', null, 404);
            }

            $notification->delete();

            return $this->success(null, 'Notification deleted successfully');

        } catch (\Exception $e) {
            return $this->error('Failed to delete notification', $e->getMessage(), 500);
        }
    }
}
