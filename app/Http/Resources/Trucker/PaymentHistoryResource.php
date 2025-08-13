<?php

namespace App\Http\Resources\Trucker;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentHistoryResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
