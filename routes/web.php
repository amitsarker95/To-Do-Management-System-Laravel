<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\ThemeController;


Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('todos.index')
        : view('welcome');
});


Route::middleware('auth')->group(function () {
    
    Route::resource('todos', TodoController::class)->except(['show']);

   
    Route::patch('/todos/{todo}/toggle', [TodoController::class, 'toggle'])
        ->name('todos.toggle');
    Route::delete('/todos', [TodoController::class, 'clearAll'])
        ->name('todos.clear');

    // Theme toggle (Light/Dark Mode with cookies)
    Route::post('/theme/toggle', [ThemeController::class, 'toggle'])
        ->name('theme.toggle');
});

Route::middleware('auth')->group(function () {
    Route::get('/ajax/todos', [TodoController::class, 'ajaxList'])->name('ajax.todos.list');
    Route::get('/ajax/todos/paginate', [TodoController::class, 'ajaxPaginate'])->name('ajax.todos.paginate');
    Route::post('/ajax/todos', [TodoController::class, 'ajaxStore'])->name('ajax.todos.store');
    Route::put('/ajax/todos/{todo}', [TodoController::class, 'ajaxUpdate'])->name('ajax.todos.update');
    Route::delete('/ajax/todos/{todo}', [TodoController::class, 'ajaxDelete'])->name('ajax.todos.delete');
    Route::post('/ajax/todos/{todo}/toggle', [TodoController::class, 'ajaxToggle'])->name('ajax.todos.toggle');
});

// Laravel authentication routes
require __DIR__.'/auth.php';
