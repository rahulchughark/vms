<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FirebaseNotificationService
{
    public static function send($token, $title, $body, $data = [])
    {
        $response = Http::withToken(config('firebase.server_key'))
            ->post(
                'https://fcm.googleapis.com/fcm/send',
                [
                    'to' => $token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => $data
                ]
            );

        return $response->json();
    }
}
