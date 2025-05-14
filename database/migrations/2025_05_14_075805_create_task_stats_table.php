<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create task_stats table if it doesn't exist or add missing columns
        if (!Schema::hasTable('task_stats')) {
            Schema::create('task_stats', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('household_id')->constrained()->onDelete('cascade');
                $table->integer('tasks_created_count')->default(0);
                $table->integer('tasks_completed_count')->default(0);
                $table->integer('tasks_assigned_count')->default(0);
                $table->integer('current_streak_days')->default(0);
                $table->integer('longest_streak_days')->default(0);
                $table->datetime('last_completed_at')->nullable();
                $table->integer('points')->default(0);
                $table->decimal('completion_rate', 5, 2)->default(0);
                $table->timestamps();

                $table->unique(['user_id', 'household_id']);
            });
        } else {
            // Add missing columns if table exists
            Schema::table('task_stats', function (Blueprint $table) {
                if (!Schema::hasColumn('task_stats', 'tasks_created_count')) {
                    $table->integer('tasks_created_count')->default(0);
                }
            });
        }

        // Update existing TaskStat records to calculate initial stats
        $taskStats = \App\Models\TaskStat::all();
        foreach ($taskStats as $stat) {
            $stat->updateStats();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only remove the column we added
        if (Schema::hasColumn('task_stats', 'tasks_created_count')) {
            Schema::table('task_stats', function (Blueprint $table) {
                $table->dropColumn('tasks_created_count');
            });
        }
    }
};
