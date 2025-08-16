<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\ThemeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/todos', [TodoController::class, 'index'])->name('todos.index');
    Route::post('/todos', [TodoController::class, 'store'])->name('todos.store');

    Route::get('/todos/{id}/edit', [TodoController::class, 'edit'])->name('todos.edit');
    Route::put('/todos/{id}', [TodoController::class, 'update'])->name('todos.update');

    Route::patch('/todos/{id}/toggle', [TodoController::class, 'toggle'])->name('todos.toggle');
    Route::delete('/todos/{id}', [TodoController::class, 'destroy'])->name('todos.destroy');

    Route::delete('/todos', [TodoController::class, 'clearAll'])->name('todos.clear');

    Route::post('/theme/toggle', [ThemeController::class, 'toggle'])->name('theme.toggle');
});

require __DIR__.'/auth.php';
