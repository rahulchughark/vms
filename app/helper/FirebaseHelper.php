<?php

use Illuminate\Support\Facades\Http;
use Google\Client;

// if (!function_exists('sendFirebase')) {

//     function sendFirebase(string $token, string $title, string $message): bool
//     {
//         $serverKey = config('services.firebase.server_key');

//         if (empty($serverKey)) {
//             \Log::warning('Firebase server key is missing.');

//             return false;
//         }

//         $response = Http::withHeaders([
//             'Authorization' => 'key=' . $serverKey,
//             'Content-Type'  => 'application/json',
//         ])->post('https://fcm.googleapis.com/fcm/send', [
//             'to' => $token,
//             'notification' => [
//                 'title' => $title,
//                 'body'  => $message,
//             ],
//             'data' => [
//                 'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
//             ],
//         ]);

//         // Optional: log response for debugging
//         \Log::info('FCM Response: ' . $response->body());

//         return $response->successful();
//     }
// }


if (!function_exists('sendFirebase')) {

    function sendFirebase(string $deviceToken, string $title, string $message): bool
    {
        try {
            $firebaseConfigPath = storage_path('app/firebase/firebase.json');
            $projectId = config('services.firebase.project_id');

            if (empty($projectId) && file_exists($firebaseConfigPath)) {
                $firebaseConfig = json_decode((string) file_get_contents($firebaseConfigPath), true);
                $projectId = $firebaseConfig['project_id'] ?? null;
            }

            if (empty($projectId)) {
                \Log::error('FCM Error: Firebase project_id missing in services config and firebase.json');
                return false;
            }

            // Step 1: Generate Access Token
            $client = new Client();
            $client->setAuthConfig($firebaseConfigPath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

            $token = $client->fetchAccessTokenWithAssertion();
            $accessToken = $token['access_token'];

            // Step 3: Send Notification
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type'  => 'application/json',
            ])->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", [
                "message" => [
                    "token" => $deviceToken,
                    "notification" => [
                        "title" => $title,
                        "body"  => $message,
                    ]
                ]
            ]);

            \Log::info('FCM V1 Response: ' . $response->body());

            if (! $response->successful()) {
                \Log::warning('FCM send failed', [
                    'project_id' => $projectId,
                    'token_prefix' => substr($deviceToken, 0, 16),
                    'http_status' => $response->status(),
                ]);
            }

            return $response->successful();

        } catch (\Exception $e) {
            \Log::error('FCM Error: ' . $e->getMessage());
            return false;
        }
    }
}