<!DOCTYPE html>
<html>
<head>
    <title>Firebase FCM Test</title>
</head>
<body>

<h2>FCM Token Generator</h2>
<button onclick="getToken()">Get FCM Token</button>

<pre id="token" style="white-space: pre-wrap;"></pre>

<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging-compat.js"></script>

<script>
const firebaseConfig = {
    apiKey: "{{ config('services.firebase.api_key') }}",
    authDomain: "{{ config('services.firebase.auth_domain') }}",
    projectId: "{{ config('services.firebase.project_id') }}",
    storageBucket: "{{ config('services.firebase.storage_bucket') }}",
    messagingSenderId: "{{ config('services.firebase.sender_id') }}",
    appId: "{{ config('services.firebase.app_id') }}"
};

firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

// Foreground notification handler
messaging.onMessage(function(payload) {
    const notification = payload.notification || {};
    // Show a browser notification popup
    if (Notification.permission === 'granted') {
        new Notification(notification.title || 'Notification', {
            body: notification.body || '',
            icon: notification.icon || '/favicon.ico',
            data: payload.data || {}
        });
    }
    // Also show in-page for demo
    document.getElementById('token').innerText =
        'Foreground notification received!\\n' +
        'Title: ' + (notification.title || '') + '\\n' +
        'Body: ' + (notification.body || '') + '\\n';
});

function getToken() {
    Notification.requestPermission().then(permission => {
        if (permission === 'granted') {
            messaging.getToken({
                vapidKey: "{{ config('services.firebase.vapid_key') }}"
            }).then((currentToken) => {
                if (currentToken) {
                    document.getElementById('token').innerText = currentToken;
                    console.log('FCM Token:', currentToken);
                } else {
                    alert('No token generated');
                }
            });
        } else {
            alert('Notification permission denied');
        }
    });
}
</script>

</body>
</html>
