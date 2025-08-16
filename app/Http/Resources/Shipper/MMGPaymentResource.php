<?php

namespace App\Http\Resources\Shipper;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MMGPaymentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'status'      => $this['status'] ?? null,
            'transaction' => $this['objectReference'] ?? null,
            'balance'     => [
                'normal_wallet' => $this['normalWalletAvailableBalance'] ?? null,
                'bonus_wallet'  => $this['bonusWalletAvailableBalance'] ?? null,
            ],
            'server_correlation_id' => $this['serverCorrelationId'] ?? null,
            'pending_reason' => $this['pendingReason'] ?? null,
            'notification_method' => $this['notificationMethod'] ?? null,
        ];
    }
}
