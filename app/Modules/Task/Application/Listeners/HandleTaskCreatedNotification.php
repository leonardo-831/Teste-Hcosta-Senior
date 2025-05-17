<?php

namespace App\Modules\Task\Application\Listeners;

use App\Modules\Task\Application\Events\TaskCreated;
use App\Modules\Shared\Notification\Application\Services\NotificationDispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleTaskCreatedNotification implements ShouldQueue
{
    public string $queue = 'emails';

    public function handle(TaskCreated $event): void
    {
        $creatorEmail = $event->creatorEmail;
        $assigneeEmail = $event->taskData['assigneeEmail'] ?? null;

        NotificationDispatcher::sendTaskCreatedEmail($creatorEmail, $event->taskData);
        if (!empty($assigneeEmail) && $assigneeEmail !== $creatorEmail) {
            NotificationDispatcher::sendTaskAssignedEmail($assigneeEmail, $event->taskData);
        }
    }
}
