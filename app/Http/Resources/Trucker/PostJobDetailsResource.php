<?php

namespace App\Http\Resources\Trucker;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostJobDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
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
                'latitude' => $this->pickup_latitude,
                'longitude' => $this->pickup_longitude,
                'pickup_date' => $this->pickup_date ? \Carbon\Carbon::parse($this->pickup_date)->format('F d, Y') : null,
                'pickup_time' => $this->pickup_time ? \Carbon\Carbon::parse($this->pickup_time)->format('H:i') : null,
                'delivery_date' => $this->delivery_date ? \Carbon\Carbon::parse($this->delivery_date)->format('F d, Y') : null,
                'delivery_time' => $this->delivery_time ? \Carbon\Carbon::parse($this->delivery_time)->format('H:i') : null,

            ],
            'delivery_location' => [
                'address' => $this->delivery_address,
                'city' => $this->delivery_city,
                'state' => $this->delivery_state,
                'zip' => $this->delivery_zip,
                'pickup_date' => $this->pickup_date ? \Carbon\Carbon::parse($this->pickup_date)->format('F d, Y') : null,
                'pickup_time' => $this->pickup_time ? \Carbon\Carbon::parse($this->pickup_time)->format('H:i') : null,
                'delivery_date' => $this->delivery_date ? \Carbon\Carbon::parse($this->delivery_date)->format('F d, Y') : null,
                'delivery_time' => $this->delivery_time ? \Carbon\Carbon::parse($this->delivery_time)->format('H:i') : null,

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
            'shipment_information' => [
                'package_name' => $this->package_name,
                'shipping_method' => 'Ocean Freight', // You may want to add this field to the DB if dynamic
                'insurance' => 'Full Coverage',      // Add DB columns if needed
                'incoterms' => 'FOB ' . $this->pickup_city,  // Dynamic if possible
                'container_type' => '40ft Standard',          // Add DB columns if needed
            ],
            'options' => [
                'is_urgent_shipment' => $this->is_urgent_shipment,
                'flexible_with_pickup' => $this->flexible_with_pickup,
                'temperature_controlled' => $this->temperature_controlled,
                'fragile_handling' => $this->fragile_handling,
                'hazardous_materials' => $this->hazardous_materials,
            ],
            'additional_instructions' => $this->additional_instructions,
            'budget' => [
                'amount' => $this->budget_amount,
                'currency' => $this->currency,
            ],
            'last_updated' => $this->updated_at->diffForHumans(),
            'delivery_status' => $this->delivery_status,
        ];
    }
}
