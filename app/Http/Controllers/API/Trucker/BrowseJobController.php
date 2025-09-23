<?php

namespace App\Http\Controllers\API\Trucker;

use App\Http\Controllers\Controller;
use App\Http\Resources\Shipper\MyJobResource;
use App\Http\Resources\Trucker\BrowseJobResource;
use App\Models\JobApplication;
use App\Models\JobPost;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrowseJobController extends Controller
{
    use ApiResponse;


    /**
     * Browse available jobs
     */
    public function browseJob(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $myJobs = JobPost::where('delivery_status', 'Pending')
            ->latest()
            ->paginate($perPage);

        return $this->sendResponse(
            BrowseJobResource::collection($myJobs),
            __('Fetch Browse Jobs')
        );
    }


    /**
     * Apply for a job
     */
    public function applyJob(Request $request)
    {
        $request->validate([
            'job_post_id' => 'required|exists:job_posts,id',
        ]);

        $user = Auth::user();

        // Check if already applied
        $alreadyApplied = JobApplication::where('user_id', $user->id)
            ->where('job_post_id', $request->job_post_id)
            ->exists();

        if ($alreadyApplied) {
            return $this->sendError('Job already applied');
        }

        // Create new job application
        JobApplication::create([
            'user_id'     => $user->id,
            'job_post_id' => $request->job_post_id,
            'status'      => 'applied',
        ]);

        return $this->sendResponse([], __('Successfully Applied for this job.'));
    }


    /**
     * Fetch job details
     */
    public function jobDetails($id)
    {
        $jobDetails = JobPost::where('delivery_status', 'Pending')
            ->where('id', $id)
            ->firstOrFail();

        return $this->sendResponse(
            new BrowseJobResource($jobDetails),
            __('Fetch Job Details')
        );
    }

}
