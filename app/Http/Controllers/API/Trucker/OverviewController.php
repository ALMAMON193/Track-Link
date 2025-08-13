<?php

namespace App\Http\Controllers\API\Trucker;

use App\Http\Controllers\Controller;
use App\Http\Resources\Trucker\OverviewResource;
use App\Models\JobPost;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class OverviewController extends Controller
{
    use ApiResponse;

    public function overview()
    {
        $userId = auth()->id();

        // Example calculations
        $availableJobsCount = JobPost::where('delivery_status', 'Pending')->count();
        $earningsThisMonth = JobPost::where('user_id', $userId)
            ->whereMonth('created_at', now()->month)
            ->sum('budget_amount');
        $totalCompleteJobs = JobPost::where('user_id', $userId)
            ->where('delivery_status', 'Complete')
            ->count();
        $completedThisMonth = JobPost::where('user_id', $userId)
            ->where('delivery_status', 'Complete')
            ->whereMonth('created_at', now()->month)
            ->count();

        // Mock rating data
        $averageRating = 4.8;
        $totalReviews = 24;

        $availableJobs = JobPost::where('delivery_status', 'Pending')->limit(3)->get();
        $myJobs = JobPost::where('user_id', $userId)->limit(5)->get();

        $data = (object)[
            'available_jobs_count' => $availableJobsCount,
            'available_jobs_change_percentage' => 12,
            'earnings_this_month' => $earningsThisMonth,
            'total_complete_jobs' => $totalCompleteJobs,
            'completed_this_month' => $completedThisMonth,
            'average_rating' => $averageRating,
            'total_reviews' => $totalReviews,
            'available_jobs' => $availableJobs,
            'my_jobs' => $myJobs,
        ];
        return $this->sendResponse(new OverviewResource($data), 'Overview fetched successfully');
    }
}
