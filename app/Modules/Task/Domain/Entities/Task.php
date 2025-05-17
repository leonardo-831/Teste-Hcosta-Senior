<?php

namespace App\Modules\Task\Domain\Entities;

use App\Modules\Task\Domain\ValueObjects\TaskStatus;
use Carbon\Carbon;

class Task
{
    public function __construct(
        public readonly int $id,
        public int $projectId,
        public string $name,
        public TaskStatus $status,
        public ?string $description = null,
        public ?int $assigneeId = null,
        public ?string $assigneeName = null,
        public ?string $assigneeEmail = null,
        public ?string $projectName = null,
        public ?Carbon $createdAt = null,
        public ?Carbon $updatedAt = null,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'project_id' => $this->projectId,
            'projectName' => $this->projectName,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status->toArray(),
            'assigneeId' => $this->assigneeId,
            'assigneeEmail' => $this->assigneeEmail
        ];
    }
}
