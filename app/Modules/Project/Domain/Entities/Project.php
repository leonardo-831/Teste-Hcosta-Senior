<?php

namespace App\Modules\Project\Domain\Entities;

class Project
{
    public function __construct(
        public readonly int $id,
        public string $name,
        public int $ownerId
    ) {}
}