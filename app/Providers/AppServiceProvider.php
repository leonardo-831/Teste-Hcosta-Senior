<?php

namespace App\Providers;

use App\Modules\Task\Application\Events\TaskCreated;
use App\Modules\Task\Application\Events\TaskUpdated;
use App\Modules\Task\Application\Listeners\HandleTaskCreatedLog;
use App\Modules\Task\Application\Listeners\HandleTaskCreatedNotification;
use App\Modules\Task\Application\Listeners\HandleTaskUpdatedLog;
use App\Modules\Task\Application\Listeners\HandleTaskUpdatedNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $listen = [
        TaskCreated::class => [
            HandleTaskCreatedLog::class,
            HandleTaskCreatedNotification::class,
        ],
        TaskUpdated::class => [
            HandleTaskUpdatedLog::class,
            HandleTaskUpdatedNotification::class,
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

        $this->app->bind(\App\Modules\Shared\Authorization\PermissionService::class, function ($app) {
            return new \App\Modules\Shared\Authorization\PermissionService(
                $app->make(\App\Modules\Project\Domain\Repositories\ProjectRepositoryInterface::class),
                $app->make(\App\Modules\Task\Domain\Repositories\TaskRepositoryInterface::class),
            );
        });
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
