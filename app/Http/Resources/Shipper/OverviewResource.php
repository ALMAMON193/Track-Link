<?php

namespace App\Http\Resources\Shipper;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class OverviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'total_job_post' => $this->total_job_post,
            'total_job_post_this_week' => $this->total_job_post_this_week,
            'total_in_transport' => $this->total_in_transport,
            'today_arriving_job' => $this->today_arriving_job,
            'total_delivered_job' => $this->total_delivered_job,
            'this_month_delivered_job' => $this->this_month_delivered_job,
            'total_spend_amount' => $this->total_spend_amount,
            'total_spend_amount_last_month' => $this->total_spend_amount_last_month,
            'recent_jobs' => $this->recent_jobs->map(function($job){
                return [
                    'id'                => $job->id,
                    'job_id'            => $job->job_id,
                    'package_name'      => $job->package_name,
                    'pickup_location'   => "{$job->pickup_city}, {$job->pickup_state}",
                    'delivery_location' => "{$job->delivery_city}, {$job->delivery_state}",
                    'cargo'             => "{$job->quantity} containers, {$job->weight} {$job->weight_type}",
                    'distance' => Helper::formatDistance(
                        $job->pickup_latitude,
                        $job->pickup_longitude,
                        $job->delivery_latitude,
                        $job->delivery_longitude
                    ),
                    'departed_schedule' => [
                        'departed_start' => Helper::formatDate($job->pickup_date),
                        'departed_end'   => Helper::formatDate($job->delivery_date),
                        'percentage'     => Helper::percentageCalculate($job->delivery_status),
                    ],
                    'delivery_status'   => $job->delivery_status ?? 'N/A',
                    'updated_at'        => $job->when($job->updated_at, fn () => $job->updated_at->diffForHumans()),
                ];
            }),
        ];
    }
}
