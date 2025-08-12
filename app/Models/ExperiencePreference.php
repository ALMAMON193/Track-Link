<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class ExperiencePreference extends Model
{
    protected $table = 'experience_preferences';
    protected $fillable = [
        'user_id',
        'experience',
        'vehicle_type',
        'service_area',
        'additional_information',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
