<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $email
 * @property mixed $user_type
 * @property mixed $phone_number
 * @property mixed $email_verified_at
 * @property mixed $created_at
 */
class RegisterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'email'          => $this->email,
            'user_type'      => $this->user_type,
            'email_verified' => (bool) $this->email_verified_at,
            'created_at'     => $this->created_at->toDateTimeString(),
        ];
    }
}
