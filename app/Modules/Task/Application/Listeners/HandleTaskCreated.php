<?php

namespace App\Modules\Task\Application\Listeners;

use App\Modules\Task\Application\Events\TaskCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\Shared\Logging\Application\Services\LogDispatcher;
use App\Modules\Shared\Notification\Application\Services\NotificationDispatcher;

class HandleTaskCreated implements ShouldQueue
{
    public string $queue = 'logs';

    public function handle(TaskCreated $event): void
    {
        LogDispatcher::log('task_created', 'Task', $event->taskData['id'], $event->taskData);
        NotificationDispatcher::sendTaskCreatedEmail($event->taskData);

        if (!empty($event->taskData['assignee_id'])) {
            NotificationDispatcher::sendTaskAssignedEmail($event->taskData);
        }
    }
}
