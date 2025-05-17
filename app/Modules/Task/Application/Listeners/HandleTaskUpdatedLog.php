<?php

namespace App\Modules\Task\Application\Listeners;

use App\Modules\Shared\Logging\Application\Services\LogDispatcher;
use App\Modules\Task\Application\Events\TaskUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleTaskUpdatedLog implements ShouldQueue
{
    public string $queue = 'logs';

    public function handle(TaskUpdated $event): void
    {
        LogDispatcher::log([
            'usuario' => $event->userName,
            'acao' => 'task_updated',
            'ip' => $event->ip,
            'rota' => $event->route,
            'payload' => $event->taskData,
        ]);
    }
}
