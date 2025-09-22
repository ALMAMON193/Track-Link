<?php

namespace App\Http\Resources\Shipper;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrowseTrackerDetailsResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'about' => $this->personalInformation->about ?? null,
            'ratting' => [
                'rating' => $this->rating ?? 0,
                'review_count' => $this->review_count ?? 0,
                'success_rate' => $this->success_rate ?? 0,
            ],
            'personal_information' => [
                'phone' => $this->personalInformation->phone ?? null,
                'email' => $this->email ?? null,
            ],
            'driving_credentials' => [
                'license_number' => $this->driverDetail->license_number ?? null,
                'state_of_issue' => $this->driverDetail->state_of_issue ?? null,
                'expiration_date' => $this->driverDetail->expiration_date ?? null,
                'expiration_image' =>  asset( $this->driverDetail->driver_license) ?? '',
                'is_valid' => $this->driverDetail->expiration_date > now(),
            ],
            'driving_experience' => $this->experiencePreference->experience ?? null,
            'vehicle_type' => $this->experiencePreference->vehicle_type ?? null,
            'service_area' => $this->experiencePreference->service_area ?? null,
            'additional_information' => $this->experiencePreference->additional_information ?? null,
            'availability' => $this->setAvailabilities->map(function ($availability) {
                return [
                    'date' => $availability->date,
                    'time' => $availability->time,
                    'is_available' => $availability->is_available,
                ];
            })->values(),
        ];
    }

}
