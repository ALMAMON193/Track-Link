<?php

namespace App\Events;

use App\Models\JobPost;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class TrackingStatusUpdated implements ShouldBroadcast
{
    use SerializesModels;

    public JobPost $job;

    public function __construct(JobPost $job)
    {
        $this->job = $job;
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('job.' . $this->job->id);
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->job->id,
            'tracking_time' => $this->job->tracking_time,
        ];
    }
}
