<?php

namespace App\Helpers;

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
        $timeline = [];

        if ($jobPost->tracking_status) {
            $timeline[] = [
                'status' => $jobPost->tracking_status,
                'location' => $jobPost->tracking_location ?? 'Unknown',
                'datetime' => $jobPost->tracking_date
                    ? Carbon::parse($jobPost->tracking_date)->format('M d, Y • h:i A')
                    : null,
            ];
        }

        return $timeline;
    }
}
