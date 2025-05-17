<?php

namespace App\Modules\Auth\Application\Listeners;

use App\Modules\Auth\Application\Events\UserLogin;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\Shared\Logging\Application\Services\LogDispatcher;

class HandleUserLoginLog implements ShouldQueue
{
    public string $queue = 'logs';

    public function handle(UserLogin $event): void
    {
        LogDispatcher::log([
            'usuario' => $event->userName,
            'acao' => 'login',
            'ip' => $event->ip,
            'rota' => $event->route,
            'payload' => $event->data,
        ]);
    }
}
