<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
