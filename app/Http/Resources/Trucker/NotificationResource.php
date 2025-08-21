<?php

namespace App\Http\Resources\Trucker;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'job_id'         => $this->data['job_id'] ?? null,
            'package_name'    => $this->data['package_name'] ?? null,
            'amount'         => intval ($this->data['amount']) ?? null,
            'message'        => $this->data['message'] ?? null,
            'is_read'        => (bool)$this->read_at,
            'read_at'        => $this->read_at,
            'created_at'     => $this->created_at->toDateTimeString(),
        ];
    }
}
