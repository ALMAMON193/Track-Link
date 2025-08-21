<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'job_post_id',
        'amount',
        'currency',
        'transaction_id',
        'status',
        'response',
    ];

    protected $casts = [
        'response' => 'array',
    ];

    public function jobPost(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(JobPost::class);
    }
}
