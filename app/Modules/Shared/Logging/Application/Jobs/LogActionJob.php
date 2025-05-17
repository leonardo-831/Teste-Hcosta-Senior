<?php

namespace App\Modules\Shared\Logging\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Modules\Shared\Logging\Application\Services\LogDispatcher;
use Illuminate\Foundation\Bus\Dispatchable;

class LogActionJob implements ShouldQueue
{
    use Dispatchable, Queueable, InteractsWithQueue, SerializesModels;

    public function __construct(
        public string $action,
        public string $model,
        public int $modelId,
        public array $payload = []
    ) {}

    public function handle(): void
    {
        LogDispatcher::log($this->action, $this->model, $this->modelId, $this->payload);
    }
}
