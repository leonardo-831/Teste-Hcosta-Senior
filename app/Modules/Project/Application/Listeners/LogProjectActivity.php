<?php

namespace App\Modules\Project\Application\Listeners;

use App\Modules\Project\Application\Events\ProjectCreated;
use App\Modules\Shared\Logging\Application\Services\LogDispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogProjectActivity implements ShouldQueue
{
    public function handle(ProjectCreated $event)
    {
        LogDispatcher::log('project_created', 'Project', $event->projectData['id'], $event->projectData);
    }
}
