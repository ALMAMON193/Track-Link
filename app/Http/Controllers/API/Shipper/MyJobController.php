<?php

namespace App\Http\Controllers\API\Shipper;

use App\Http\Controllers\Controller;
use App\Http\Resources\Shipper\MyJobResource;
use App\Models\JobPost;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class MyJobController extends Controller
{
    use ApiResponse;

    public function myJobs(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $userId  = auth()->id();

        // Base query to avoid repeating "where user_id = $userId"
        $baseQuery = JobPost::where('user_id', $userId);

        // Overview calculations
        $overview = [
            'total_job_post'                => (clone $baseQuery)->count(),
            'total_job_post_this_week'      => (clone $baseQuery)
                ->where('created_at', '>=', Carbon::now()->startOfWeek())
                ->count(),
            'total_in_transport'            => (clone $baseQuery)
                ->where('delivery_status', 'In_Transport')
                ->count(),
            'today_arriving_job'            => (clone $baseQuery)
                ->whereDate('delivery_date', Carbon::today())
                ->count(),
            'total_delivered_job'           => (clone $baseQuery)
                ->where('delivery_status', 'Complete')
                ->count(),
            'this_month_delivered_job'      => (clone $baseQuery)
                ->where('delivery_status', 'Complete')
                ->whereBetween('delivery_date', [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth()
                ])
                ->count(),
            'total_spend_amount'            => "2000.00",  // You can calculate dynamically if needed
            'total_spend_amount_last_month' => " - 8%",    // You can calculate dynamically if needed
        ];

        // Paginated jobs excluding completed jobs
        $myJobs = (clone $baseQuery)
            ->where('delivery_status', '!=', 'Complete')
            ->latest()
            ->paginate($perPage);

        // Return combined overview and jobs in a single API response
        return $this->sendResponse(
            [
                    'overview' => $overview,
                'my_jobs'  => MyJobResource::collection($myJobs)
            ],
            __('My jobs fetched successfully')
        );
    }
}
