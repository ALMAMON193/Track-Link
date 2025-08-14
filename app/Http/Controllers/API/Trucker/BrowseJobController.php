<?php

namespace App\Http\Controllers\API\Trucker;

use App\Http\Controllers\Controller;
use App\Http\Resources\Shipper\MyJobResource;
use App\Http\Resources\Trucker\BrowseJobResource;
use App\Models\JobPost;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class BrowseJobController extends Controller
{
    use ApiResponse;
    public function browseJob(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $myJobs = JobPost::where('delivery_status', '!=', 'Complete')
            ->latest()
            ->paginate($perPage);
        return $this->sendResponse(
            BrowseJobResource::collection($myJobs),
            __('Fetch Browse Jobs')
        );
    }
}
