<?php

namespace App\Http\Resources\Trucker;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UpdateTrackingStatusResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'tracking_time' => $this->tracking_time,
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
