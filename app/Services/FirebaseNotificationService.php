<?php

namespace App\Services;

use App\Models\User;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;

class FirebaseNotificationService
{
    protected $messaging;

    public function __construct()
    {
        try {
            $this->messaging = app('firebase.messaging');
        } catch (\Exception $e) {
            Log::error('Firebase initialization failed: ' . $e->getMessage());
            $this->messaging = null;
        }
    }

    /**
     * Send notification to a single user
     */
    public function sendToUser(User $user, string $title, string $body, array $data = [])
    {
        if (!$user->fcm_token) {
            Log::info("User {$user->id} has no FCM token");
            return false;
        }

        return $this->sendToToken($user->fcm_token, $title, $body, $data);
    }

    /**
     * Send notification to multiple users
     */
    public function sendToUsers($users, string $title, string $body, array $data = [])
    {
        $results = [];

        foreach ($users as $user) {
            if ($user->fcm_token) {
                $results[] = $this->sendToUser($user, $title, $body, $data);
            }
        }

        return $results;
    }

    /**
     * Send notification to a specific token
     */
    public function sendToToken(string $token, string $title, string $body, array $data = [])
    {
        if (!$this->messaging) {
            Log::error('Firebase messaging is not initialized');
            return false;
        }

        try {
            $notification = Notification::create($title, $body);

            $message = CloudMessage::withTarget('token', $token)
                ->withNotification($notification)
                ->withData($data);

            $this->messaging->send($message);

            Log::info("Notification sent successfully to token: {$token}");
            return true;

        } catch (\Exception $e) {
            Log::error('Failed to send notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send notification to admins
     */
    public function sendToAdmins(string $title, string $body, array $data = [])
    {
        $admins = User::role(['admin', 'super_admin'])->whereNotNull('fcm_token')->get();

        return $this->sendToUsers($admins, $title, $body, $data);
    }

    /**
     * Send notification to super admins only
     */
    public function sendToSuperAdmins(string $title, string $body, array $data = [])
    {
        $superAdmins = User::role('super_admin')->whereNotNull('fcm_token')->get();

        return $this->sendToUsers($superAdmins, $title, $body, $data);
    }
}
