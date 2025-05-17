<?php

namespace App\Modules\Task\Application\Listeners;

use App\Modules\Shared\Logging\Application\Jobs\LogActionJob;
use App\Modules\Shared\Notification\Application\Jobs\SendTaskAssignedEmailJob;
use App\Modules\Shared\Notification\Application\Jobs\SendTaskCreatedEmailJob;
use App\Modules\Task\Application\Events\TaskUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleTaskUpdated implements ShouldQueue
{
    public function handle(TaskUpdated $event): void
    {
        $taskData = $event->taskData;

        LogActionJob::dispatch(
            action: 'task_created',
            model: 'Task',
            modelId: $taskData['id'],
            payload: $taskData
        )->onQueue('logs');

        SendTaskCreatedEmailJob::dispatch($taskData)->onQueue('emails');

        if (!empty($taskData['assignee_id'])) {
            SendTaskAssignedEmailJob::dispatch($taskData)->onQueue('emails');
        }
    }
}
