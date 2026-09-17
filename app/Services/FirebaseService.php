<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Google\Client;

class FirebaseService
{
    /**
     * Get cached Google FCM OAuth 2.0 access token (valid for 55 mins)
     */
    protected function getAccessToken(): ?string
    {
        return Cache::remember('fcm_google_access_token_v1', 3300, function () {
            $firebaseConfigPath = storage_path('app/firebase/firebase_credentials.json');
            if (!file_exists($firebaseConfigPath)) {
                $firebaseConfigPath = storage_path('app/firebase/firebase.json');
            }

            if (!file_exists($firebaseConfigPath)) {
                \Log::error('FCM Error: Firebase service account credentials file not found.');
                return null;
            }

            $client = new Client();
            $client->setAuthConfig($firebaseConfigPath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

            $token = $client->fetchAccessTokenWithAssertion();
            return $token['access_token'] ?? null;
        });
    }

    /**
     * Get Firebase Project ID
     */
    protected function getProjectId(): ?string
    {
        $firebaseConfigPath = storage_path('app/firebase/firebase_credentials.json');
        if (!file_exists($firebaseConfigPath)) {
            $firebaseConfigPath = storage_path('app/firebase/firebase.json');
        }

        if (file_exists($firebaseConfigPath)) {
            $config = json_decode((string) file_get_contents($firebaseConfigPath), true);
            return $config['project_id'] ?? null;
        }

        return config('services.firebase.project_id');
    }

    /**
     * Send FCM Push Notification
     */
    public function send($token, $title, $body, array $extraData = [])
    {
        try {
            $accessToken = $this->getAccessToken();
            $projectId = $this->getProjectId();

            if (empty($accessToken) || empty($projectId)) {
                \Log::error('FCM Error: Missing OAuth access token or Firebase project_id.');
                return false;
            }

            $messagePayload = [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                ]
            ];

            if (!empty($extraData)) {
                $messagePayload['data'] = array_map(fn($v) => (string) $v, $extraData);
            }

            $response = Http::timeout(3)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type'  => 'application/json',
                ])
                ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", [
                    'message' => $messagePayload
                ]);

            // If token expired, clear cache and retry once
            if ($response->status() === 401) {
                Cache::forget('fcm_google_access_token_v1');
                $accessToken = $this->getAccessToken();

                $response = Http::timeout(3)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $accessToken,
                        'Content-Type'  => 'application/json',
                    ])
                    ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", [
                        'message' => $messagePayload
                    ]);
            }

            return $response->successful();

        } catch (\Exception $e) {
            \Log::error('FCM Send Error: ' . $e->getMessage());
            return false;
        }
    }
}
