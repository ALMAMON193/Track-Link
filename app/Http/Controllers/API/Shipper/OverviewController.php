<?php

namespace App\Http\Controllers\API\Shipper;

use App\Http\Controllers\Controller;
use App\Http\Resources\Shipper\OverviewResource;
use App\Models\JobPost;
use App\Traits\ApiResponse;
use Illuminate\Support\Carbon;

class OverviewController extends Controller
{
    use ApiResponse;
    public function shipperOverview()
    {
        $userId = auth()->id();
        // Base query to avoid repeating "where user_id = $userId" in all queries
        $baseQuery = JobPost::where('user_id', $userId);
        // Total jobs created by the user
        $totalJobPost = (clone $baseQuery)->count();
        // Jobs created this week
        $totalJobPostThisWeek = (clone $baseQuery)
            ->where('created_at', '>=', Carbon::now()->startOfWeek())
            ->count();
        // Jobs currently in transport
        $totalInTransport = (clone $baseQuery)
            ->where('delivery_status', 'In_Transport')
            ->count();
        // Jobs scheduled to be delivered today
        $todayArrivingJob = (clone $baseQuery)
            ->whereDate('delivery_date', Carbon::today())
            ->count();
        // All completed jobs (status = Complete)
        $totalDeliveredJob = (clone $baseQuery)
            ->where('delivery_status', 'Complete')
            ->count();
        // Jobs completed this month
        $thisMonthDeliveredJob = (clone $baseQuery)
            ->where('delivery_status', 'Complete')
            ->whereBetween('delivery_date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->count();
        // Total spend amount for all jobs
        $totalSpendAmount = "2000.00";
        // Total spend amount from last month
        $totalSpendAmountLastMonth = " - 8%";

        // Resent job Get the latest 3 jobs for dashboard display
        $recentJobs = (clone $baseQuery)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        //prepare response data
        $data = (object)[
            'total_job_post'                => $totalJobPost,
            'total_job_post_this_week'      => $totalJobPostThisWeek,
            'total_in_transport'            => $totalInTransport,
            'today_arriving_job'            => $todayArrivingJob,
            'total_delivered_job'           => $totalDeliveredJob,
            'this_month_delivered_job'      => $thisMonthDeliveredJob,
            'total_spend_amount'            => $totalSpendAmount,
            'total_spend_amount_last_month' => $totalSpendAmountLastMonth,
            'recent_jobs'                   => $recentJobs,
        ];
        // Send API response with OverviewResource
        return $this->sendResponse(new OverviewResource($data), __('Overview fetched successfully'));
    }
}
