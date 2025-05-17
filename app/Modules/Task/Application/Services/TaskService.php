<?php

namespace App\Modules\Task\Application\Services;

use App\Modules\Task\Domain\Repositories\TaskRepositoryInterface;
use App\Modules\Task\Domain\Entities\Task;
use App\Modules\Task\Application\Events\TaskCreated;
use Illuminate\Support\Facades\Event;

class TaskService
{
    public function __construct(
        protected TaskRepositoryInterface $repo,
    ) {}

    public function list(int $projectId): array
    {
        return $this->repo->findAllByProject($projectId);
    }

    public function create(array $data, int $projectId, int $creatorId): Task
    {
        $task = new Task(
            id: 0,
            projectId: $projectId,
            name: $data['name'],
            description: $data['description'] ?? '',
            status: $data['status'],
            assigneeId: $data['assignee_id'] ?? null,
            creatorId: $creatorId
        );

        $savedTask = $this->repo->save($task);

        Event::dispatch(new TaskCreated($savedTask->toArray()));

        return $savedTask;
    }

    public function show(int $id): ?Task
    {
        return $this->repo->findById($id);
    }

    public function update(int $id, array $data, int $requesterId): ?Task
    {
        $task = $this->repo->findById($id);
        if (!$task) return null;

        $task->name = $data['name'];
        $task->description = $data['description'] ?? '';
        $task->status = $data['status'];
        $task->assigneeId = $data['assignee_id'] ?? null;

        $savedTask = $this->repo->save($task);

        Event::dispatch(new TaskCreated($savedTask->toArray()));

        return $savedTask;
    }

    public function delete(int $id): void
    {
        $this->repo->delete($id);
    }
}
