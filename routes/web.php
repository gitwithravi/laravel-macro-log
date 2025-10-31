<?php

use App\Http\Controllers\GoalController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Goal Management Routes
    Route::resource('goals', GoalController::class)->except(['create', 'show', 'edit']);
    Route::post('/goals/{goal}/toggle-active', [GoalController::class, 'toggleActive'])->name('goals.toggle-active');
    Route::post('/goals/calculate-nutrition', [GoalController::class, 'calculateNutrition'])->name('goals.calculate-nutrition');
});
