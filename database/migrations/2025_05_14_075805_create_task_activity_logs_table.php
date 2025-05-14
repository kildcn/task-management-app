<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('task_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('action', [
                'created', 'updated', 'completed', 'reopened',
                'assigned', 'unassigned', 'commented', 'deleted'
            ]);
            $table->text('description')->nullable();
            $table->json('changes')->nullable(); // Store what changed
            $table->timestamp('created_at');

            $table->index(['task_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('task_activity_logs');
    }
};
