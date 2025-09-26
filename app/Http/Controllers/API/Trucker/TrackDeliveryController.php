<?php

namespace App\Http\Controllers\API\Trucker;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Trucker\MyJobDetailsResource;
use App\Http\Resources\Trucker\MyJobResource;
use App\Http\Resources\Trucker\UpdateDeliveryStatusResource;
use App\Models\JobApplication;
use App\Models\JobPost;
use App\Notifications\OrderStatusUpdated;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TrackDeliveryController extends Controller
{
    use ApiResponse;

    /**
     * List all jobs assigned to the authenticated trucker
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $myJobs = JobApplication::with('jobPost')
            ->where('user_id', auth()->id())
            // ->where('status', 'accepted')
            ->orderByDesc('created_at')
            ->paginate($perPage);

        return $this->sendResponse(
            MyJobResource::collection($myJobs),
            __('Fetch My Jobs')
        );
    }

    // details job
    public function jobDetails($id)
    {
        $myJob = JobApplication::with('jobPost')->where('id', $id)->first();
        if (! $myJob) {
            return $this->sendError('Job Not Found');
        }

        return $this->sendResponse(
            new MyJobDetailsResource($myJob),
            __('Job Details Fetched Successfully.')
        );
    }

    /**
     * Update delivery status for a specific job
     */
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

            // Send notification to job owner
            if ($jobPost->user) {
                $jobPost->user->notify(
                    new OrderStatusUpdated($jobPost, 'Delivery', $validated['delivery_status'])
                );
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
            Log::error(
                'Delivery Status Update Failed: '.$e->getMessage(),
                ['jobPostId' => $jobPostId]
            );

            return $this->sendError('Something went wrong. Please try again later.');
        }
    }

    /**
     * Update tracking status for a specific job
     */
    public function updateTrackingStatus(Request $request, $jobPostId)
    {
        try {
            $validated = $request->validate([
                'tracking_status' => 'required|in:Customs Clearance,Departed from Port,In Transit,Arrived at Port',
                'location' => 'nullable|string',
                'datetime' => 'nullable|date',
            ]);

            $jobPost = JobPost::findOrFail($jobPostId);

            // Save latest status on JobPost
            $jobPost->tracking_status = $validated['tracking_status'];
            $jobPost->tracking_location = $validated['location'] ?? null;
            $jobPost->tracking_date = $validated['datetime'] ?? now();
            $jobPost->save();

            // Insert into tracking history
            $jobPost->trackingHistories()->create([
                'status' => $validated['tracking_status'],
                'location' => $validated['location'] ?? null,
                'datetime' => $validated['datetime'] ?? now(),
            ]);

            // Send notification to job owner
            if ($jobPost->user) {
                $jobPost->user->notify(
                    new OrderStatusUpdated($jobPost, 'Tracking', $validated['tracking_status'])
                );
            }

            return $this->sendResponse(
                Helper::getTrackingTimeline($jobPost),
                __('Tracking Status Updated Successfully.')
            );

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->sendError('Validation Error', $e->errors(), 422);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('Job Post Not Found');

        } catch (\Exception $e) {
            Log::error(
                'Tracking Status Update Failed: '.$e->getMessage(),
                ['jobPostId' => $jobPostId]
            );

            return $this->sendError('Something went wrong. Please try again later. '.$e->getMessage());
        }
    }
}
