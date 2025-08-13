<?php

namespace App\Http\Controllers\API\Trucker;

use App\Http\Controllers\Controller;
use App\Http\Resources\Trucker\SetAvailabilityResource;
use App\Models\SetAvailability;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class SetAvailabilityController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $availabilities = SetAvailability::where('user_id', auth()->id())
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        return $this->sendResponse(
            SetAvailabilityResource::collection($availabilities),
            'Availability list fetched successfully'
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date'         => 'required|date',
            'time'         => 'required|date_format:H:i',
            'is_available' => 'required|boolean',
        ]);

        $validated['user_id'] = auth()->id();

        $availability = SetAvailability::updateOrCreate(
            [
                'user_id' => $validated['user_id'],
                'date'    => $validated['date'],
                'time'    => $validated['time']
            ],
            ['is_available' => $validated['is_available']]
        );

        return $this->sendResponse(
            new SetAvailabilityResource($availability),
            'Availability saved successfully'
        );
    }
}
