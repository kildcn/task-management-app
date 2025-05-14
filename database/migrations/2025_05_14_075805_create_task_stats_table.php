<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
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
            $table->decimal('completion_rate', 5, 2)->default(0); // Percentage
            $table->timestamps();

            $table->unique(['user_id', 'household_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('task_stats');
    }
};
