<?php

namespace App\Modules\Shared\Notification\Domain\Contracts;

interface NotificationServiceInterface
{
    public function sendTaskCreatedEmail(array $taskData): void;

    public function sendTaskAssignedEmail(array $taskData): void;
}
