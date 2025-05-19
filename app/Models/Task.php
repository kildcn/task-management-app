<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'household_id',
        'creator_id',
        'assignee_id',
        'category_id',
        'title',
        'description',
        'is_recurring',
        'recurrence_pattern',
        'recurrence_data',
        'due_date',
        'completed_at',
        'completed_by_id',
        'urgency_score',
        'priority',
        'difficulty',
        'estimated_duration',
        'attachments',
        'completion_notes',
        'overdue_penalty_applied',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
        'is_recurring' => 'boolean',
        'recurrence_data' => 'array',
        'attachments' => 'array',
        'urgency_score' => 'decimal:2',
        'deleted_at' => 'datetime',
        'difficulty' => 'integer',
        'overdue_penalty_applied' => 'boolean',
    ];

    protected $dates = ['deleted_at'];

    protected static function booted()
    {
        static::creating(function ($task) {
            $task->urgency_score = $task->calculateUrgencyScore();
        });

        static::updating(function ($task) {
            if ($task->isDirty(['due_date', 'priority'])) {
                $task->urgency_score = $task->calculateUrgencyScore();
            }
        });
    }

    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(TaskParticipant::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(TaskActivityLog::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(TaskReaction::class);
    }

    public function isCompleted(): bool
    {
        return !is_null($this->completed_at);
    }

    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && !$this->isCompleted();
    }

    /**
     * Check if the task is severely overdue (more than 1 day)
     */
    public function isSeverelyOverdue(): bool
    {
        if (!$this->due_date || $this->isCompleted()) {
            return false;
        }

        return $this->due_date->diffInDays(now()) >= 1;
    }

    /**
     * Calculate the overdue penalty points
     */
    public function calculateOverduePenalty(): int
    {
        if (!$this->isOverdue() || $this->overdue_penalty_applied) {
            return 0;
        }

        // Base penalty is 10 points
        $basePenalty = 10;

        // Additional penalty based on severity and priority
        $daysOverdue = $this->due_date->diffInDays(now());
        $severityMultiplier = min(5, $daysOverdue); // Cap at 5 days for multiplier

        $priorityMultiplier = match($this->priority) {
            'urgent' => 2.0,
            'high' => 1.5,
            'medium' => 1.0,
            'low' => 0.5,
        };

        // Calculate total penalty
        $totalPenalty = (int)($basePenalty * $severityMultiplier * $priorityMultiplier);

        // Cap penalty at 100 points
        return min(100, $totalPenalty);
    }

    public function calculateUrgencyScore(): float
    {
        if (!$this->due_date) {
            return 0;
        }

        $now = Carbon::now();
        $hoursUntilDue = $now->diffInHours($this->due_date, false);

        // Base score on time until due (negative if overdue)
        $timeScore = max(0, 100 - ($hoursUntilDue / 24));

        // Priority multiplier
        $priorityMultiplier = match($this->priority) {
            'urgent' => 2.0,
            'high' => 1.5,
            'medium' => 1.0,
            'low' => 0.5,
        };

        return round($timeScore * $priorityMultiplier, 2);
    }

    /**
     * Get the difficulty label
     */
    public function getDifficultyLabelAttribute(): string
    {
        return match($this->difficulty) {
            1 => 'Very Easy',
            2 => 'Easy',
            3 => 'Medium',
            4 => 'Hard',
            5 => 'Very Hard',
            default => 'Medium',
        };
    }

    /**
     * Calculate points for completing this task
     */
    public function getCompletionPointsAttribute(): int
    {
        return 30 * $this->difficulty;
    }

    /**
     * Calculate points for creating this task
     */
    public function getCreationPointsAttribute(): int
    {
        return 100;
    }

    /**
     * Apply the overdue penalty to the assignee's stats
     */
    public function applyOverduePenalty(): int
    {
        if (!$this->isOverdue() || $this->overdue_penalty_applied || !$this->assignee) {
            return 0;
        }

        $penalty = $this->calculateOverduePenalty();

        if ($penalty > 0) {
            // Get or create stats for the assignee
            $stats = TaskStat::firstOrCreate([
                'user_id' => $this->assignee_id,
                'household_id' => $this->household_id,
            ]);

            // Apply penalty
            $stats->points = max(0, $stats->points - $penalty);
            $stats->save();

            // Mark penalty as applied
            $this->overdue_penalty_applied = true;
            $this->save();

            // Log the penalty
            TaskActivityLog::create([
                'task_id' => $this->id,
                'user_id' => $this->assignee_id,
                'action' => 'penalty',
                'description' => "Received a {$penalty} point penalty for overdue task: {$this->title}",
            ]);
        }

        return $penalty;
    }

    protected function durationInHours(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->estimated_duration ? round($this->estimated_duration / 60, 2) : null,
        );
    }
}
