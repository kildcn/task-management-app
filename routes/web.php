<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\CalendarController;

// Home route
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('welcome');
});

// Routes for users without household (remove 'verified' middleware)
Route::middleware(['auth'])->group(function () {
    Route::get('household/create', [HouseholdController::class, 'create'])->name('household.create');
    Route::post('household', [HouseholdController::class, 'store'])->name('household.store');
    Route::get('household/join', [HouseholdController::class, 'joinForm'])->name('household.join');
    Route::post('household/join', [HouseholdController::class, 'join'])->name('household.join.post');
});

// Protected routes requiring household membership (remove 'verified' middleware)
Route::middleware(['auth', 'household.member'])->group(function () {
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
    Route::get('household', [HouseholdController::class, 'show'])->name('household.show'); // Changed from resource to explicit
    Route::get('household/manage-members', [HouseholdController::class, 'members'])->name('household.members'); // Changed URL path
    Route::delete('household/members/{member}', [HouseholdController::class, 'removeMember'])->name('household.members.remove');
    Route::patch('household/members/{member}/role', [HouseholdController::class, 'toggleAdminRole'])->name('household.members.toggle-role');
    Route::get('household/invite', [HouseholdController::class, 'invite'])->name('household.invite');
    Route::post('household/regenerate-key', [HouseholdController::class, 'regenerateKey'])->name('household.regenerate-key');
    Route::get('household/leave', [HouseholdController::class, 'leaveConfirm'])->name('household.leave');
    Route::post('household/leave', [HouseholdController::class, 'leave'])->name('household.leave.post');

    // Calendar
    Route::get('calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('calendar/month/{year}/{month}', [CalendarController::class, 'getMonthData'])->name('calendar.month');
    Route::get('calendar/day/{date}', [CalendarController::class, 'getDayDetails'])->name('calendar.day');

    // Stats
    Route::get('stats', [StatsController::class, 'index'])->name('stats.index');

    // Profile
    Route::resource('profile', ProfileController::class)->only(['show', 'edit', 'update']);
    Route::get('profile/stats', [ProfileController::class, 'stats'])->name('profile.stats');
    Route::get('profile/badges', [ProfileController::class, 'badges'])->name('profile.badges');
});

// Include Breeze auth routes
require __DIR__.'/auth.php';
