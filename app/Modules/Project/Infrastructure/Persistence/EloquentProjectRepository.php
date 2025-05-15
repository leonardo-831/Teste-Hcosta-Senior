<?php

namespace App\Modules\Project\Infrastructure\Persistence;

use App\Modules\Project\Domain\Entities\Project as DomainProject;
use App\Modules\Project\Domain\Repositories\ProjectRepositoryInterface;
use App\Modules\Project\Infrastructure\Models\Project as EloquentProject;

class EloquentProjectRepository implements ProjectRepositoryInterface
{
    public function findById(int $id): ?DomainProject
    {
        $model = EloquentProject::find($id);
        return $model ? new DomainProject($model->id, $model->name, $model->owner_id) : null;
    }

    public function findAllByUser(int $userId): array
    {
        return EloquentProject::where('owner_id', $userId)
            ->get()
            ->map(fn ($model) => new DomainProject($model->id, $model->name, $model->owner_id))
            ->toArray();
    }

    public function save(DomainProject $project): DomainProject
    {
        $model = $project->id ? EloquentProject::find($project->id) : new EloquentProject();
        $model->name = $project->name;
        $model->owner_id = $project->ownerId;
        $model->save();

        return new DomainProject($model->id, $model->name, $model->owner_id);
    }

    public function delete(int $id): void
    {
        EloquentProject::destroy($id);
    }
}