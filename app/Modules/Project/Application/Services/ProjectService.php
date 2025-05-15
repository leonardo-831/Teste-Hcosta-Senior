<?php

namespace App\Modules\Project\Application\Services;

use App\Modules\Project\Domain\Entities\Project;
use App\Modules\Project\Domain\Repositories\ProjectRepositoryInterface;

class ProjectService
{
    public function __construct(
        protected ProjectRepositoryInterface $repo
    ) {}

    public function list(int $userId): array
    {
        return $this->repo->findAllByUser($userId);
    }

    public function create(string $name, int $ownerId): Project
    {
        $project = new Project(id: 0, name: $name, ownerId: $ownerId);
        return $this->repo->save($project);
    }

    public function show(int $id): ?Project
    {
        return $this->repo->findById($id);
    }

    public function update(int $id, string $name, int $ownerId): ?Project
    {
        $project = new Project(id: $id, name: $name, ownerId: $ownerId);
        return $this->repo->save($project);
    }

    public function delete(int $id): void
    {
        $this->repo->delete($id);
    }
}