<?php

namespace App\Http\Resources\Trucker;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonalInformationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = $this->user ?: auth()->user();
        return [
            'id' => $this->id ?? ($user ? $user->id : null),
            'name' => $user ? $user->name : '',
            'email' => $user ? $user->email : '',
            'city' => $this->city ?? '',
            'address' => $this->address ?? '',
            'phone' => $this->phone ?? '',
            'about' => $this->about ?? '',
            'avatar' => $this->avatar ? asset('storage/' . $this->avatar) : '',
        ];
    }
}
