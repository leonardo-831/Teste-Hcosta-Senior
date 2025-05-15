<?php

namespace App\Modules\Auth\Infrastructure\Persistence;

use App\Modules\Auth\Domain\Repositories\UserRepositoryInterface;
use App\Modules\Auth\Domain\Entities\User as DomainUser;
use App\Modules\Auth\Infrastructure\Models\User as EloquentUser;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?DomainUser
    {
        $user = EloquentUser::find($id);
        return $user ? $this->toDomain($user) : null;
    }

    public function findByEmail(string $email): ?DomainUser
    {
        $user = EloquentUser::where('email', $email)->first();
        return $user ? $this->toDomain($user) : null;
    }

    public function create(array $data): DomainUser
    {
        $user = EloquentUser::create($data);
        return $this->toDomain($user);
    }

    private function toDomain(EloquentUser $user): DomainUser
    {
        return new DomainUser(
            id: $user->id,
            name: $user->name,
            email: $user->email
        );
    }
}
