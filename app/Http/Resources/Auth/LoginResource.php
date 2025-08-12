<?php

namespace App\Http\Resources\Auth;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $email
 * @property mixed $user_type
 * @property mixed $is_verified
 * @property mixed $verified_at
 */
class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'user_type' => $this->user_type ?? 'N/A',
            'is_verified' => $this->is_verified ?? false,
            'verified_at' => $this->verified_at instanceof \Carbon\Carbon
                ? $this->verified_at->toDateTimeString()
                : ($this->verified_at ? Carbon::parse($this->verified_at)->toDateTimeString() : 'N/A'),
        ];
    }
}
