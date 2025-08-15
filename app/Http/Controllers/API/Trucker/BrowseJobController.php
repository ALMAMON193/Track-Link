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
    //apply job
    public function applyJob(Request $request)
    {
        $request->validate([
            'job_post_id' => 'required|exists:job_posts,id',
        ]);
        $user = Auth::user();
        $alreadyApplied = JobApplication::where('user_id', $user->id)
            ->where('job_post_id', $request->job_post_id)
            ->exists();
        if ($alreadyApplied) {
            return $this->sendError('Job already applied');
        }
        // new job request
         JobApplication::create([
            'user_id'      => $user->id,
            'job_post_id'  => $request->job_post_id,
            'status'       => 'applied',
        ]);
        return $this->sendResponse([],__('Applied for this job.'));
    }
}
