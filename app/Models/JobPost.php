<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    protected $fillable = [
        'job_id',
        'user_id',
        'package_name',
        'shipment_type',
        'priority',
        'pickup_address',
        'pickup_city',
        'pickup_state',
        'pickup_zip',
        'pickup_latitude',
        'pickup_longitude',
        'delivery_address',
        'delivery_city',
        'delivery_state',
        'delivery_zip',
        'cargo_type',
        'weight',
        'weight_type',
        'quantity',
        'length',
        'width',
        'height',
        'pickup_date',
        'pickup_time',
        'delivery_date',
        'delivery_time',
        'is_urgent_shipment',
        'flexible_with_pickup',
        'temperature_controlled',
        'fragile_handling',
        'hazardous_materials',
        'additional_instructions',
        'budget_amount',
        'currency',
        'delivery_status',
        'tracking_time',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
