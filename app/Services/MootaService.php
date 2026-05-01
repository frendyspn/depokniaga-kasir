<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MootaService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = env('MOOTA_API_URL', 'https://api.moota.co/api/v2');
        $this->token = env('MOOTA_API_TOKEN', null);
    }

    /**
     * Create transaction on Moota
     * $data should follow Moota Create Transaction spec
     */
    public function createTransaction(array $data)
    {
        if (!$this->token) {
            return ['error' => true, 'message' => 'MOOTA_API_TOKEN not configured'];
        }

        // Try primary endpoint and a few fallbacks if provider returns 404
        $endpoints = [
            '/create-transaction'
        ];

        $attempts = [];

        foreach ($endpoints as $ep) {
            $url = rtrim($this->baseUrl, '/') . $ep;
            try {
                $response = Http::withToken($this->token)
                    ->acceptJson()
                    ->post($url, $data);

                $body = $response->json();
                $attempts[] = ['url' => $url, 'status' => $response->status(), 'body' => $body];

                if ($response->successful()) {
                    return ['error' => false, 'status' => $response->status(), 'body' => $body, 'attempts' => $attempts];
                }

                // if 404 continue to next endpoint
                if ($response->status() === 404) {
                    continue;
                }

                // other non-success -> return with attempts
                return ['error' => true, 'status' => $response->status(), 'body' => $body, 'attempts' => $attempts];
            } catch (\Exception $e) {
                $attempts[] = ['url' => $url, 'exception' => $e->getMessage()];
                // continue to try next endpoint
                continue;
            }
        }

        return ['error' => true, 'message' => 'All endpoints failed', 'attempts' => $attempts];
    }
}
