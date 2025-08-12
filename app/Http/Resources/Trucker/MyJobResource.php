<?php

namespace App\Http\Resources\Trucker;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MyJobResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $job = $this->jobPost;

        return [
            'id'    => $this->id,
            'job_id' => $job->job_id ?? null,
            'package_name' => $job->package_name ?? null,
            'pickup_location' => [
                'address' => $job->pickup_address ?? null,
                'city' => $job->pickup_city ?? null,
                'state' => $job->pickup_state ?? null,
            ],
            'delivery_location' => [
                'address' => $job->delivery_address ?? null,
                'city' => $job->delivery_city ?? null,
                'state' => $job->delivery_state ?? null,
            ],
            'departed_schedule' => [
                'departed_start' => $job && $job->pickup_date ? \Carbon\Carbon::parse($job->pickup_date)->format('M d') : null,
                'departed_end' => $job && $job->delivery_date ? \Carbon\Carbon::parse($job->delivery_date)->format('M d') : null,
            ],
            'delivery_status' => $job->delivery_status ?? 'N/A',
            'updated_at' => $this->updated_at ? $this->updated_at->diffForHumans() : null,
        ];
    }

}
