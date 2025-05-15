<?php

namespace App\Modules\Auth\Domain\Entities;

class User
{
    public function __construct(
        public readonly int $id,
        public string $name,
        public string $email
    ) {}
}
