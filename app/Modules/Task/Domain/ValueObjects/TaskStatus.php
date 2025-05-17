<?php

namespace App\Modules\Task\Domain\ValueObjects;

final class TaskStatus
{
    public function __construct(
        private readonly int $id,
        private ?string $name = null
    ){}

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function equals(TaskStatus $other): bool
    {
        return $this->id === $other->id;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
