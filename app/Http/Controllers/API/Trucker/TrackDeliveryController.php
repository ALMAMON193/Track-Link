<?php

namespace App\Http\Controllers\API\Trucker;

use App\Events\TrackingStatusUpdated;
use App\Http\Controllers\Controller;
use App\Http\Resources\Trucker\MyJobResource;
use App\Http\Resources\Trucker\UpdateDeliveryStatusResource;
use App\Http\Resources\Trucker\UpdateTrackingStatusResource;
use App\Models\JobApplication;
use App\Models\JobPost;
use App\Models\User;
use App\Notifications\OrderStatusUpdated;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TrackDeliveryController extends Controller
{
    use ApiResponse;

    public function index (Request $request)
    {
        $perPage = $request->input ('per_page', 10);
        $myJobs = JobApplication::with ('jobPost')
            ->where ('user_id', auth ()->id ())
            ->where ('status', 'accepted')
            ->orderByDesc ('created_at')
            ->paginate ($perPage);
        return $this->sendResponse (
            MyJobResource::collection ($myJobs),
            __ ('Fetch My Jobs')
        );
    }
    //change a delivery status
    public function updateDeliveryStatus (Request $request, $jobPostId)
    {
        $request->validate ([
            'delivery_status' => 'required|in:Pending,Delayed,Complete,In_Transport',
        ]);

        $jobPost = JobPost::findOrFail ($jobPostId);
        $jobPost->delivery_status = $request->input ('delivery_status');
        $jobPost->save ();

        // Send notification to the user who created the job post
        $jobPost->user->notify (new OrderStatusUpdated($jobPost, 'Delivery', $request->delivery_status));

        return $this->sendResponse (
            new UpdateDeliveryStatusResource($jobPost),
            __ ('Delivery Status Updated')
        );
    }

    //change a tracking  status
    public function updateTrackingStatus (Request $request, $jobPostId)
    {
        $request->validate ([
            'tracking_time_status' => 'required|in:Customs Clearance,Departed from Port,In Transit,Arrived at Port',
        ]);

        $jobPost = JobPost::findOrFail ($jobPostId);
        $jobPost->tracking_time = $request->input ('tracking_time_status');
        $jobPost->save ();

        // Send notification to the user who created the job post
        $jobPost->user->notify (new OrderStatusUpdated($jobPost, 'Tracking', $request->tracking_time_status));

        return $this->sendResponse (
            new UpdateTrackingStatusResource($jobPost),
            __ ('Tracking Status Updated')
        );
    }

}
