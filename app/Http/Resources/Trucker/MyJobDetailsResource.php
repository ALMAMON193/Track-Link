<?php

namespace App\Http\Resources\Trucker;

use Illuminate\Http\Resources\Json\JsonResource;

class MyJobDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'assigned_at' => $this->assigned_at,
            'rejection_reason' => $this->rejection_reason,
            'applied_by' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'user_type' => $this->user->user_type,
            ],
            'job_post' => [
                'id' => $this->jobPost->id,
                'job_id' => $this->jobPost->job_id,
                'package_name' => $this->jobPost->package_name,
                'shipment_type' => $this->jobPost->shipment_type,
                'priority' => $this->jobPost->priority,
                'pickup' => [
                    'address' => $this->jobPost->pickup_address,
                    'city' => $this->jobPost->pickup_city,
                    'state' => $this->jobPost->pickup_state,
                    'zip' => $this->jobPost->pickup_zip,
                    'latitude' => $this->jobPost->pickup_latitude,
                    'longitude' => $this->jobPost->pickup_longitude,
                ],
                'delivery' => [
                    'address' => $this->jobPost->delivery_address,
                    'city' => $this->jobPost->delivery_city,
                    'state' => $this->jobPost->delivery_state,
                    'zip' => $this->jobPost->delivery_zip,
                    'latitude' => $this->jobPost->delivery_latitude,
                    'longitude' => $this->jobPost->delivery_longitude,
                ],
                'cargo' => [
                    'type' => $this->jobPost->cargo_type,
                    'weight' => $this->jobPost->weight,
                    'weight_type' => $this->jobPost->weight_type,
                    'quantity' => $this->jobPost->quantity,
                    'dimensions' => [
                        'length' => $this->jobPost->length,
                        'width' => $this->jobPost->width,
                        'height' => $this->jobPost->height,
                    ],
                ],
                'schedule' => [
                    'pickup_date' => $this->jobPost->pickup_date,
                    'pickup_time' => $this->jobPost->pickup_time,
                    'delivery_date' => $this->jobPost->delivery_date,
                    'delivery_time' => $this->jobPost->delivery_time,
                ],
                'options' => [
                    'is_urgent_shipment' => $this->jobPost->is_urgent_shipment,
                    'flexible_with_pickup' => $this->jobPost->flexible_with_pickup,
                    'temperature_controlled' => $this->jobPost->temperature_controlled,
                    'fragile_handling' => $this->jobPost->fragile_handling,
                    'hazardous_materials' => $this->jobPost->hazardous_materials,
                ],
                'additional_instructions' => $this->jobPost->additional_instructions,
                'budget' => [
                    'amount' => $this->jobPost->budget_amount,
                    'currency' => $this->jobPost->currency,
                ],
                'delivery_status' => $this->jobPost->delivery_status,
                'tracking_status' => $this->jobPost->tracking_status,
                'tracking_location' => $this->jobPost->tracking_location,
                'tracking_date' => $this->jobPost->tracking_date,
                'is_expired' => $this->jobPost->is_expired,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
