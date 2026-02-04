// Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyBK0HXAqqU0ac0yWiA4Jyk_hqeLgIQqmCo",
    authDomain: "notifications-25aa2.firebaseapp.com",
    projectId: "notifications-25aa2",
    storageBucket: "notifications-25aa2.firebasestorage.app",
    messagingSenderId: "420450697295",
    appId: "1:420450697295:web:ad493128b495ccd5386a3a"
};

console.log('🔥 Firebase initialization starting...');

// Initialize Firebase
if (typeof firebase !== 'undefined') {
    console.log('✅ Firebase SDK loaded successfully');

    try {
        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();
        console.log('✅ Firebase initialized successfully');

        // Request notification permission
        function requestNotificationPermission() {
            console.log('📢 Requesting notification permission...');

            Notification.requestPermission().then((permission) => {
                console.log('📢 Permission result:', permission);

                if (permission === 'granted') {
                    console.log('✅ Notification permission granted.');

                    // Get registration token
                    messaging.getToken({
                        vapidKey: 'BAkM5PQnIs5ZGwYBicWumbJZWbqevOc51lxXxpq5_R_xszoCt6lKqoQY8kFRuDnEoMosNMspy_1lGuXzVUn7KyI'
                    })
                    .then((currentToken) => {
                        if (currentToken) {
                            console.log('🔑 FCM Token:', currentToken);

                            // Send token to server
                            saveFCMToken(currentToken);
                        } else {
                            console.warn('⚠️ No registration token available.');
                        }
                    })
                    .catch((err) => {
                        console.error('❌ Error retrieving token:', err);
                    });
                } else {
                    console.warn('⚠️ Unable to get permission to notify.');
                }
            });
        }

        // Save FCM token to server
        function saveFCMToken(token) {
            console.log('💾 Saving FCM token to server...');

            // Check if we're in Dashboard (session-based) or API (token-based)
            const authToken = localStorage.getItem('auth_token');
            const isDashboard = window.location.pathname.includes('/dashboard');

            let url, headers;

            if (isDashboard) {
                // Dashboard uses session authentication
                console.log('📋 Using dashboard route (session-based)');
                url = '/dashboard/update-fcm-token';
                headers = {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                };
            } else {
                // API uses token authentication
                if (!authToken) {
                    console.warn('⚠️ No auth token found in localStorage. Please login first.');
                    return;
                }
                console.log('🔐 Using API route (token-based)');
                url = '/api/update-fcm-token';
                headers = {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + authToken,
                    'Accept': 'application/json'
                };
            }

            fetch(url, {
                method: 'POST',
                headers: headers,
                body: JSON.stringify({
                    fcm_token: token
                })
            })
            .then(response => {
                console.log('📡 Server response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('✅ FCM Token saved successfully:', data);
            })
            .catch(error => {
                console.error('❌ Error saving FCM token:', error);
            });
        }

        // Handle foreground messages (when app is open)
        messaging.onMessage((payload) => {
            console.log('🔔 Message received in foreground:', payload);

            const notificationTitle = payload.notification?.title || 'New Notification';
            const notificationBody = payload.notification?.body || '';

            console.log('📬 Notification:', notificationTitle, '-', notificationBody);

            const notificationOptions = {
                body: notificationBody,
                icon: payload.notification?.icon || '/firebase-logo.png',
                badge: '/firebase-logo.png',
                data: payload.data,
                requireInteraction: true
            };

            // Show notification
            if (Notification.permission === 'granted') {
                console.log('📢 Showing notification...');
                const notification = new Notification(notificationTitle, notificationOptions);

                notification.onclick = function(event) {
                    console.log('🖱️ Notification clicked');
                    event.preventDefault();
                    window.focus();
                    notification.close();
                };
            } else {
                console.warn('⚠️ Notification permission not granted');
            }
        });

        // Auto-request permission on page load
        if ('Notification' in window) {
            console.log('📢 Current notification permission:', Notification.permission);

            if (Notification.permission === 'default') {
                console.log('⏰ Will request permission in 2 seconds...');
                setTimeout(requestNotificationPermission, 2000);
            } else if (Notification.permission === 'granted') {
                console.log('✅ Notification already granted, getting token...');
                // Get token if already granted
                messaging.getToken({
                    vapidKey: 'BAkM5PQnIs5ZGwYBicWumbJZWbqevOc51lxXxpq5_R_xszoCt6lKqoQY8kFRuDnEoMosNMspy_1lGuXzVUn7KyI'
                }).then((token) => {
                    if (token) {
                        console.log('🔑 FCM Token:', token);
                        saveFCMToken(token);
                    }
                });
            }
        } else {
            console.error('❌ Notification API not available in this browser');
        }

        // Manual request button (optional)
        window.enableNotifications = requestNotificationPermission;
        console.log('✅ Firebase setup complete!');

    } catch (error) {
        console.error('❌ Firebase initialization error:', error);
    }
} else {
    console.error('❌ Firebase SDK not loaded! Make sure you included the Firebase scripts.');
}
