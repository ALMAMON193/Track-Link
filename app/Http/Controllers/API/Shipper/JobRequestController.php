<?php

namespace App\Http\Controllers\API\Shipper;

use App\Http\Controllers\Controller;
use App\Http\Resources\Shipper\RequestJobDetailsResource;
use App\Http\Resources\Shipper\RequestJobResource;
use App\Http\Resources\Shipper\UserDetailsResource;
use App\Models\JobPost;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobRequestController extends Controller
{
    use ApiResponse;
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
    public function show($id)
    {
        $job = JobPost::with(['applications.user'])->findOrFail($id);
        return $this->sendResponse(
            new RequestJobDetailsResource($job),
            __('Job details retrieved successfully.')
        );
    }
    //details user
    public function userDetails($jobId, $userId)
    {
        // Load job with applications and nested user relations
        $job = JobPost::with([
            'applications.user.driverDetail',
            'applications.user.experiencePreference'
        ])->findOrFail($jobId);

        // Find the specific application for this user
        $application = $job->applications->firstWhere('user_id', $userId);

        if (!$application) {
            return $this->sendResponse(null, 'Application not found', 404);
        }
        // Pass both user and application to the resource
        return $this->sendResponse(
            new UserDetailsResource($application->user, $application),
            'User details retrieved successfully.'
        );
    }




}
