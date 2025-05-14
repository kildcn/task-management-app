<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('task_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['like', 'appreciate', 'well_done', 'helpful']);
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['task_id', 'user_id', 'type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('task_reactions');
    }
};
