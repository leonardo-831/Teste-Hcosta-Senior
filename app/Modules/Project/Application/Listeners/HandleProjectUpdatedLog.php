<?php

namespace App\Modules\Project\Application\Listeners;

use App\Modules\Shared\Logging\Application\Services\LogDispatcher;
use App\Modules\Project\Application\Events\ProjectUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleTaskUpdatedLog implements ShouldQueue
{
    public string $queue = 'logs';

    public function handle(ProjectUpdated $event): void
    {
        LogDispatcher::log([
            'usuario' => $event->userName,
            'acao' => 'project_updated',
            'ip' => $event->ip,
            'rota' => $event->route,
            'payload' => $event->projectData,
        ]);
    }
}
