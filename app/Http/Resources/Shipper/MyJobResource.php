<?php

namespace App\Http\Resources\Shipper;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MyJobResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'job_id'            => $this->job_id,
            'package_name'      => $this->package_name,
            'pickup_location'   => "{$this->pickup_city}, {$this->pickup_state}",
            'delivery_location' => "{$this->delivery_city}, {$this->delivery_state}",
            'cargo'             => "{$this->quantity} containers, {$this->weight} {$this->weight_type}",
            'distance'          => Helper::formatDistance(
                $this->pickup_latitude,
                $this->pickup_longitude,
                $this->delivery_latitude,
                $this->delivery_longitude
            ),
            'departed_schedule' => [
                'departed_start' => Helper::formatDate($this->pickup_date),
                'departed_end'   => Helper::formatDate($this->delivery_date),
                'percentage'     => Helper::percentageCalculate($this->delivery_status),
            ],
            'delivery_status'   => $this->delivery_status ?? 'N/A',
            'updated_at'        => $this->when(
                $this->updated_at,
                fn () => $this->updated_at->diffForHumans()
            ),
        ];
    }
}
