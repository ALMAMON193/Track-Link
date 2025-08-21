<?php

namespace App\Http\Resources\Trucker;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UpdateTrackingStatusResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'job_post_id'    => $this->id,
            'current_status' => $this->tracking_time,
            'timeline'       => Helper::getTrackingTimeline($this),
            'last_updated'   => $this->updated_at?->toDateTimeString(),
        ];
    }
}
