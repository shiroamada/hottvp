<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class MetVBoxService
{
    protected $client;
    protected $baseUrl;
    protected $partnerToken;

    public function __construct()
    {
        $this->baseUrl = config('services.metvbox.base_url');
        $this->partnerToken = config('services.metvbox.partner_token');

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 30,
            'verify' => true,
        ]);
    }

    /**
     * Generate activation codes
     *
     * @param int $validDays Number of days the code is valid
     * @param string $deviceType Device type (all, mobile, tv, etc.)
     * @param int $quantity Number of codes to generate
     * @return array|null
     */
    public function generateCode(int $validDays = 1, string $deviceType = 'all', int $quantity = 1): ?array
    {
        $endpoint = '/api/partner/codes';
        $body = [
            'valid_days' => $validDays,
            'device_type' => $deviceType,
            'quantity' => $quantity,
        ];

        \Log::info("=== MetVBox Generate Code Request ===");
        \Log::info("Endpoint: " . $this->baseUrl . $endpoint);
        \Log::info("Request Body: " . json_encode($body, JSON_PRETTY_PRINT));

        try {
            $response = $this->client->request('POST', $endpoint, [
                'json' => $body,
                'headers' => [
                    'X-Partner-Token' => $this->partnerToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            \Log::info("=== MetVBox Generate Code Response ===");
            \Log::info("Status Code: " . $response->getStatusCode());
            \Log::info("Response: " . json_encode($data, JSON_PRETTY_PRINT));

            // Normalize response to have 'data' key with codes array
            if (isset($data['data']['codes']) && is_array($data['data']['codes'])) {
                // Response has nested structure: { success, data: { codes: [...], points_deducted, balance_after } }
                return [
                    'success' => $data['success'] ?? true,
                    'data' => $data['data']['codes'],
                    'metadata' => [
                        'points_deducted' => $data['data']['points_deducted'] ?? null,
                        'balance_after' => $data['data']['balance_after'] ?? null,
                    ]
                ];
            }

            // Return as-is if structure is already normalized
            return $data;
        } catch (GuzzleException $e) {
            \Log::error("=== MetVBox Generate Code Error ===");
            \Log::error("Error Message: " . $e->getMessage());
            \Log::error("Error Code: " . $e->getCode());

            return null;
        }
    }

    /**
     * List activation codes with filters
     *
     * @param string|null $status Filter by status (inactive, active, expired, revoked)
     * @param int $page Page number
     * @param int $limit Results per page
     * @return array|null Normalized response with codes array and pagination
     */
    public function listCodes(?string $status = null, int $page = 1, int $limit = 20): ?array
    {
        $endpoint = '/api/partner/codes';
        $queryParams = [
            'page' => $page,
            'limit' => $limit,
        ];

        if ($status) {
            $queryParams['status'] = $status;
        }

        $queryString = http_build_query($queryParams);
        $fullEndpoint = $endpoint . '?' . $queryString;

        \Log::info("=== MetVBox List Codes Request ===");
        \Log::info("Endpoint: " . $this->baseUrl . $fullEndpoint);
        \Log::info("Query Params: " . json_encode($queryParams, JSON_PRETTY_PRINT));

        try {
            $response = $this->client->request('GET', $fullEndpoint, [
                'headers' => [
                    'X-Partner-Token' => $this->partnerToken,
                    'Accept' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            \Log::info("=== MetVBox List Codes Response ===");
            \Log::info("Status Code: " . $response->getStatusCode());
            \Log::info("Response: " . json_encode($data, JSON_PRETTY_PRINT));

            // Normalize response
            if ($data['success'] && isset($data['data']['codes'])) {
                return [
                    'success' => true,
                    'codes' => $data['data']['codes'] ?? [],
                    'pagination' => $data['data']['pagination'] ?? [
                        'page' => $page,
                        'limit' => $limit,
                        'total' => 0,
                        'total_pages' => 0,
                    ]
                ];
            }

            return $data;
        } catch (GuzzleException $e) {
            \Log::error("=== MetVBox List Codes Error ===");
            \Log::error("Error Message: " . $e->getMessage());
            \Log::error("Error Code: " . $e->getCode());

            return null;
        }
    }

    /**
     * Check the status of a specific activation code
     *
     * @param string $code The activation code to check
     * @return array|null Response: { success, data: { code, status, expires_at, activated_at, remaining_days, ... } }
     */
    public function checkCodeStatus(string $code): ?array
    {
        $endpoint = '/api/partner/codes/' . $code;

        \Log::info("=== MetVBox Check Code Status Request ===");
        \Log::info("Endpoint: " . $this->baseUrl . $endpoint);
        \Log::info("Code: " . $code);

        try {
            $response = $this->client->request('GET', $endpoint, [
                'headers' => [
                    'X-Partner-Token' => $this->partnerToken,
                    'Accept' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            \Log::info("=== MetVBox Check Code Status Response ===");
            \Log::info("Status Code: " . $response->getStatusCode());
            \Log::info("Response: " . json_encode($data, JSON_PRETTY_PRINT));

            // Normalize response format
            if ($data['success'] && isset($data['data'])) {
                return [
                    'success' => true,
                    'data' => [
                        'code' => $data['data']['code'] ?? null,
                        'status' => $data['data']['status'] ?? 'unknown', // active, used, expired, revoked
                        'expires_at' => $data['data']['expires_at'] ?? null,
                        'activated_at' => $data['data']['activated_at'] ?? null,
                        'created_at' => $data['data']['created_at'] ?? null,
                        'remaining_days' => $data['data']['remaining_days'] ?? null,
                        'device_type' => $data['data']['device_type'] ?? null,
                        'valid_days' => $data['data']['valid_days'] ?? null,
                        'points_cost' => $data['data']['points_cost'] ?? null,
                    ]
                ];
            }

            return $data;
        } catch (GuzzleException $e) {
            \Log::error("=== MetVBox Check Code Status Error ===");
            \Log::error("Error Message: " . $e->getMessage());
            \Log::error("Error Code: " . $e->getCode());

            return null;
        }
    }

    /**
     * Revoke an activation code
     *
     * @param string $code The activation code to revoke
     * @return array|null
     */
    public function revokeCode(string $code): ?array
    {
        $endpoint = '/api/partner/codes/' . $code . '/revoke';

        \Log::info("=== MetVBox Revoke Code Request ===");
        \Log::info("Endpoint: " . $this->baseUrl . $endpoint);
        \Log::info("Code: " . $code);

        try {
            $response = $this->client->request('GET', $endpoint, [
                'headers' => [
                    'X-Partner-Token' => $this->partnerToken,
                    'Accept' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            \Log::info("=== MetVBox Revoke Code Response ===");
            \Log::info("Status Code: " . $response->getStatusCode());
            \Log::info("Response: " . json_encode($data, JSON_PRETTY_PRINT));

            return $data;
        } catch (GuzzleException $e) {
            \Log::error("=== MetVBox Revoke Code Error ===");
            \Log::error("Error Message: " . $e->getMessage());
            \Log::error("Error Code: " . $e->getCode());

            return null;
        }
    }

    /**
     * Test MetVBox API connectivity
     *
     * @return array
     */
    public function testConnection(): array
    {
        \Log::info("=== Testing MetVBox API Connection ===");
        \Log::info("Base URL: " . $this->baseUrl);
        \Log::info("Has Token: " . (!empty($this->partnerToken) ? 'Yes' : 'No'));

        try {
            // Try to list codes with limit 1 to test connection
            $response = $this->client->request('GET', '/api/partner/codes?limit=1', [
                'headers' => [
                    'X-Partner-Token' => $this->partnerToken,
                    'Accept' => 'application/json',
                ],
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            \Log::info("Connection test successful - Status: " . $statusCode);

            return [
                'success' => true,
                'status_code' => $statusCode,
                'message' => 'MetVBox API connection successful',
            ];
        } catch (GuzzleException $e) {
            \Log::error("Connection test failed: " . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'message' => 'MetVBox API connection failed',
            ];
        }
    }
}
