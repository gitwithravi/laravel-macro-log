<?php

use App\Http\Controllers\GoalController;
use App\Http\Controllers\MealEntryController;
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
    // Dashboard & Meal Logging Routes
    Route::get('/dashboard', [MealEntryController::class, 'dashboard'])->name('dashboard');
    Route::get('/history', [MealEntryController::class, 'history'])->name('history');
    Route::post('/meals', [MealEntryController::class, 'store'])->name('meals.store');
    Route::delete('/meals/{mealEntry}', [MealEntryController::class, 'destroy'])->name('meals.destroy');
    Route::get('/meals/{mealEntry}/insight', [MealEntryController::class, 'getInsight'])->name('meals.insight');

    // Goal Management Routes
    Route::resource('goals', GoalController::class)->except(['create', 'show', 'edit']);
    Route::post('/goals/{goal}/toggle-active', [GoalController::class, 'toggleActive'])->name('goals.toggle-active');
    Route::post('/goals/calculate-nutrition', [GoalController::class, 'calculateNutrition'])->name('goals.calculate-nutrition');
});
