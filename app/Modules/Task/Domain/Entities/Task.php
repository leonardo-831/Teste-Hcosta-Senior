<?php

namespace App\Modules\Task\Domain\Entities;

class Task
{
    public function __construct(
        public readonly int $id,
        public int $projectId,
        public string $name,
        public string $description,
        public string $status,
        public ?int $assigneeId,
        public int $creatorId
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'project_id' => $this->projectId,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'assignee_id' => $this->assigneeId,
            'creator_id' => $this->creatorId,
        ];
    }
}
