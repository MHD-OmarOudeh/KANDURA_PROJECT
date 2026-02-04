<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\FirebaseNotificationService;
use Illuminate\Http\Request;

class TestNotificationController extends Controller
{
    public function __construct(
        protected FirebaseNotificationService $notificationService
    ) {
    }

    /**
     * Send test notification to current user
     */
    public function sendTestNotification(Request $request)
    {
        $user = $request->user();

        if (!$user->fcm_token) {
            return response()->json([
                'success' => false,
                'message' => 'User does not have FCM token',
            ], 400);
        }

        $result = $this->notificationService->sendToUser(
            $user,
            'Test Notification',
            'This is a test notification from Kandura System.',
            [
                'type' => 'test',
                'timestamp' => now()->toDateTimeString(),
            ]
        );

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Test notification sent successfully' : 'Failed to send notification',
        ]);
    }

    /**
     * Send custom notification
     */
    public function sendCustomNotification(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:500',
            'data' => 'nullable|array',
        ]);

        $user = \App\Models\User::find($request->user_id);

        if (!$user->fcm_token) {
            return response()->json([
                'success' => false,
                'message' => 'User does not have FCM token',
            ], 400);
        }

        $result = $this->notificationService->sendToUser(
            $user,
            $request->title,
            $request->body,
            $request->data ?? []
        );

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Notification sent successfully' : 'Failed to send notification',
        ]);
    }

    /**
     * Broadcast notification to all users
     */
    public function broadcastNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:500',
            'data' => 'nullable|array',
        ]);

        $users = \App\Models\User::whereNotNull('fcm_token')->get();

        $results = $this->notificationService->sendToUsers(
            $users,
            $request->title,
            $request->body,
            $request->data ?? []
        );

        $successCount = count(array_filter($results));

        return response()->json([
            'success' => true,
            'message' => "Notification sent to {$successCount} users",
            'total_users' => $users->count(),
            'success_count' => $successCount,
        ]);
    }
}
