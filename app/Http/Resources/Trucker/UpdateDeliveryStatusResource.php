<?php

namespace App\Http\Resources\Trucker;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UpdateDeliveryStatusResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'delivery_status' => $this->delivery_status,
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
