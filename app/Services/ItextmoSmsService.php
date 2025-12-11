<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Throwable;

class ItextmoSmsService
{
    protected string $url = '';

    public function __construct()
    {
        $this->url = config('itextmo.url');
    }

    public function send(string|array $recipients, string $message): array
    {
        if (is_string($recipients)) {
            $recipients = [$recipients];
        }

        $payload = [
            'Email'      => config('itextmo.email'),
            'Password'   => config('itextmo.password'),
            'ApiCode'    => config('itextmo.apicode'),
            'Recipients' => json_encode($recipients),
            'Message'    => $message,
        ];

        try {
            $response = Http::withOptions([
                'verify' => false
            ])->asForm()->post($this->url, $payload);

            return [
                'success'  => $response->successful(),
                'status'   => $response->status(),
                'body'     => $response->body(),
                'json'     => $response->json(),
            ];
        } catch (Throwable $e) {
            return [
                'success' => false,
                'error'   => $e->getMessage(),
            ];
        }
    }

    public function sendWithCurl(string|array $recipients, string $message)
    {
        if (is_string($recipients)) {
            $recipients = [$recipients];
        }

        $itextmo = [
            'Email'      => config('itextmo.email'),
            'Password'   => config('itextmo.password'),
            'ApiCode'    => config('itextmo.apicode'),
            'Recipients' => json_encode($recipients),
            'Message'    => $message,
        ];

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($itextmo));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $response = curl_exec($ch);

            return $response;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }


    public function debugCheck(string|array $recipients, string $message)
    {
        $endpoint = 'https://api.itexmo.com/api/broadcast';
        $email    = config('itextmo.email');
        $password = config('itextmo.password');

        try {
            $ch = curl_init($endpoint);

            $headers = [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($email . ':' . $password),
            ];

            $payload = [
                'Recipients' => ['09174128392'],
                'Message'    => 'Test Message',
                'ApiCode'    => config('itextmo.apicode'),
            ];

            curl_setopt_array($ch, [
                CURLOPT_HTTPHEADER     => $headers,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => json_encode($payload),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ]);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                $error = curl_error($ch);
                logger()->error('cURL error: ' . $error);
                return $error;
            }

            logger($response);

            return $response;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }

    }
}
