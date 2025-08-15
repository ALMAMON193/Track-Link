<?php

namespace App\Http\Resources\Shipper;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailsResource extends JsonResource
{
    protected $application;

    public function __construct($resource, $application = null)
    {
        parent::__construct($resource);
        $this->application = $application;
    }

    public function toArray(Request $request): array
    {
        return [
            'name'   => $this->name,
            'email'  => $this->email,
            'avatar' => $this->personalInformation && $this->personalInformation->avatar
                ? asset('storage/' . $this->personalInformation->avatar)
                : '',
            'driver_details' => $this->driverDetail ? [
                'license_number' => $this->driverDetail->license_number,
                'state_of_issue' => $this->driverDetail->state_of_issue,
                'expiration_date'=> $this->driverDetail->expiration_date,
            ] : [],

            'experience_preferences' => $this->experiencePreference ? [
                'experience' => $this->experiencePreference->experience,
                'vehicle_type'=> $this->experiencePreference->vehicle_type,
                'service_area'=> $this->experiencePreference->service_area,
                'additional_information'=> $this->experiencePreference->additional_information,
            ] : [],

            'applied_at' => $this->application?->created_at?->toDateTimeString(),
        ];
    }
}
