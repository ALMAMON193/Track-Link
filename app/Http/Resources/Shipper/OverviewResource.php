<?php

namespace App\Http\Resources\Shipper;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OverviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'overview' => [
                'available_jobs' => [
                    'count' => $this->available_jobs_count,
                    'change_percentage' => $this->available_jobs_change_percentage,
                ],
                'earnings_this_month' => [
                    'amount' => number_format($this->earnings_this_month, 2),
                    'currency' => 'USD',
                ],
                'total_complete_job' => [
                    'total_complete' => $this->total_complete_jobs,
                    'completed_this_month' => $this->completed_this_month,
                ],
            ],
            'my_jobs' => $this->my_jobs->map(function ($job) {
                return [
                    'job_title' => $job->package_name,
                    'pickup_location' => $job->pickup_city . ', ' . $job->pickup_state,
                    'dropoff_location' => $job->delivery_city . ', ' . $job->delivery_state,
                    'amount' => number_format($job->budget_amount, 2),
                    'pickup_date' => $job->pickup_date->format('M d, Y'),
                    'delivery_date' => $job->delivery_date->format('M d, Y'),
                    'status' => $job->delivery_status,
                ];
            }),
        ];
    }
}
