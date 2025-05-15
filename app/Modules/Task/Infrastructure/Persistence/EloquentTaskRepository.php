<?php

namespace App\Modules\Task\Infrastructure\Persistence;

use App\Modules\Task\Domain\Entities\Task as DomainTask;
use App\Modules\Task\Domain\Repositories\TaskRepositoryInterface;
use App\Modules\Task\Infrastructure\Models\Task as EloquentTask;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function findById(int $id): ?DomainTask
    {
        $model = EloquentTask::find($id);
        return $model ? $this->toDomain($model) : null;
    }

    public function findAllByProject(int $projectId): array
    {
        return EloquentTask::where('project_id', $projectId)
            ->get()
            ->map(fn ($task) => $this->toDomain($task))
            ->toArray();
    }

    public function save(DomainTask $task): DomainTask
    {
        $model = $task->id ? EloquentTask::find($task->id) : new EloquentTask();
        $model->project_id = $task->projectId;
        $model->name = $task->name;
        $model->description = $task->description;
        $model->status = $task->status;
        $model->assignee_id = $task->assigneeId;
        $model->creator_id = $task->creatorId;
        $model->save();

        return $this->toDomain($model);
    }

    public function delete(int $id): void
    {
        EloquentTask::destroy($id);
    }

    private function toDomain(EloquentTask $model): DomainTask
    {
        return new DomainTask(
            id: $model->id,
            projectId: $model->project_id,
            name: $model->name,
            description: $model->description,
            status: $model->status,
            assigneeId: $model->assignee_id,
            creatorId: $model->creator_id
        );
    }
}
