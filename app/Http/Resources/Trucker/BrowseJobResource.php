<?php

namespace App\Http\Resources\Trucker;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrowseJobResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'job_id'           => $this->job_id,
            'package_name'     => $this->package_name,
            'pickup_location'  => [
                'address' => $this->pickup_address,
                'city'    => $this->pickup_city,
                'state'   => $this->pickup_state,
            ],
            'delivery_location' => [
                'address' => $this->delivery_address,
                'city'    => $this->delivery_city,
                'state'   => $this->delivery_state,
            ],
            'cargo_details'    => $this->quantity . ' containers, ' . $this->weight . ' ' . $this->weight_type,
            'budget_amount'    => $this->budget_amount,
            'created_at'       => optional($this->created_at)->format('M j, Y'),
        ];
    }
}
