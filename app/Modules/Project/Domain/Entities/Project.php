<?php

namespace App\Modules\Project\Domain\Entities;

use Carbon\Carbon;

class Project
{
    public function __construct(
        public readonly int $id,
        public int $ownerId,
        public string $name,
        public ?string $description = null,
        public ?string $ownerName = null,
        public ?Carbon $createdAt = null,
        public ?Carbon $updatedAt = null,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'ownerId' => $this->ownerId,
        ];
    }
}
