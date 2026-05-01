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

        $url = rtrim($this->baseUrl, '/') . '/mutations/create-transaction/create-transaction';

        try {
            $response = Http::withToken($this->token)
                ->acceptJson()
                ->post($url, $data);

            $body = $response->json();
            return ['error' => !$response->successful(), 'status' => $response->status(), 'body' => $body];
        } catch (\Exception $e) {
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }
}
