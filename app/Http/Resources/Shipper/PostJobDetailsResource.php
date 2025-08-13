<?php

namespace App\Http\Resources\Shipper;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostJobDetailsResource extends JsonResource
{

    public function toArray(Request $request): array
    {

        return [
            'job_id' => $this->job_id,
            'user' => $this->user?->name,
            'package_name' => $this->package_name,
            'shipment_type' => $this->shipment_type,
            'priority' => $this->priority,
            'pickup_location'   => "{$this->pickup_city}, {$this->pickup_state}",
            'delivery_location' => "{$this->delivery_city}, {$this->delivery_state}",
            'cargo'             => "{$this->quantity} containers, {$this->weight} {$this->weight_type}",
            'distance' => Helper::formatDistance(
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
            'tracking_timeline' => Helper::getTrackingTimeline($this),
            'shipment_information' => [
                'package_name' => $this->package_name,
                'shipping_method' => 'Ocean Freight',
                'insurance' => 'Full Coverage',
                'incoterms' => 'FOB ' . $this->pickup_city,
                'container_type' => '40ft Standard',
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
            'delivery_status' => $this->delivery_status ?? 'N/A',
        ];
    }
}
