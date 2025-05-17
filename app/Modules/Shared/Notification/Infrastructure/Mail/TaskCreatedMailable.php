<?php

namespace App\Modules\Shared\Notification\Infrastructure\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskCreatedMailable extends Mailable
{
    use Queueable, SerializesModels;

    public array $taskData;

    public function __construct(array $taskData)
    {
        $this->taskData = $taskData;
    }

    public function build()
    {
        return $this->subject('Nova tarefa criada')
            ->view('emails.task-created')
            ->with(['task' => $this->taskData]);
    }
}
