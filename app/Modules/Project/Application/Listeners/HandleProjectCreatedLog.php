<?php

namespace App\Modules\Project\Application\Listeners;

use App\Modules\Project\Application\Events\ProjectCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\Shared\Logging\Application\Services\LogDispatcher;

class HandleProjectCreatedLog implements ShouldQueue
{
    public string $queue = 'logs';

    public function handle(ProjectCreated $event): void
    {
        LogDispatcher::log([
            'usuario' => $event->userName,
            'acao' => 'project_created',
            'ip' => $event->ip,
            'rota' => $event->route,
            'payload' => $event->projectData,
        ]);
    }
}
