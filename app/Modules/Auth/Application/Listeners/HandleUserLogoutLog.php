<?php

namespace App\Modules\Auth\Application\Listeners;

use App\Modules\Auth\Application\Events\UserLogout;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\Shared\Logging\Application\Services\LogDispatcher;

class HandleUserLogoutLog implements ShouldQueue
{
    public string $queue = 'logs';

    public function handle(UserLogout $event): void
    {
        LogDispatcher::log([
            'usuario' => $event->userName,
            'acao' => 'logout',
            'ip' => $event->ip,
            'rota' => $event->route,
            'payload' => $event->data,
        ]);
    }
}
