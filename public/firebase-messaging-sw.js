importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js');

// Replace with your Firebase config
firebase.initializeApp({
  apiKey: "AIzaSyBK0HXAqqU0ac0yWiA4Jyk_hqeLgIQqmCo",

  authDomain: "notifications-25aa2.firebaseapp.com",

  projectId: "notifications-25aa2",

  storageBucket: "notifications-25aa2.firebasestorage.app",

  messagingSenderId: "420450697295",

  appId: "1:420450697295:web:ad493128b495ccd5386a3a"

});

const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);

    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: payload.notification.icon || '/firebase-logo.png',
        badge: '/firebase-logo.png',
        data: payload.data
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});

// Handle notification click
self.addEventListener('notificationclick', function(event) {
    console.log('[Service Worker] Notification click received.', event);
    event.notification.close();

    const urlToOpen = event.notification.data?.click_action || '/';

    event.waitUntil(
        clients.openWindow(urlToOpen)
    );
});
