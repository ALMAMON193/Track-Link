<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class MMGPaymentService
{
    protected string $baseUrl;
    protected string $authUrl;

    protected string $merchantMid;
    protected string $merchantPassword;
    protected string $apiKey;
    protected string $mKey;
    protected string $mSecret;
    protected string $xApiKey;

    public function __construct()
    {
        $this->baseUrl = 'https://uat-api.mmg.gy';
        $this->authUrl = 'https://gtt-uat-oauth2-service-api.qpass.com:9143/oauth2-endpoint/oauth/resourcetoken';

        $this->merchantMid      = config('services.mmg.merchant_mid');
        $this->merchantPassword = config('services.mmg.merchant_password');
        $this->apiKey           = config('services.mmg.resourcetoken_apikey');
        $this->mKey             = config('services.mmg.merchant_mkey');
        $this->mSecret          = config('services.mmg.merchant_msecret');
        $this->xApiKey          = config('services.mmg.x_api_key');

        $this->validateConfig();
    }

    private function validateConfig()
    {
        $configs = [
            'merchant_mid' => $this->merchantMid,
            'merchant_password' => $this->merchantPassword,
            'api_key' => $this->apiKey,
            'mkey' => $this->mKey,
            'msecret' => $this->mSecret,
            'x_api_key' => $this->xApiKey,
        ];

        foreach ($configs as $key => $value) {
            if (empty($value)) {
                Log::error("MMG Config Missing: {$key}");
                throw new \Exception("MMG configuration missing: {$key}");
            }
        }

        Log::info('MMG Config Validation', [
            'merchant_mid_length' => strlen($this->merchantMid),
            'password_length' => strlen($this->merchantPassword),
            'api_key_length' => strlen($this->apiKey),
            'mkey_length' => strlen($this->mKey),
            'msecret_length' => strlen($this->mSecret),
            'x_api_key_length' => strlen($this->xApiKey),
        ]);
    }

    public function getToken(): string
    {
        $requestData = [
            'grant_type' => 'password',
            'api_key'    => $this->apiKey,
            'username'   => $this->merchantMid,
            'password'   => $this->merchantPassword,
        ];

        Log::info('Getting MMG Token', [
            'url' => $this->authUrl,
            'request_data' => array_merge($requestData, ['password' => '***masked***'])
        ]);

        $response = Http::timeout(30)
            ->asForm()
            ->post($this->authUrl, $requestData);

        Log::info('MMG Token Response', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to get MMG token: ' . $response->body());
        }

        $responseData = $response->json();

        if (!isset($responseData['access_token'])) {
            throw new \Exception('Access token not found in response: ' . json_encode($responseData));
        }

        return $responseData['access_token'];
    }

    /**
     * Merchant Initiated Payment (MIP)
     */
    public function merchantPayment(array $data)
    {
        $token = $this->getToken();
        $correlationId = (string) Str::uuid();

        $body = [
            'amount'    => (string) $data['amount'],
            'currency'  => $data['currency'] ?? 'GYD',
            'subType'   => 'merinipmt',
            'type'      => 'transfer',
            'debitParty' => [
                ['key' => 'accountid', 'value' => $data['customer_msisdn']]
            ],
            'creditParty' => [
                ['key' => 'accountid', 'value' => $this->merchantMid]
            ],
            'metadata' => [
                ['key' => 'remarks', 'value' => $data['description'] ?? 'Payment'],
                ['key' => 'pmtType', 'value' => 'MPYMT'],
                ['key' => 'fee', 'value' => (string) ($data['fee'] ?? $data['amount'])]
            ]
        ];

        $headers = [
            'x-wss-mid'           => $this->merchantMid,
            'x-wss-token'         => "Bearer {$token}",
            'x-wss-mkey'          => $this->mKey,
            'x-wss-msecret'       => $this->mSecret,
            'x-api-key'           => $this->xApiKey,
            'x-wss-correlationid' => $correlationId,
            'Content-Type'        => 'application/json',
            'Accept'              => 'application/json',
        ];

        // Log headers for debugging (mask sensitive values)
        Log::info('MMG Payment Headers', [
            'x-wss-mid' => $this->merchantMid,
            'x-wss-token' => "Bearer " . substr($token, 0, 4) . '***',
            'x-wss-mkey' => substr($this->mKey, 0, 4) . '***',
            'x-wss-msecret' => substr($this->mSecret, 0, 4) . '***',
            'x-api-key' => substr($this->xApiKey, 0, 4) . '***',
            'x-wss-correlationid' => $correlationId,
        ]);

        $url = "{$this->baseUrl}/merchant/transactions/{$this->merchantMid}";

        Log::info('Sending MMG Merchant Payment', [
            'url' => $url,
            'correlation_id' => $correlationId,
            'body' => $body
        ]);

        $response = Http::timeout(60)
            ->withHeaders($headers)
            ->post($url, $body);

        Log::info('MMG Payment Response', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        if ($response->failed()) {
            Log::error('MMG Payment Failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'headers' => $headers, // Note: In production, avoid logging full headers if sensitive
                'url' => $url,
            ]);
            throw new \Exception('Payment failed: ' . $response->body());
        }

        $responseData = $response->json();

        // Handle pending approval
        if (isset($responseData['status']) && $responseData['status'] === 'pending') {
            Log::info('Payment is pending approval', ['response' => $responseData]);
        }

        return $responseData;
    }
}
