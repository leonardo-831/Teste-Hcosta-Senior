<?php

namespace App\Modules\Project\Application\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Project\Domain\Entities\Project;

/**
 * @property Project $resource
 */
class ProjectResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'description' => $this->resource->description,
            'owner' => $this->resource->ownerName,
            'created_at' => optional($this->resource->createdAt)?->format('d/m/Y H:i'),
            'updated_at' => optional($this->resource->updatedAt)?->format('d/m/Y H:i'),
        ];
    }
}
