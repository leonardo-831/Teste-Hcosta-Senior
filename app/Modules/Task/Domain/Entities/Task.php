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
}
