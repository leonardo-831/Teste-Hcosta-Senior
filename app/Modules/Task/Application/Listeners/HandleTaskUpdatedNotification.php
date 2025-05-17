<?php

namespace App\Modules\Task\Application\Listeners;

use App\Modules\Shared\Notification\Application\Services\NotificationDispatcher;
use App\Modules\Task\Application\Events\TaskUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleTaskUpdatedNotification implements ShouldQueue
{
    public string $queue = 'emails';

    public function handle(TaskUpdated $event): void
    {
        $newAssigneeId = $event->taskData['assigneeId'] ?? null;
        $oldAssigneeId = $event->oldAssigneeId;

        if (!empty($newAssigneeId) && $newAssigneeId !== $oldAssigneeId) {
            NotificationDispatcher::sendTaskAssignedEmail($event->taskData);
        }
    }
}
