<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetAvailability extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'time',
        'is_available'
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
        'is_available' => 'boolean',
    ];
}
