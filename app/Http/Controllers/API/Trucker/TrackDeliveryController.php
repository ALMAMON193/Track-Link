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
    public function updateDeliveryStatus(Request $request, $jobPostId)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'delivery_status' => 'required|in:Pending,Delayed,Complete,In_Transport',
            ]);
            // Find JobPost
            $jobPost = JobPost::findOrFail($jobPostId);
            // Update delivery status
            $jobPost->delivery_status = $validated['delivery_status'];
            $jobPost->save();
            // Send notification
            if ($jobPost->user) {
                $jobPost->user->notify(new OrderStatusUpdated($jobPost, 'Delivery', $validated['delivery_status']));
            }
            return $this->sendResponse(
                new UpdateDeliveryStatusResource($jobPost),
                __('Delivery Status Updated Successfully.')
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->sendError('Validation Error', $e->errors(), 422);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('Job Post Not Found');

        } catch (\Exception $e) {
            Log::error('Delivery Status Update Failed: '.$e->getMessage(), ['jobPostId' => $jobPostId]);
            return $this->sendError('Something went wrong. Please try again later.');
        }
    }

    public function updateTrackingStatus(Request $request, $jobPostId)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'tracking_time_status' => 'required|in:Customs Clearance,Departed from Port,In Transit,Arrived at Port',
            ]);
            // Find JobPost
            $jobPost = JobPost::findOrFail($jobPostId);
            // Update tracking time
            $jobPost->tracking_time = $validated['tracking_time_status'];
            $jobPost->save();
            // Send notification
            if ($jobPost->user) {
                $jobPost->user->notify(new OrderStatusUpdated($jobPost, 'Tracking', $validated['tracking_time_status']));
            }
            return $this->sendResponse(
                new UpdateTrackingStatusResource($jobPost),
                __('Tracking Status Updated Successfully.')
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->sendError('Validation Error', $e->errors(), 422);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('Job Post Not Found');
        } catch (\Exception $e) {
            Log::error('Tracking Status Update Failed: '.$e->getMessage(), ['jobPostId' => $jobPostId]);
            return $this->sendError('Something went wrong. Please try again later.');
        }
    }

}
