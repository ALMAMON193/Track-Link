<?php

namespace App\Http\Resources\Shipper;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrowseTrackerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                        => $this->id,
            'name'                      => $this->name,
            'experience'                => $this->experiencePreference->experience ?? null,
            'vehicle_type'              => $this->experiencePreference->vehicle_type ?? null,
            'service_area'              => $this->experiencePreference->service_area ?? null,
            'state_of_issue'            => $this->driverDetail->state_of_issue ?? null,
            'available_now'             => $this->status === 'verified',
            'additional_information'    => $this->experiencePreference->additional_information ?? null,
            'avatar'                    =>$this->personalInformation->avatar ? Helper::generateURL($this->personalInformation->avatar) : '',
        ];
    }
}
