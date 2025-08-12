<?php

namespace App\Http\Requests\Shipper;

use Illuminate\Foundation\Http\FormRequest;

class PostJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Use Auth check if needed
    }

    public function rules(): array
    {
        return [
            'package_name' => 'required|string|max:255',
            'shipment_type' => 'required|string|max:255',
            'priority' => 'required|in:Standard,Express,Urgent',
            'pickup_address' => 'nullable|string|max:255',
            'pickup_city' => 'required|string|max:100',
            'pickup_state' => 'required|string|max:100',
            'pickup_zip' => 'required|string|max:20',
            'pickup_latitude' => 'required|numeric',
            'pickup_longitude' => 'required|numeric',

            'delivery_address' => 'required|string|max:255',
            'delivery_city' => 'required|string|max:100',
            'delivery_state' => 'required|string|max:100',
            'delivery_zip' => 'required|string|max:20',

            'cargo_type' => 'required|string|max:100',
            'weight' => 'required|numeric',
            'weight_type' => 'required|string|max:20',
            'quantity' => 'required|integer',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',

            'pickup_date' => 'required|date',
            'pickup_time' => 'required|date_format:H:i',
            'delivery_date' => 'required|date',
            'delivery_time' => 'required|date_format:H:i',

            'is_urgent_shipment' => 'boolean',
            'flexible_with_pickup' => 'boolean',
            'temperature_controlled' => 'boolean',
            'fragile_handling' => 'boolean',
            'hazardous_materials' => 'boolean',
            'additional_instructions' => 'nullable|string',

            'budget_amount' => 'required|numeric',
            'currency' => 'required|string|max:10',
        ];
    }
}
