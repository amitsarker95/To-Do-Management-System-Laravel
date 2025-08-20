<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\ThemeController;

// Redirect home to todos if logged in
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('todos.index')
        : view('welcome');
});

// Auth-protected routes
Route::middleware('auth')->group(function () {
    // Resourceful routes (CRUD) for todos, except "show"
    Route::resource('todos', TodoController::class)->except(['show']);

    // Extra Todo features
    Route::patch('/todos/{todo}/toggle', [TodoController::class, 'toggle'])
        ->name('todos.toggle');
    Route::delete('/todos', [TodoController::class, 'clearAll'])
        ->name('todos.clear');

    // Theme toggle (Light/Dark Mode with cookies)
    Route::post('/theme/toggle', [ThemeController::class, 'toggle'])
        ->name('theme.toggle');
});

// Laravel authentication routes (Breeze/UI)
require __DIR__.'/auth.php';
