<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Task\Interfaces\Http\Controllers\TaskController;

Route::middleware('jwt.auth')->group(function () {
    Route::prefix('projects/{projectId}/tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index']);
        Route::post('/', [TaskController::class, 'store']);
    });

    Route::prefix('tasks')->group(function () {
        Route::get('/{id}', [TaskController::class, 'show']);
        Route::put('/{id}', [TaskController::class, 'update']);
        Route::delete('/{id}', [TaskController::class, 'destroy']);
    });
});
