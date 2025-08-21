<?php

namespace App\Helpers;

use App\Models\JobPost;
use Carbon\Carbon;

class Helper
{
    public static function getDistance($lat1, $lon1, $lat2, $lon2): ?float
    {
        if (!$lat1 || !$lon1 || !$lat2 || !$lon2) {
            return null;
        }

        $earthRadius = 6371; // KM
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $latDelta = $lat2 - $lat1;
        $lonDelta = $lon2 - $lon1;

        $angle = 2 * asin(
                sqrt(
                    pow(sin($latDelta / 2), 2) +
                    cos($lat1) * cos($lat2) * pow(sin($lonDelta / 2), 2)
                )
            );

        return $earthRadius * $angle;
    }

    public static function formatDistance($lat1, $lon1, $lat2, $lon2): string
    {
        $distance = self::getDistance($lat1, $lon1, $lat2, $lon2);
        return $distance ? round($distance, 2) . ' km distance'  : 'N/A';
    }
    public static function formatDate($date, $format = 'M d'): ?string
    {
        return $date ? Carbon::parse($date)->format($format) : null;
    }
    public static function percentageCalculate($status): int
    {
        return match ($status) {
            'In_Transport' => 50,
            'Delayed'      => 30,
            'Complete'     => 100,
            default        => 0,
        };
    }
    public static function getTrackingTimeline($jobPost): array
    {
        // Predefined steps in correct order
        $steps = [
            'Customs Clearance (Origin)',
            'Departed from Port',
            'In Transit',
            'Arrived at Port',
            'Customs Clearance (Destination)',
        ];

        $timeline = [];
        $histories = $jobPost->trackingHistories()->orderBy('datetime')->get()->keyBy('status');

        foreach ($steps as $step) {
            $history = $histories->get($step);

            $timeline[] = [
                'status'    => $step,
                'location'  => $history?->location,
                'datetime'  => $history?->datetime
                    ? Carbon::parse($history->datetime)->format('M d, Y • h:i A')
                    : null,
                'current'   => $jobPost->tracking_time === $step,
                'completed' => $history !== null,
            ];
        }
        return $timeline;
    }

    //user details and overview
    public static function calculateJobStats($userId): array
    {
        $completedJobs = JobPost::whereHas('applications', function ($q) use ($userId) {
            $q->where('user_id', $userId)
                ->where('status', 'accepted');
        })->where('delivery_status', 'Complete')->count();

        $totalJobs = JobPost::whereHas('applications', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->count();

        $averageRating = 5.0;
        // Or: Rating::where('user_id', $userId)->avg('rating') ?? 0;

        $jobSuccess = $totalJobs ? ($completedJobs / $totalJobs) * 100 : 0;

        return [
            'complete_job' => $completedJobs,
            'ratting'      => number_format($averageRating, 1),
            'job_success'  => number_format($jobSuccess, 1),
        ];
    }
}
