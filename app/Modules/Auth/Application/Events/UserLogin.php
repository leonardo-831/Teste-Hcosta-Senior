<?php

namespace App\Modules\Auth\Application\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLogin
{
    use Dispatchable, SerializesModels;

    public function __construct(public array $data, public readonly string $userName, public readonly string $ip, public readonly string $route) {}
}
