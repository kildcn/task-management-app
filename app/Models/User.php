<?php

namespace App\Models;

// Remove MustVerifyEmail interface for development
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable // Remove MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'household_id',
        'name',
        'email',
        'password',
        'role',
        'avatar_path',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'creator_id');
    }

    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assignee_id');
    }

    public function completedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'completed_by_id');
    }

    public function taskParticipations(): HasMany
    {
        return $this->hasMany(TaskParticipant::class);
    }

    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
                    ->withPivot('awarded_at', 'notes')
                    ->withTimestamps();
    }

    public function taskStats(): HasOne
    {
        return $this->hasOne(TaskStat::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(TaskActivityLog::class);
    }

    public function taskReactions(): HasMany
    {
        return $this->hasMany(TaskReaction::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user has a household
     */
    public function hasHousehold(): bool
    {
        return !is_null($this->household_id);
    }
}
