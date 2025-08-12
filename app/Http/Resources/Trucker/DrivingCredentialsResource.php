<?php

namespace App\Http\Resources\Trucker;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DrivingCredentialsResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->id,
            'license_number'   => $this->license_number,
            'state_of_issue'   => $this->state_of_issue,
            'expiration_date'  => $this->expiration_date,
            'driver_license'   => $this->driver_license ? asset('storage/' . $this->driver_license) : null,
        ];
    }
}
