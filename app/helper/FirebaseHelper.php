<?php

use Illuminate\Support\Facades\Http;

if (!function_exists('sendFirebase')) {

    function sendFirebase(string $token, string $title, string $message): bool
    {
        $serverKey = "BB6Zd1zSSuGVNaZo9pfyTX-BtREdVZZadD8OWYpGr3X9rBOb71YAa7l79m8lioPpFz_bJqw0DUoIWsNmTVswpDY";

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type'  => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'to' => $token,
            'notification' => [
                'title' => $title,
                'body'  => $message,
            ],
            'data' => [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ],
        ]);

        // Optional: log response for debugging
        \Log::info('FCM Response: ' . $response->body());

        return $response->successful();
    }
}
