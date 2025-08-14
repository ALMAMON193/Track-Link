<?php

namespace App\Http\Resources\Trucker;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SetAvailabilityResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'date'         => $this->date->format('M j, Y'), // e.g., "Sep 25, 2024"
            'day'          => $this->date->format('l'), // e.g., "Monday"
            'time'         => $this->time->format('g:i A'), // e.g., "9:00 AM"
            'is_available' => $this->is_available,
        ];
    }
}
