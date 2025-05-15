<?php

namespace App\Modules\Task\Domain\Repositories;

use App\Modules\Task\Domain\Entities\Task;

interface TaskRepositoryInterface
{
    public function findById(int $id): ?Task;
    public function findAllByProject(int $projectId): array;
    public function save(Task $task): Task;
    public function delete(int $id): void;
}
