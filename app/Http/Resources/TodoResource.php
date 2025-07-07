<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TodoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'color' => $this->color,
            'priority' => [
                'value' => $this->priority?->name,
                'label' => $this->priority?->label()
            ],
            'status' => [
                'value' => $this->status?->name,
                'label' => $this->status?->label()
            ],
            'is_completed' => $this->is_completed ?? false,
            'is_archived' => $this->is_archived ?? false,
            'is_private' => $this->is_private ?? false,
            'due_date' => $this->due_date?->format('Y-m-d H:i:s'),
            'completed_at' => $this->completed_at?->format('Y-m-d H:i:s'),
            'archived_at' => $this->archived_at?->format('Y-m-d H:i:s'),
            'author' => new UserResource($this->whenLoaded('author')),
            'assigned_to' => new UserResource($this->whenLoaded('assignedTo')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ];
    }
}
