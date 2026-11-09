<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'user' => $this->user->name,
            'is_completed' => (int) $this->is_completed,
            'start_at' => $this->start_at?->format('Y-m-d H:i:s'),
            'expired_at' => $this->expired_at?->format('Y-m-d H:i:s'),
        ];
    }
}
