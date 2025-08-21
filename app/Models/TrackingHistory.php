<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackingHistory extends Model
{
    protected $fillable = ['job_post_id', 'status', 'location', 'datetime'];

    public function jobPost(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(JobPost::class);
    }
}
