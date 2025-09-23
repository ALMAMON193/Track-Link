<?php

namespace App\Http\Controllers\API\Shipper;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Shipper\RequestJobDetailsResource;
use App\Http\Resources\Shipper\RequestJobResource;
use App\Http\Resources\Shipper\UserDetailsResource;
use App\Models\JobApplication;
use App\Models\JobPost;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobRequestController extends Controller
{
    use ApiResponse;

    /**
     * List all jobs created by the logged-in shipper with applicants.
     */
    public function index()
    {
        $user = Auth::user();

        $jobsWithApplications = JobPost::with('applications.user')
            ->where('user_id', $user->id)
            ->whereHas('applications')
            ->latest()
            ->get();

        return $this->sendResponse(
            RequestJobResource::collection($jobsWithApplications),
            'Jobs with applicants retrieved successfully.'
        );
    }

    /**
     * Show a specific job with its applicants.
     */
    public function show($id)
    {
        $job = JobPost::with(['applications.user'])->findOrFail($id);

        return $this->sendResponse(
            new RequestJobDetailsResource($job),
            __('Job details retrieved successfully.')
        );
    }

    /**
     * Show detailed information about a specific applicant for a job.
     */
    public function userDetails($jobId, $userId)
    {
        $job = JobPost::with([
            'applications.user.driverDetail',
            'applications.user.experiencePreference'
        ])
            ->findOrFail($jobId);

        $application = $job->applications->firstWhere('user_id', $userId);

        if (!$application) {
            return $this->sendResponse(null, 'Application not found', 404);
        }

        $stats = Helper::calculateJobStats($userId);

        return $this->sendResponse(
            new UserDetailsResource($application->user, $application, $stats),
            'User details retrieved successfully.'
        );
    }

    /**
     * Reject a job application with optional reason.
     */
    public function jobReject(Request $request, $jobId, $userId)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:255',
        ]);

        $application = JobApplication::where('job_post_id', $jobId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $application->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        $application->delete();

        return $this->sendResponse([], __('Job application rejected & removed successfully.'));
    }

    /**
     * Accept a job applicant and reject all others.
     */
    public function jobAccept(Request $request)
    {
        $request->validate([
            'job_post_id' => 'required|exists:job_posts,id',
            'user_id'     => 'required|exists:users,id',
        ]);

        $shipperId = auth()->id();

        $jobPost = JobPost::where('id', $request->job_post_id)
            ->where('user_id', $shipperId)
            ->first();

        if (!$jobPost) {
            return $this->sendError('You are not authorized to accept this job.', [], 403);
        }

        $application = JobApplication::where('user_id', $request->user_id)
            ->where('job_post_id', $request->job_post_id)
            ->first();

        if (!$application) {
            return $this->sendError('Application not found.');
        }

        $application->update([
            'status'      => 'accepted',
            'assigned_at' => now(),
        ]);

        JobApplication::where('job_post_id', $request->job_post_id)
            ->where('id', '!=', $application->id)
            ->update(['status' => 'rejected']);

        return $this->sendResponse([], 'Applicant accepted successfully.');
    }
}
