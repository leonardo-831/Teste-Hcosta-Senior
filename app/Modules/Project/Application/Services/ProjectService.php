<?php

namespace App\Modules\Project\Application\Services;

use App\Modules\Project\Application\Events\ProjectCreated;
use App\Modules\Project\Application\Events\ProjectUpdated;
use App\Modules\Project\Domain\Entities\Project;
use App\Modules\Project\Domain\Repositories\ProjectRepositoryInterface;
use App\Modules\Shared\Authorization\PermissionService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Event;

class ProjectService
{
    public function __construct(
        protected ProjectRepositoryInterface $repo,
        protected PermissionService $permissionService,
    ) {}

    public function list(int $userId): array
    {
        return $this->repo->findAllByUser($userId);
    }

    public function create(array $data, int $userId): Project
    {
        $project = new Project(
            id: 0,
            name: $data['name'],
            ownerId: $userId,
            description: $data['description'] ?? ''
        );

        $savedProject = $this->repo->save($project);

        Event::dispatch(new ProjectCreated(
            projectData: $savedProject->toArray(),
            userName: auth()->user()->name,
            ip: request()->ip(),
            route: request()->path()
        ));

        return $savedProject;
    }

    public function show(int $id): ?Project
    {
        return $this->repo->findById($id);
    }

    public function update(int $id, array $data, int $userId): ?Project
    {
        if (!$this->permissionService->canEditProject($userId, $id)) {
            throw new AuthorizationException('Você não tem permissão para editar este projeto.');
        }

        $project = $this->repo->findById($id);
        if (!$project) {
            throw new \Exception("Projeto não encontrado!");
        }

        $project->name = $data['name'];
        $project->description = $data['description'] ?? '';
        $project->ownerId = $data['owner_id'] ?? $userId;

        $savedProject = $this->repo->save($project);

        Event::dispatch(new ProjectUpdated(
            projectData: $savedProject->toArray(),
            userName: auth()->user()->name,
            ip: request()->ip(),
            route: request()->path(),
        ));

        return $savedProject;
    }

    public function delete(int $id, int $userId): void
    {
        if (!$this->permissionService->canDeleteProject($userId, $id)) {
            throw new AuthorizationException('Você não tem permissão para deletar este projeto.');
        }

        $this->repo->delete($id);
    }
}
