<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MMGPaymentService
{
    protected string $baseUrl = 'https://uat-api.mmg.gy';
    protected string $authUrl = 'https://gtt-uat-oauth2-service-api.qpass.com:9143/oauth2-endpoint/oauth/resourcetoken';

    protected string $merchantMid;
    protected string $merchantPassword;
    protected string $apiKey;
    protected string $mKey;
    protected string $mSecret;

    public function __construct()
    {
        $this->merchantMid = config('services.mmg.merchant_mid');
        $this->merchantPassword = config('services.mmg.merchant_password');
        $this->apiKey = config('services.mmg.resourcetoken_apikey');
        $this->mKey = config('services.mmg.merchant_mkey');
        $this->mSecret = config('services.mmg.merchant_msecret');
    }

    /**
     * Get Resource Token
     */
    public function getToken(): string
    {
        $response = Http::asForm()->post($this->authUrl, [
            'grant_type' => 'password',
            'api_key'    => $this->apiKey,
            'username'   => $this->merchantMid,
            'password'   => $this->merchantPassword,
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to get MMG token: ' . $response->body());
        }

        return $response->json()['access_token'];
    }

    /**
     * Process Merchant Initiated Payment
     */
    public function merchantPayment(array $data)
    {
        $token = $this->getToken();
        $body = [
            'amount'    => $data['amount'],
            'currency'  => $data['currency'] ?? 'GYD',
            'subType'   => 'merinipmt',
            'type'      => 'transfer',
            'debitParty' => [
                [
                    'key'   => 'accountid',
                    'value' => $data['customer_msisdn']
                ]
            ],
            'creditParty' => [
                [
                    'key'   => 'accountid',
                    'value' => $this->merchantMid
                ]
            ],
            'metadata' => [
                [
                    'key'   => 'remarks',
                    'value' => $data['description'] ?? 'Payment'
                ],
                [
                    'key'   => 'pmtType',
                    'value' => 'MPYMT'
                ]
            ]
        ];
        $response = Http::withHeaders([
            'x-wss-token'        => "Bearer {$token}",
            'x-wss-mkey'         => $this->mKey,
            'x-wss-msecret'      => $this->mSecret,
            'x-wss-correlationid'=> (string) \Str::uuid(),
            'x-wss-mid'          => $this->merchantMid,
            'x-api-key'          => config('services.mmg.x_api_key'),
        ])->post("{$this->baseUrl}/merchant/transactions/{$this->merchantMid}", $body);

        if ($response->failed()) {
            throw new \Exception('Payment failed: ' . $response->body());
        }
        return $response->json();
    }
}
