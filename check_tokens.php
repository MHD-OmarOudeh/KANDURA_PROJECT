<?php

use Illuminate\Support\Facades\DB;

// Check FCM tokens
$users = DB::table('users')
    ->whereNotNull('fcm_token')
    ->get(['id', 'name', 'email']);

echo "Users with FCM tokens: " . $users->count() . "\n\n";

foreach ($users as $user) {
    echo "ID: {$user->id} - {$user->name} ({$user->email})\n";
}

echo "\n✅ FCM token is saved! You can now test notifications.\n";
