<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Project\Interfaces\Http\Controllers\ProjectController;

Route::middleware('jwt.auth')->group(function () {
    Route::prefix('projects')->group(function () {
        Route::get('/', [ProjectController::class, 'index']);
        Route::post('/', [ProjectController::class, 'store']);
        Route::get('/{id}', [ProjectController::class, 'show']);
        Route::put('/{id}', [ProjectController::class, 'update']);
        Route::delete('/{id}', [ProjectController::class, 'destroy']);
    });
});