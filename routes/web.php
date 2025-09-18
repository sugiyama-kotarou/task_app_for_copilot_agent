<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Task management routes
Route::resource('tasks', TaskController::class);

// Additional route for task completion
Route::patch('tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
