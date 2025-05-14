<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('household_id')->constrained()->onDelete('cascade');
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assignee_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_pattern')->nullable(); // daily, weekly, monthly, custom
            $table->json('recurrence_data')->nullable(); // Store complex recurrence rules
            $table->datetime('due_date')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->foreignId('completed_by_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('urgency_score', 5, 2)->default(0);
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->integer('estimated_duration')->nullable(); // minutes
            $table->json('attachments')->nullable();
            $table->text('completion_notes')->nullable();
            $table->timestamps();

            $table->index(['household_id', 'due_date']);
            $table->index(['assignee_id', 'completed_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
