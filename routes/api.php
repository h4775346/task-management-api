<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\TaskDependenciesController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\TaskSearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Health check
Route::get('health', [HealthController::class, 'index']);

// Auth routes
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

// Protected routes
Route::group(['middleware' => 'auth:api'], function () {
    // Tasks routes
    Route::apiResource('tasks', TasksController::class)->except(['index']);
    Route::patch('tasks/{task}/status', [TasksController::class, 'updateStatus']);

    // Task dependencies routes
    Route::get('tasks/{task}/dependencies', [TaskDependenciesController::class, 'index']);
    Route::post('tasks/{task}/dependencies', [TaskDependenciesController::class, 'store']);
    Route::delete('tasks/{task}/dependencies/{dependsOnTask}', [TaskDependenciesController::class, 'destroy']);

    // Task search route
    Route::post('tasks/search', TaskSearchController::class);
});
