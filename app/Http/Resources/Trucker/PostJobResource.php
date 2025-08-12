<?php


namespace App\Http\Resources\Trucker;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class PostJobResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'job_id' => $this->job_id,
            'user' => $this->user?->name,
            'package_name' => $this->package_name,
            'shipment_type' => $this->shipment_type,
            'priority' => $this->priority,
            'pickup_location' => [
                'address' => $this->pickup_address,
                'city' => $this->pickup_city,
                'state' => $this->pickup_state,
                'zip' => $this->pickup_zip,
                'lat' => $this->pickup_latitude,
                'lng' => $this->pickup_longitude,
            ],
            'delivery_location' => [
                'address' => $this->delivery_address,
                'city' => $this->delivery_city,
                'state' => $this->delivery_state,
                'zip' => $this->delivery_zip,
            ],
            'cargo' => [
                'type' => $this->cargo_type,
                'weight' => $this->weight,
                'weight_type' => $this->weight_type,
                'quantity' => $this->quantity,
                'dimensions' => [
                    'length' => $this->length,
                    'width' => $this->width,
                    'height' => $this->height,
                ],
            ],
            'schedule' => [
                'pickup_date' => $this->pickup_date->format('Y-m-d'),
                'pickup_time' => $this->pickup_time->format('H:i'),
                'delivery_date' => $this->delivery_date->format('Y-m-d'),
                'delivery_time' => $this->delivery_time->format('H:i'),
            ],
            'options' => [
                'is_urgent_shipment' => $this->is_urgent_shipment,
                'flexible_with_pickup' => $this->flexible_with_pickup,
                'temperature_controlled' => $this->temperature_controlled,
                'fragile_handling' => $this->fragile_handling,
                'hazardous_materials' => $this->hazardous_materials,
            ],
            'instructions' => $this->additional_instructions,
            'budget' => [
                'amount' => $this->budget_amount,
                'currency' => $this->currency,
            ],
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }

}
