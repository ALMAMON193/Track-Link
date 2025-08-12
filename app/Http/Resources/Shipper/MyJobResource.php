<?php

namespace App\Http\Resources\Shipper;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MyJobResource extends JsonResource
{
    private function percentageCalculate(): int
    {
        return match ($this->delivery_status) {
            'In_Transport' => 50,
            'Delayed' => 30,
            'Complete' => 100,
            default => 0,
        };
    }
    public function toArray(Request $request): array
    {
        return [
            'id'    =>$this->id,
            'job_id' => $this->job_id,
            'package_name' => $this->package_name,
            'pickup_location' => [
                'address' => $this->pickup_address,
                'city' => $this->pickup_city,
                'state' => $this->pickup_state,
            ],
            'delivery_location' => [
                'address' => $this->delivery_address,
                'city' => $this->delivery_city,
                'state' => $this->delivery_state,
            ],
            'departed_schedule' => [
                'departed_start' => \Carbon\Carbon::parse($this->pickup_date)->format('M d'),
                'departed_end' => \Carbon\Carbon::parse($this->delivery_date)->format('M d'),
                'percentage' => $this->percentageCalculate(),
            ],
            'delivery_status' => $this->delivery_status ?? 'N/A',
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
