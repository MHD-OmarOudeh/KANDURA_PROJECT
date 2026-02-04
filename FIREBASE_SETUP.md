# Firebase Push Notifications Setup Guide

## Requirements

This system implements push notifications using Firebase Cloud Messaging (FCM) for the following events:

1. **Design Created** - Notify admins when a new design is created (DB + Push)
2. **Order Status Updated** - Notify user when order status changes (DB only)
3. **Order Created** - Notify admin and design owner when new order is placed (DB + Push)

---

## Step 1: Firebase Console Setup

### 1.1 Create Firebase Project
1. Visit [Firebase Console](https://console.firebase.google.com/)
2. Create a new project or select existing one
3. Add a web app to your project

### 1.2 Get Service Account Key (for Backend)
1. Go to **Project Settings** (gear icon)
2. Navigate to **Service Accounts** tab
3. Click **Generate New Private Key**
4. Download the JSON file and save it as:
   ```
   storage/app/firebase/firebase_credentials.json
   ```

### 1.3 Get Web Push Certificate (VAPID Key)
1. In **Project Settings**, go to **Cloud Messaging** tab
2. Under **Web configuration** section
3. Click **Generate key pair**
4. Copy the **Key pair** (starts with `B...`)

### 1.4 Get Firebase Config
In **Project Settings** → **General** tab, scroll to "Your apps" section and copy:
- API Key
- Auth Domain
- Project ID
- Storage Bucket
- Messaging Sender ID
- App ID

---

## Step 2: Laravel Backend Configuration

### 2.1 Install Firebase Package
```bash
composer require kreait/laravel-firebase
```

### 2.2 Configure Environment Variables
Add to your `.env` file:
```env
FIREBASE_CREDENTIALS=storage/app/firebase/firebase_credentials.json
```

### 2.3 Run Database Migration
```bash
php artisan migrate
```
This adds `fcm_token` column to users table.

### 2.4 Configure Queue Worker (Important!)
Since listeners implement `ShouldQueue`, you must run a queue worker:

```bash
php artisan queue:work
```

For production, use Supervisor to keep the queue worker running.

---

## Step 3: Frontend Configuration

### 3.1 Update Service Worker
Edit `public/firebase-messaging-sw.js` and replace:
```javascript
firebase.initializeApp({
    apiKey: "YOUR_API_KEY",
    authDomain: "YOUR_PROJECT_ID.firebaseapp.com",
    projectId: "YOUR_PROJECT_ID",
    storageBucket: "YOUR_PROJECT_ID.appspot.com",
    messagingSenderId: "YOUR_SENDER_ID",
    appId: "YOUR_APP_ID"
});
```

### 3.2 Update Firebase Init Script
Edit `public/js/firebase-init.js` and replace:
1. Firebase config (same as above)
2. VAPID key:
```javascript
messaging.getToken({ 
    vapidKey: 'YOUR_VAPID_KEY_HERE' 
})
```

### 3.3 Include Scripts in Your HTML
Add to your main layout (`app.blade.php` or similar):

```html
<!-- Firebase SDK -->
<script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js"></script>

<!-- Firebase Init -->
<script src="{{ asset('js/firebase-init.js') }}"></script>
```

---

## Step 4: API Endpoints

### Update FCM Token
```http
POST /api/update-fcm-token
Authorization: Bearer {token}
Content-Type: application/json

{
  "fcm_token": "firebase_device_token_here"
}
```

### Get Notifications
```http
GET /api/notifications
Authorization: Bearer {token}
```

### Mark as Read
```http
PUT /api/notifications/{id}/read
Authorization: Bearer {token}
```

### Test Notification (Admin Only)
```http
POST /api/admin/test-notification
Authorization: Bearer {admin_token}
```

---

## Step 5: Testing

### 5.1 Test Push Notifications
1. Login as a user
2. Allow notifications when prompted
3. FCM token will be automatically saved
4. Create a design (as user) → Admin receives notification
5. Create an order → User and admin receive notification
6. Change order status → User receives database notification

### 5.2 Admin Testing
```http
POST /api/admin/send-notification
{
  "user_id": 1,
  "title": "Test Notification",
  "body": "This is a test message",
  "data": {
    "type": "test"
  }
}
```

---

## How Events Work

### Order Created
- **Triggered by**: `OrderObserver::created()`
- **Event**: `OrderCreated`
- **Listener**: `SendOrderCreatedNotification`
- **Recipients**:
  - User who placed order (DB + Push)
  - Design owner if different from order user (DB + Push)
  - All admins (Push only)

### Order Status Changed
- **Triggered by**: `OrderObserver::updated()` when status changes
- **Event**: `OrderStatusChanged`
- **Listener**: `SendOrderStatusChangedNotification`
- **Recipients**: User who owns the order (DB only, no push)

### Design Created
- **Triggered by**: `DesignObserver::created()`
- **Event**: `DesignCreated`
- **Listener**: `SendDesignCreatedNotification`
- **Recipients**: All admins (DB + Push)

---

## Architecture

```
Controller/Observer
    ↓
Event (OrderCreated, DesignCreated, OrderStatusChanged)
    ↓
Listener (ShouldQueue)
    ↓
├─ Store in Database (via Laravel Notifications)
└─ Send Push (via FirebaseNotificationService)
```

---

## Important Notes

1. **Queue Workers**: Notifications are queued, so you MUST run `php artisan queue:work`
2. **FCM Token**: Users must grant notification permission to receive push notifications
3. **Database Storage**: All notifications are stored in `notifications` table
4. **Browser Support**: Push notifications work in modern browsers (Chrome, Firefox, Edge)
5. **HTTPS Required**: Push notifications require HTTPS in production

---

## Troubleshooting

### Notifications not sending?
1. Check queue worker is running: `php artisan queue:work`
2. Check Firebase credentials file exists
3. Check user has `fcm_token` in database
4. Check Laravel logs: `storage/logs/laravel.log`

### User not receiving push?
1. Check browser notification permission
2. Check FCM token is saved in database
3. Check Service Worker is registered
4. Check Firebase console for any errors

### Testing locally?
1. Use ngrok or similar to get HTTPS URL
2. Service Worker requires HTTPS (or localhost)

---

## Files Structure

```
app/
├── Events/
│   ├── DesignCreated.php
│   ├── OrderCreated.php
│   ├── OrderCompleted.php (for invoice generation)
│   └── OrderStatusChanged.php
├── Listeners/
│   ├── SendDesignCreatedNotification.php
│   ├── SendOrderCreatedNotification.php
│   └── SendOrderStatusChangedNotification.php
├── Notifications/
│   ├── DesignNotification.php
│   └── OrderNotification.php
├── Observers/
│   ├── DesignObserver.php
│   └── OrderObserver.php
└── Services/
    └── FirebaseNotificationService.php

public/
├── firebase-messaging-sw.js (Service Worker)
└── js/
    └── firebase-init.js (Frontend initialization)

database/migrations/
└── 2026_02_01_194942_add_fcm_token_to_users_table.php

storage/app/firebase/
└── firebase_credentials.json (Your Firebase service account key)
```

---

## Support

For issues or questions, check:
- Firebase Console logs
- Laravel logs: `storage/logs/laravel.log`
- Browser console for JavaScript errors
