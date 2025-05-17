<?php

namespace App\Providers;

use App\Modules\Task\Application\Events\TaskCreated;
use App\Modules\Task\Application\Events\TaskUpdated;
use App\Modules\Task\Application\Listeners\HandleTaskCreated;
use App\Modules\Task\Application\Listeners\HandleTaskUpdated;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $listen = [
        TaskCreated::class => [
            HandleTaskCreated::class,
        ],
        TaskUpdated::class => [
            HandleTaskUpdated::class,
        ],
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Modules\Project\Domain\Repositories\ProjectRepositoryInterface::class,
            \App\Modules\Project\Infrastructure\Persistence\EloquentProjectRepository::class
        );

        $this->app->bind(
            \App\Modules\Task\Domain\Repositories\TaskRepositoryInterface::class,
            \App\Modules\Task\Infrastructure\Persistence\EloquentTaskRepository::class
        );

        $this->app->bind(
            \App\Modules\Auth\Domain\Repositories\UserRepositoryInterface::class,
            \App\Modules\Auth\Infrastructure\Persistence\EloquentUserRepository::class
        );

        $this->app->bind(
            \App\Modules\Shared\Logging\Domain\Contracts\LoggingRepositoryInterface::class,
            \App\Modules\Shared\Logging\Infrastructure\Repositories\MongoLoggingRepository::class
        );
    }

    public function boot(): void
    {
        $this->registerListeners();
    }

    protected function registerListeners(): void
    {
        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }
}
