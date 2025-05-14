<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

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
        'estimated_duration',
        'attachments',
        'completion_notes',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
        'is_recurring' => 'boolean',
        'recurrence_data' => 'array',
        'attachments' => 'array',
        'urgency_score' => 'decimal:2',
    ];

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

    protected function durationInHours(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->estimated_duration ? round($this->estimated_duration / 60, 2) : null,
        );
    }
}
