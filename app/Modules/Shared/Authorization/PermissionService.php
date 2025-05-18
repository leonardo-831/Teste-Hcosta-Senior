<?php

namespace App\Modules\Shared\Authorization;

use App\Modules\Project\Domain\Repositories\ProjectRepositoryInterface;
use App\Modules\Task\Domain\Repositories\TaskRepositoryInterface;

class PermissionService
{
    public function __construct(
        protected ProjectRepositoryInterface $projectRepo,
        protected TaskRepositoryInterface $taskRepo,
    ) {}

    public function canEditProject(int $userId, int $projectId): bool
    {
        $project = $this->projectRepo->findById($projectId);
        if (!$project) {
            return false;
        }

        return $project->ownerId === $userId;
    }

    public function canDeleteProject(int $userId, int $projectId): bool
    {
        return $this->canEditProject($userId, $projectId);
    }

    public function canCreateTask(int $userId, int $projectId): bool
    {
        return $this->canEditProject($userId, $projectId);
    }

    public function canUpdateTask(int $userId, int $taskId): bool
    {
        $task = $this->taskRepo->findById($taskId);
        if (!$task) {
            return false;
        }

        $project = $this->projectRepo->findById($task->projectId);
        if (!$project) {
            return false;
        }

        return $project->ownerId === $userId || $task->assigneeId === $userId;
    }

    public function canDeleteTask(int $userId, int $taskId): bool
    {
        $task = $this->taskRepo->findById($taskId);
        if (!$task) {
            return false;
        }

        $project = $this->projectRepo->findById($task->projectId);
        if (!$project) {
            return false;
        }

        return $project->ownerId === $userId;
    }
}
