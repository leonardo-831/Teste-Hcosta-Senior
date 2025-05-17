<?php

namespace App\Modules\Task\Application\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Task\Domain\Entities\Task;

/**
 * @property Task $resource
 */
class TaskResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'project' => $this->resource->projectName,
            'name' => $this->resource->name,
            'description' => $this->resource->description,
            'status' => $this->resource->status->name(),
            'assignee' => $this->resource->assigneeName,
            'created_at' => optional($this->resource->createdAt)?->format('d/m/Y H:i'),
            'updated_at' => optional($this->resource->updatedAt)?->format('d/m/Y H:i'),
        ];
    }
}
