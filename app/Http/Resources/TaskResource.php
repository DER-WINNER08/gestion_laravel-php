<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'status'      => $this->status,
            "owner"       => [
                "id"    => $this->user->id,
                "name"  => $this->user->name,
                "email" => $this->user->email,
            ],
            'created_at'  => $this->created_at->toIso8601String(),
        ];
    }
}
