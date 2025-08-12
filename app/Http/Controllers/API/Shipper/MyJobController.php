<?php

namespace App\Http\Controllers\API\Shipper;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\Patient\PatientListResource;
use App\Http\Resources\Shipper\MyJobResource;
use App\Models\JobPost;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyJobController extends Controller
{
    use ApiResponse;

    public function myJobs(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $myJobs = JobPost::where('delivery_status', '!=', 'Complete')
            ->latest()
            ->paginate($perPage);
        return $this->sendResponse(
            MyJobResource::collection($myJobs),
            __('Fetch My Jobs')
        );
    }
}
