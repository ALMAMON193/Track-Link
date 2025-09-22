<?php

namespace App\Http\Controllers\API\Shipper;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shipper\BrowseTrackerRequest;
use App\Http\Resources\Shipper\BrowseTrackerDetailsResource;
use App\Models\HireRequest;
use App\Models\User;
use App\Notifications\TrackerHiredNotification;
use App\Traits\ApiResponse;
use App\Http\Resources\Shipper\BrowseTrackerResource;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BrowseTrackerController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10); // Default 10 per page

        $drivers = User::with(['experiencePreference', 'driverDetail'])
            ->where('user_type', 'trucker')
            ->where('status', 'verified')
            ->paginate($perPage);

        return $this->sendResponse([
            'data' => BrowseTrackerResource::collection($drivers),
            'meta' => [
                'current_page' => $drivers->currentPage(),
                'last_page'    => $drivers->lastPage(),
                'per_page'     => $drivers->perPage(),
                'total'        => $drivers->total(),
            ]
        ], __('Browse Trackers successfully fetched.'));

    }


    public function show($id)
    {
        $driver = User::with(['experiencePreference', 'driverDetail','personalInformation' ,'setAvailabilities'])
            ->where('user_type', 'trucker')
            ->where('status', 'verified')
            ->findOrFail($id);
        return $this->sendResponse(new BrowseTrackerDetailsResource($driver),__('Browse Tracker details successfully fetched.'));
    }

    //hire tracker request
    public function sendHireRequest(Request $request)
    {
        $request->validate([
            'tracker_id' => 'required|exists:users,id',
            'date' => 'required|date',          // requested work date
            'reason' => 'nullable|string',      // optional short description/reason
        ]);

        $tracker = User::findOrFail($request->tracker_id);

        // Check if a pending hire request already exists for this tracker and date
        $existingRequest = HireRequest::where('tracker_id', $tracker->id)
            ->where('date', $request->date)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return $this->sendError('A hire request for this tracker on the selected date is already pending.');
        }

        // Create the hire request
        $hireRequest = HireRequest::create([
            'tracker_id' => $tracker->id,
            'shipper_id' => auth()->id(),
            'date' => $request->date,
            'reason' => $request->reason,
            'expires_at' => Carbon::now()->addHours(24), // 24 hours to accept
        ]);

        // Notify tracker
        $tracker->notify(new TrackerHiredNotification($hireRequest));

        return $this->sendResponse(
            new BrowseTrackerResource($tracker),
            __('Hire request successfully sent.')
        );
    }
}
