<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class JobApplication extends Model
{

    protected $fillable = [
        'user_id',         // Tracker ID
        'job_post_id',
        'assigned_at',
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    // Tracker who applied (User model)
    public function tracker(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // The job post related to this application
    public function jobPost(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(JobPost::class);
    }
}
