<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\ProfileController;

// Home route
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('welcome');
});

// Routes for users without household
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('household/create', [HouseholdController::class, 'create'])->name('household.create');
    Route::post('household', [HouseholdController::class, 'store'])->name('household.store');
    Route::get('household/join', [HouseholdController::class, 'joinForm'])->name('household.join');
    Route::post('household/join', [HouseholdController::class, 'join'])->name('household.join.post');
});

// Protected routes requiring household membership
Route::middleware(['auth', 'verified', 'household.member'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tasks
    Route::resource('tasks', TaskController::class);
    Route::patch('tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::patch('tasks/{task}/reopen', [TaskController::class, 'reopen'])->name('tasks.reopen');
    Route::post('tasks/{task}/reaction', [TaskController::class, 'addReaction'])->name('tasks.reaction');

    // Categories
    Route::resource('categories', CategoryController::class);
    Route::patch('categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');

    // Household management
    Route::resource('household', HouseholdController::class)->only(['show', 'edit', 'update']);
    Route::get('household/members', [HouseholdController::class, 'members'])->name('household.members');
    Route::get('household/invite', [HouseholdController::class, 'invite'])->name('household.invite');

    // Profile
    Route::resource('profile', ProfileController::class)->only(['show', 'edit', 'update']);
    Route::get('profile/stats', [ProfileController::class, 'stats'])->name('profile.stats');
    Route::get('profile/badges', [ProfileController::class, 'badges'])->name('profile.badges');
});

// Include Breeze auth routes
require __DIR__.'/auth.php';
