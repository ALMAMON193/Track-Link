<?php

namespace App\Http\Controllers\API\Shipper;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shipper\PostJobRequest;
use App\Http\Resources\Shipper\PostJobDetailsResource;
use App\Models\JobPost;
use App\Traits\ApiResponse;

class PostJobController extends Controller
{
    use ApiResponse;


    /**
     * Post a new job
     */
    public function postJob(PostJobRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = auth()->id();

        // Get current year
        $year = now()->year;

        // Count how many jobs already posted this year
        $count = JobPost::whereYear('created_at', $year)->count() + 1;

        // Generate formatted job_id like JOB-2023-001
        $data['job_id'] = 'JOB-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        // Create the job post
        JobPost::create($data);

        return $this->sendResponse([], __('Job Posted Successfully'));
    }


    /**
     * Retrieve job post details
     */
    public function postJobDetails($id)
    {
        $job = JobPost::find($id);

        if (!$job) {
            return $this->sendError('Job not found', [], 404);
        }

        return $this->sendResponse(
            new PostJobDetailsResource($job),
            'Job post details retrieved successfully.'
        );
    }

}
