<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MathController;
use App\Http\Controllers\TaskController;

Route::get('/suma/{num1}/{num2}', [MathController::class, 'suma']);
Route::get('/mult/{num1}/{num2}', [MathController::class, 'mult']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
route::get('/tasks', [TaskController::class, 'index']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tasks/user/{id}', [TaskController::class, 'getTasksByUser']);
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
});