<?php

namespace App\Modules\Project\Domain\Repositories;

use App\Modules\Project\Domain\Entities\Project;

interface ProjectRepositoryInterface
{
    public function findById(int $id): ?Project;
    public function findAllByUser(int $userId): array;
    public function save(Project $project): Project;
    public function delete(int $id): void;
}