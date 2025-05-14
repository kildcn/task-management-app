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

        $this->tasks_created_count = $user->createdTasks()->count();
        $this->tasks_completed_count = $user->completedTasks()->count();
        $this->tasks_assigned_count = $user->assignedTasks()->count();

        if ($this->tasks_assigned_count > 0) {
            $this->completion_rate = ($this->tasks_completed_count / $this->tasks_assigned_count) * 100;
        }

        $this->calculateStreak();
        $this->calculatePoints();
        $this->save();
    }

    private function calculateStreak()
    {
        $user = $this->user;
        $completedTasks = $user->completedTasks()
            ->orderBy('completed_at', 'desc')
            ->get(['completed_at']);

        $streak = 0;
        $lastDate = null;

        foreach ($completedTasks as $task) {
            $date = $task->completed_at->format('Y-m-d');

            if ($lastDate === null) {
                $lastDate = $date;
                $streak = 1;
            } elseif ($lastDate === Carbon::parse($date)->addDay()->format('Y-m-d')) {
                $streak++;
                $lastDate = $date;
            } else {
                break;
            }
        }

        $this->current_streak_days = $streak;
        $this->longest_streak_days = max($this->longest_streak_days, $streak);
    }

    private function calculatePoints()
    {
        // Simple point system: 10 points per completed task, bonus for streaks
        $basePoints = $this->tasks_completed_count * 10;
        $streakBonus = $this->current_streak_days * 5;
        $this->points = $basePoints + $streakBonus;
    }
}
