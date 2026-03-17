importScripts('https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging-compat.js');

firebase.initializeApp({
    apiKey: "AIzaSyA2tNdn3WcFmcH72YTBZDoAv6GDYgP3g0I",
    authDomain: "vms-ark-a1346.firebaseapp.com",
    projectId: "vms-ark-a1346",
    messagingSenderId: "923887602479",
    appId: "1:923887602479:web:686ab65496eb62891e2fda"
});

const messaging = firebase.messaging();

// Show notification when message is received in background
self.addEventListener('push', function(event) {
    // Fallback for some browsers
    if (event.data) {
        const payload = event.data.json();
        const notification = payload.notification || {};
        event.waitUntil(
            self.registration.showNotification(notification.title || 'Notification', {
                body: notification.body || '',
                icon: notification.icon || '/favicon.ico',
                data: payload.data || {}
            })
        );
    }
});

// For Firebase v9+ background message handler
messaging.onBackgroundMessage && messaging.onBackgroundMessage(function(payload) {
    const notification = payload.notification || {};
    self.registration.showNotification(notification.title || 'Notification', {
        body: notification.body || '',
        icon: notification.icon || '/favicon.ico',
        data: payload.data || {}
    });
});
