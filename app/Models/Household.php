<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Household extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'timezone',
        'description',
        'join_key',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($household) {
            // Generate a unique join key if not provided
            if (empty($household->join_key)) {
                $household->join_key = self::generateUniqueJoinKey();
            }
        });
    }

    /**
     * Generate a unique join key for the household
     */
    public static function generateUniqueJoinKey(): string
    {
        $key = strtoupper(Str::random(8));

        // Make sure the key is unique
        while (self::where('join_key', $key)->exists()) {
            $key = strtoupper(Str::random(8));
        }

        return $key;
    }

    /**
     * Regenerate the join key for the household
     */
    public function regenerateJoinKey(): void
    {
        $this->join_key = self::generateUniqueJoinKey();
        $this->save();
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function taskStats(): HasMany
    {
        return $this->hasMany(TaskStat::class);
    }
}
