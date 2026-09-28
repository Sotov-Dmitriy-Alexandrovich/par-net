<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\TrackerController;
use App\Http\Controllers\QuitAttemptController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\RelapseController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Главная страница — гайд
Route::get('/', [GuideController::class, 'index'])->name('guide.index');
Route::get('/guide/{slug}', [GuideController::class, 'section'])->name('guide.section');

// Трекер (требует авторизации)
Route::middleware(['auth'])->group(function () {
    // Главная страница трекера
    Route::get('/tracker', [TrackerController::class, 'index'])->name('tracker.index');
    Route::post('/tracker/start', [TrackerController::class, 'start'])->name('tracker.start');
    Route::post('/tracker/task/{taskId}/complete', [TrackerController::class, 'completeTask'])->name('tracker.task.complete');
    Route::post('/tracker/relapse', [TrackerController::class, 'relapse'])->name('tracker.relapse');

    // Попытки
    Route::get('/attempts/history', [QuitAttemptController::class, 'history'])->name('attempts.history');
    Route::get('/attempts/stats', [QuitAttemptController::class, 'stats'])->name('attempts.stats');

    // Задания
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/progress', [TaskController::class, 'progress'])->name('tasks.progress');

    // Срывы
    Route::get('/relapse/create', [RelapseController::class, 'create'])->name('relapse.create');
    Route::post('/relapse', [RelapseController::class, 'store'])->name('relapse.store');
    Route::get('/relapse/history', [RelapseController::class, 'history'])->name('relapse.history');

    // Профиль пользователя
    Route::get('/profile', [UserController::class, 'show'])->name('profile.show');
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update');
});

// Стандартные роуты авторизации (Laravel Breeze)
//require __DIR__.'/auth.php';
