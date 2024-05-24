<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;

// Users
Route::get('users', [UserController::class, 'index']);
Route::get('users/{user}', [UserController::class, 'show']);
Route::get('users/email/{email}', [UserController::class, 'getUserByEmail']);
Route::post('users', [UserController::class, 'store']);
Route::put('users/{user}', [UserController::class, 'update']);
Route::delete('users/{user}', [UserController::class, 'delete']);

// Tasks
Route::get('tasks', [TaskController::class, 'index']);
Route::get('tasks/unassigned', [TaskController::class, 'getUnassignedTasks']);
Route::get('tasks/{task}', [TaskController::class, 'show']);
Route::get('tasks/user/{user}', [TaskController::class, 'getUserTasks']);
Route::post('tasks', [TaskController::class, 'store']);
Route::put('tasks/{task}', [TaskController::class, 'update']);
Route::delete('tasks/{task}', [TaskController::class, 'delete']);
