<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalInformation extends Model
{
    protected $table = 'personal_information';

    protected $fillable = [
        'user_id',
        'city',
        'address',
        'phone',
        'about',
        'avatar',
    ];

    /**
     * The user that owns this personal information.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
