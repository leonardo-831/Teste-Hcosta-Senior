<?php

namespace App\Modules\Shared\Notification\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Modules\Shared\Notification\Application\Services\NotificationDispatcher;
use Illuminate\Foundation\Bus\Dispatchable;

class SendTaskCreatedEmailJob implements ShouldQueue
{
    use Dispatchable, Queueable, InteractsWithQueue, SerializesModels;

    public function __construct(public array $taskData) {}

    public function handle(): void
    {
        NotificationDispatcher::sendTaskCreatedEmail($this->taskData);
    }
}
