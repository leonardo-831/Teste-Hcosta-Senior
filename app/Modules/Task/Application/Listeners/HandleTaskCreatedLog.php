<?php

namespace App\Modules\Task\Application\Listeners;

use App\Modules\Task\Application\Events\TaskCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\Shared\Logging\Application\Services\LogDispatcher;

class HandleTaskCreatedLog implements ShouldQueue
{
    public string $queue = 'logs';

    public function handle(TaskCreated $event): void
    {
        LogDispatcher::log([
            'usuario' => $event->userName,
            'acao' => 'task_created',
            'ip' => $event->ip,
            'rota' => $event->route,
            'payload' => $event->taskData,
        ]);
    }
}
