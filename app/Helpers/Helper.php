<?php

namespace App\Helpers;

use App\Models\JobPost;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Helper
{
    public static function getDistance($lat1, $lon1, $lat2, $lon2): ?float
    {
        if (! $lat1 || ! $lon1 || ! $lat2 || ! $lon2) {
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

        return $distance ? round($distance, 2).' km distance' : 'N/A';
    }

    public static function formatDate($date, $format = 'M d'): ?string
    {
        return $date ? Carbon::parse($date)->format($format) : null;
    }

    public static function percentageCalculate($status): int
    {
        return match ($status) {
            'In_Transport' => 50,
            'Delayed' => 30,
            'Complete' => 100,
            default => 0,
        };
    }

    public static function getTrackingTimeline($jobPost): array
    {
        // Predefined steps in correct order
        $steps = [
            'Customs Clearance',
            'Departed from Port',
            'In Transit',
            'Arrived at Port',
        ];

        $timeline = [];
        $histories = $jobPost->trackingHistories()->orderBy('datetime')->get()->keyBy('status');

        foreach ($steps as $step) {
            $history = $histories->get($step);

            $timeline[] = [
                'status' => $step,
                'location' => $history?->location,
                'datetime' => $history?->datetime
                    ? Carbon::parse($history->datetime)->format('M d, Y • h:i A')
                    : null,
                'current' => $jobPost->tracking_time === $step,
                'completed' => $history !== null,
            ];
        }

        return $timeline;
    }

    // user details and overview
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
            'ratting' => number_format($averageRating, 1),
            'job_success' => number_format($jobSuccess, 1),
        ];
    }

    public static function uploadFile($folderName, $file, $fileName = null): string
    {
        // Ensure folder exists
        $uploadPath = public_path('uploads/'.$folderName);
        if (! file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Generate file name if not provided
        $fileName = $fileName ?? time().'_'.Str::random(8).'.'.$file->getClientOriginalExtension();

        // Move file to public folder
        $file->move($uploadPath, $fileName);

        // Return relative path for URL usage
        return 'uploads/'.$folderName.'/'.$fileName;
    }

    /**
     * Delete a file from public/uploads
     */
    public static function deleteFile(?string $filePath): bool
    {
        if (! $filePath) {
            return false; // nothing to delete
        }

        $fullPath = public_path($filePath);

        // Only unlink if it's a file
        if (file_exists($fullPath) && is_file($fullPath)) {
            return unlink($fullPath);
        }

        return false;
    }

    /**
     * Generate a public URL for the uploaded file
     */
    public static function generateURL(?string $filePath): ?string
    {
        // Check if the path is empty or only whitespace
        if (empty($filePath) || trim($filePath) === '') {
            return null;
        }

        $fullPath = public_path($filePath);

        // Only return URL if file actually exists
        if (file_exists($fullPath)) {
            return asset($filePath);
        }

        return null;
    }
}
