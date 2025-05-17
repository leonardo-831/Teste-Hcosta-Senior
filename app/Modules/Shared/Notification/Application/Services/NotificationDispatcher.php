<?php

namespace App\Modules\Shared\Notification\Application\Services;

use Illuminate\Support\Facades\Mail;
use App\Modules\Shared\Notification\Domain\Contracts\NotificationServiceInterface;
use App\Modules\Shared\Notification\Infrastructure\Mail\TaskAssignedMailable;
use App\Modules\Shared\Notification\Infrastructure\Mail\TaskCreatedMailable;

class NotificationDispatcher
{
    public function __construct(
        protected NotificationServiceInterface $service
    ) {}

    public static function sendTaskCreatedEmail(string $email, array $taskData): void
    {
        if($email) {
            Mail::to($email)->send(new TaskCreatedMailable($taskData));
        }
    }

    public static function sendTaskAssignedEmail(array $taskData): void
    {
        $email = $taskData['assigneeEmail'] ?? null;
        if ($email) {
            Mail::to($email)->send(new TaskAssignedMailable($taskData));
        }
    }
}
