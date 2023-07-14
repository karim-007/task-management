<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'task_id' => $this->task_id,
            'comment' => $this->comment,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'ip' => $this->ip,
            'browser' => $this->browser,
        ];
    }
}
