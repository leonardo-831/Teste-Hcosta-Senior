<?php

namespace App\Modules\Task\Application\Services;

use App\Modules\Task\Domain\Repositories\TaskRepositoryInterface;
use App\Modules\Task\Domain\Entities\Task;
use App\Modules\Task\Application\Events\TaskCreated;
use App\Modules\Task\Domain\ValueObjects\TaskStatus;
use Illuminate\Support\Facades\Event;
use App\Modules\Shared\Authorization\PermissionService;
use App\Modules\Task\Application\Events\TaskUpdated;
use Illuminate\Auth\Access\AuthorizationException;

class TaskService
{
    public function __construct(
        protected TaskRepositoryInterface $repo,
        protected PermissionService $permissionService,
    ) {}

    public function list(int $projectId): array
    {
        return $this->repo->findAllByProject($projectId);
    }

    public function create(array $data, int $projectId, int $userId): Task
    {
        if (!$this->permissionService->canEditProject($userId, $projectId)) {
            throw new AuthorizationException("Você não tem permissão para criar uma task neste projeto.");
        }

        $task = new Task(
            id: 0,
            projectId: $projectId,
            name: $data['name'],
            description: $data['description'] ?? '',
            status: new TaskStatus($data['status']),
            assigneeId: $data['assigneeId'] ?? null,
        );

        $savedTask = $this->repo->save($task);

        Event::dispatch(new TaskCreated(
            taskData: $savedTask->toArray(),
            userName: auth()->user()->name,
            creatorEmail: auth()->user()->email,
            ip: request()->ip(),
            route: request()->path()
        ));

        return $savedTask;
    }

    public function show(int $id): ?Task
    {
        return $this->repo->findById($id);
    }

    public function update(int $id, array $data, int $userId): ?Task
    {
        if (!$this->permissionService->canUpdateTask($userId, $id)) {
            throw new AuthorizationException('Você não tem permissão para editar essa task!');
        }

        $task = $this->repo->findById($id);
        if (!$task) {
            throw new \Exception("Tarefa não encontrada!");
        }

        $oldAssigneeId = $task->assigneeId;

        $task->name = $data['name'];
        $task->description = $data['description'] ?? '';
        $task->status = new TaskStatus($data['status']);
        $task->assigneeId = $data['assigneeId'] ?? null;

        $savedTask = $this->repo->save($task);

        Event::dispatch(new TaskUpdated(
            taskData: $savedTask->toArray(),
            userName: auth()->user()->name,
            ip: request()->ip(),
            route: request()->path(),
            oldAssigneeId: $oldAssigneeId
        ));

        return $savedTask;
    }

    public function delete(int $id, int $userId): void
    {
        if (!$this->permissionService->canDeleteTask($userId, $id)) {
            throw new AuthorizationException('Você não tem permissão para deletar essa task!');
        }

        $this->repo->delete($id);
    }
}
