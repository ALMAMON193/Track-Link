<?php

namespace App\Http\Resources\Shipper;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestJobDetailsResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'job_details' => [
                'id'                => $this->id,
                'job_id'            => $this->job_id,
                'package_name'      => $this->package_name,
                'shipment_type'     => $this->shipment_type,
                'priority'          => $this->priority,

                'pickup_location'   => "{$this->pickup_city}, {$this->pickup_state}",
                'pickup_address'    => $this->pickup_address,
                'pickup_zip'        => $this->pickup_zip,
                'pickup_latitude'   => $this->pickup_latitude,
                'pickup_longitude'  => $this->pickup_longitude,

                'delivery_location' => "{$this->delivery_city}, {$this->delivery_state}",
                'delivery_address'  => $this->delivery_address,
                'delivery_zip'      => $this->delivery_zip,
                'delivery_latitude' => $this->delivery_latitude,
                'delivery_longitude'=> $this->delivery_longitude,

                'cargo_type'        => $this->cargo_type,
                'weight'            => "{$this->weight} {$this->weight_type}",
                'quantity'          => $this->quantity,
                'dimensions'        => "{$this->length}x{$this->width}x{$this->height}",

                'pickup_date'       => $this->pickup_date,
                'pickup_time'       => $this->pickup_time,
                'delivery_date'     => $this->delivery_date,
                'delivery_time'     => $this->delivery_time,

                'is_urgent_shipment'=> (bool)$this->is_urgent_shipment,
                'flexible_with_pickup' => (bool)$this->flexible_with_pickup,
                'temperature_controlled' => (bool)$this->temperature_controlled,
                'fragile_handling'  => (bool)$this->fragile_handling,
                'hazardous_materials'=> (bool)$this->hazardous_materials,
                'additional_instructions' => $this->additional_instructions,

                'budget_amount'     => $this->budget_amount ?? 'N/A',
                'currency'          => $this->currency,

                'distance'          => Helper::formatDistance(
                    $this->pickup_latitude,
                    $this->pickup_longitude,
                    $this->delivery_latitude,
                    $this->delivery_longitude
                ),
                'delivery_status'   => $this->delivery_status,
            ],
            'total_request' => $this->applications->count(),
            'requested_users' => $this->applications->map(function ($application) {
                return [
                    'user_id'   => $application->user->id,
                    'user_name' => $application->user->name,
                    'avatar'    => $application->user->personalInformation && $application->user->personalInformation->avatar
                        ? asset('storage/' . $application->user->personalInformation->avatar)
                        : '',
                    'request_time' => $application->created_at->toDateTimeString(),
                ];
            }),

        ];
    }
}
