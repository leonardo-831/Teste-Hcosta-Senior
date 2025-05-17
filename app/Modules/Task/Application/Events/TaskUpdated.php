<?php

namespace App\Modules\Task\Application\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(public array $taskData, public readonly string $userName, public readonly string $ip, public readonly string $route, public ?int $oldAssigneeId = null) {}
}
