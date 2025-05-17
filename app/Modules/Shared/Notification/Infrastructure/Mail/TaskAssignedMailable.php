<?php

namespace App\Modules\Shared\Notification\Infrastructure\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskAssignedMailable extends Mailable
{
    use Queueable, SerializesModels;

    public array $taskData;

    public function __construct(array $taskData)
    {
        $this->taskData = $taskData;
    }

    public function build()
    {
        return $this->subject('Você foi atribuído a uma tarefa')
            ->view('emails.task-assigned')
            ->with(['task' => $this->taskData]);
    }
}
