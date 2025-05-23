<?php

namespace App\Modules\Task\Application\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(public array $taskData, public readonly string $userName, public readonly string $creatorEmail, public readonly string $ip, public readonly string $route) {}
}
