<?php

namespace App\Modules\Project\Application\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(public array $projectData, public readonly string $userName, public readonly string $ip, public readonly string $route) {}
}
