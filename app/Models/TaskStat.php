<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class TaskStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'household_id',
        'tasks_created_count',
        'tasks_completed_count',
        'tasks_assigned_count',
        'current_streak_days',
        'longest_streak_days',
        'last_completed_at',
        'points',
        'completion_rate',
    ];

    protected $casts = [
        'last_completed_at' => 'datetime',
        'completion_rate' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    public function updateStats()
    {
        $user = $this->user;

        // Count tasks created by this user
        $this->tasks_created_count = Task::where('creator_id', $user->id)
            ->where('household_id', $this->household_id)
            ->count();

        // Count tasks completed by this user
        $this->tasks_completed_count = Task::where('completed_by_id', $user->id)
            ->where('household_id', $this->household_id)
            ->whereNotNull('completed_at')
            ->count();

        // Count tasks assigned to this user
        $this->tasks_assigned_count = Task::where('assignee_id', $user->id)
            ->where('household_id', $this->household_id)
            ->count();

        // Calculate completion rate based on assigned tasks
        if ($this->tasks_assigned_count > 0) {
            $assignedAndCompleted = Task::where('assignee_id', $user->id)
                ->where('household_id', $this->household_id)
                ->whereNotNull('completed_at')
                ->count();
            $this->completion_rate = ($assignedAndCompleted / $this->tasks_assigned_count) * 100;
        } else {
            $this->completion_rate = 0;
        }

        // Update last completed timestamp
        $lastCompleted = Task::where('completed_by_id', $user->id)
            ->where('household_id', $this->household_id)
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'desc')
            ->first();

        $this->last_completed_at = $lastCompleted ? $lastCompleted->completed_at : null;

        $this->calculateStreak();
        $this->calculatePoints();
        $this->save();
    }

    private function calculateStreak()
    {
        $user = $this->user;
        $completedTasks = Task::where('completed_by_id', $user->id)
            ->where('household_id', $this->household_id)
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'desc')
            ->get(['completed_at']);

        $streak = 0;
        $currentDate = Carbon::now()->startOfDay();
        $consecutiveDays = collect();

        foreach ($completedTasks as $task) {
            $completedDate = $task->completed_at->startOfDay();
            $consecutiveDays->push($completedDate->format('Y-m-d'));
        }

        // Remove duplicates and sort
        $uniqueDays = $consecutiveDays->unique()->sort()->reverse()->values();

        // Calculate current streak
        foreach ($uniqueDays as $index => $day) {
            $dayCarbon = Carbon::parse($day);

            if ($index === 0) {
                // First day - check if it's today or yesterday
                if ($dayCarbon->isSameDay($currentDate) || $dayCarbon->isSameDay($currentDate->subDay())) {
                    $streak = 1;
                    $checkDate = $dayCarbon;
                } else {
                    // No recent activity
                    break;
                }
            } else {
                // Check if this day is exactly one day before the previous day
                $expectedDate = $checkDate->copy()->subDay();
                if ($dayCarbon->isSameDay($expectedDate)) {
                    $streak++;
                    $checkDate = $dayCarbon;
                } else {
                    // Streak broken
                    break;
                }
            }
        }

        $this->current_streak_days = $streak;

        // Calculate longest streak (simplified - you might want to make this more sophisticated)
        $this->longest_streak_days = max($this->longest_streak_days, $streak);
    }

    private function calculatePoints()
    {
        $user = $this->user;
        $totalPoints = 0;

        // Points for creating tasks (100 points each)
        $createdTasks = Task::where('creator_id', $user->id)
            ->where('household_id', $this->household_id)
            ->get();

        foreach ($createdTasks as $task) {
            $totalPoints += $task->creation_points;
        }

        // Points for completing tasks (30 points × difficulty)
        $completedTasks = Task::where('completed_by_id', $user->id)
            ->where('household_id', $this->household_id)
            ->whereNotNull('completed_at')
            ->get();

        foreach ($completedTasks as $task) {
            $totalPoints += $task->completion_points;
        }

        // Streak bonus (5 points per day in current streak)
        $streakBonus = $this->current_streak_days * 5;

        // Completion rate bonus (up to 20 points for 100% completion)
        $completionBonus = ($this->completion_rate / 100) * 20;

        $this->points = round($totalPoints + $streakBonus + $completionBonus);
    }
}
