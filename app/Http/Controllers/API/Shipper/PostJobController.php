<?php

namespace App\Http\Controllers\API\Shipper;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shipper\PostJobRequest;
use App\Http\Resources\Shipper\PostJobDetailsResource;
use App\Http\Resources\Shipper\PostJobResource;
use App\Models\JobPost;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class PostJobController extends Controller
{
    use ApiResponse;
    public function postJob(PostJobRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = auth()->id();
        // Get current year
        $year = now()->year;
        // Count how many jobs already posted in this year
        $count = JobPost::whereYear('created_at', $year)->count() + 1;
        // Generate formatted job_id like JOB-2023-001
        $data['job_id'] = 'JOB-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        JobPost::create($data);
        return $this->sendResponse ([],__('Job Posted Successfully'));
    }
    public function postJobDetails(Request $request, $id)
    {
        $job = JobPost::findOrFail($id);
        return $this->sendResponse(
            new PostJobDetailsResource($job),
            'Job post details retrieved successfully.'
        );
    }
}
