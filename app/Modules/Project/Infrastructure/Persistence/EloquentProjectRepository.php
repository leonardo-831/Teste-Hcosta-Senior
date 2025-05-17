<?php

namespace App\Modules\Project\Infrastructure\Persistence;

use App\Modules\Project\Domain\Entities\Project as DomainProject;
use App\Modules\Project\Domain\Repositories\ProjectRepositoryInterface;
use App\Modules\Project\Infrastructure\Models\Project as EloquentProject;

class EloquentProjectRepository implements ProjectRepositoryInterface
{
    public function findById(int $id): ?DomainProject
    {
        $model = EloquentProject::with('owner')->find($id);
        return $model ? $this->toDomain($model) : null;
    }

    public function findAllByUser(int $userId): array
    {
        return EloquentProject::with('owner')
            ->where('owner_id', $userId)
            ->get()
            ->map(fn ($model) => $this->toDomain($model))
            ->toArray();
    }

    public function save(DomainProject $project): DomainProject
    {
        $model = $project->id ? EloquentProject::find($project->id) : new EloquentProject();

        $model->name = $project->name;
        $model->description = $project->description;
        $model->owner_id = $project->ownerId;
        $model->save();

        return $this->toDomain($model->refresh());
    }

    public function delete(int $id): void
    {
        EloquentProject::destroy($id);
    }

    private function toDomain(EloquentProject $model): DomainProject
    {
        return new DomainProject(
            id: $model->id,
            name: $model->name,
            description: $model->description,
            ownerId: $model->owner_id,
            ownerName: $model->owner?->name,
            createdAt: $model->created_at,
            updatedAt: $model->updated_at,
        );
    }
}
