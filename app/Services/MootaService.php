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

    /**
     * Get available accounts from Moota
     */
    public function getAccounts()
    {
        if (!$this->token) {
            return ['error' => true, 'message' => 'MOOTA_API_TOKEN not configured'];
        }

        $endpoints = [
            '/accounts',
            '/bank-accounts',
            '/mutations/accounts'
        ];

        $attempts = [];

        foreach ($endpoints as $ep) {
            $url = rtrim($this->baseUrl, '/') . $ep;
            try {
                $response = Http::withToken($this->token)
                    ->acceptJson()
                    ->get($url);

                $body = $response->json();
                $attempts[] = ['url' => $url, 'status' => $response->status(), 'body' => $body];

                if ($response->successful()) {
                    // Extract accounts data from common response paths
                    $rawAccounts = [];
                    if (isset($body['data']) && is_array($body['data'])) {
                        $rawAccounts = $body['data'];
                    } elseif (isset($body['accounts']) && is_array($body['accounts'])) {
                        $rawAccounts = $body['accounts'];
                    } elseif (is_array($body) && count($body) > 0) {
                        $rawAccounts = $body;
                    }

                    // Normalize Moota account fields to standard format
                    $normalizedAccounts = array_map(function($acc) {
                        return [
                            'id' => $acc['bank_id'] ?? $acc['id'] ?? $acc['account_id'] ?? $acc['code'] ?? null,
                            'name' => $acc['atas_nama'] ?? $acc['name'] ?? $acc['bank_name'] ?? $acc['account_name'] ?? null,
                            'account_number' => $acc['account_number'] ?? null,
                            'bank_type' => $acc['bank_type'] ?? null,
                            'balance' => $acc['balance'] ?? null,
                            'icon' => $acc['icon'] ?? null,
                            // Keep original for reference
                            '_raw' => $acc
                        ];
                    }, $rawAccounts);

                    if (count($normalizedAccounts) > 0) {
                        return ['error' => false, 'accounts' => $normalizedAccounts, 'attempts' => $attempts];
                    } else {
                        return ['error' => false, 'accounts' => [], 'attempts' => $attempts];
                    }
                }

                // if 404 continue to next endpoint
                if ($response->status() === 404) {
                    continue;
                }

                // other non-success -> return with attempts
                return ['error' => true, 'status' => $response->status(), 'body' => $body, 'attempts' => $attempts];
            } catch (\Exception $e) {
                $attempts[] = ['url' => $url, 'exception' => $e->getMessage()];
                continue;
            }
        }

        return ['error' => true, 'message' => 'All endpoints failed', 'attempts' => $attempts];
    }
}
