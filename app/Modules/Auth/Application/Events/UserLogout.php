<?php

namespace App\Modules\Auth\Application\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLogout
{
    use Dispatchable, SerializesModels;

    public function __construct(public array $data, public readonly string $userName, public readonly string $ip, public readonly string $route) {}
}
