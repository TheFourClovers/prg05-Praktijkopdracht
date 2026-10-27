<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/about-us', function () {
//     return view('about-us');
// });

// Route::get('/contact', function () {
//     return view('contact');
// });

Route::get('/test', function () {
    return view('test');
});

Route::get('/tasks', [TaskController::class, 'index'])->name(name: 'tasks.index');

Route::get('/tasks/create', [TaskController::class, 'create'])
    ->middleware('auth')
    ->name(name: 'tasks.create');

Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])
    ->middleware('auth')
    ->name(name: 'tasks.edit');

Route::get('/tasks/{task}', [TaskController::class, 'show'])
    ->name(name: 'tasks.show');

Route::post('/tasks', [TaskController::class, 'store'])
    ->middleware('auth')
    ->name(name: 'tasks.store');

Route::post('/tasks/{task}/close', [TaskController::class, 'close'])
    ->middleware('auth')
    ->name(name: 'tasks.close');

Route::put('/tasks/{task}', [TaskController::class, 'update'])
    ->middleware('auth')
    ->name(name: 'tasks.update');

Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])
    ->middleware('auth')
    ->name(name: 'tasks.destroy');

Route::view('/', 'welcome')->name('home');
Route::view('/about-us', 'about-us');
Route::view('/contact', 'contact');

Route::get('/app', function () {
    return view('app');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

require __DIR__.'/auth.php';
require __DIR__.'/settings.php';
