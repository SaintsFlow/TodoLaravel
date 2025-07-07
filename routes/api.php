<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TodoController;

// Публичные маршруты для аутентификации
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

// Защищенные маршруты (требуют токен)
Route::middleware('auth:sanctum')->group(function () {
    // Аутентификация
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Todo маршруты
    Route::apiResource('todos', TodoController::class);

    // Дополнительные действия с Todo
    Route::patch('/todos/{id}/complete', [TodoController::class, 'complete']);
    Route::patch('/todos/{id}/archive', [TodoController::class, 'archive']);

    // Фильтрация Todo
    Route::get('/todos/status/{status}', [TodoController::class, 'byStatus']);
    Route::get('/todos/priority/{priority}', [TodoController::class, 'byPriority']);
    Route::get('/todos/assigned/me', [TodoController::class, 'assigned']);
    Route::get('/todos/created/me', [TodoController::class, 'created']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
