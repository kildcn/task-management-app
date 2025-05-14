<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Make household_id nullable so users can be created before joining a household
            $table->foreignId('household_id')->after('id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('role', ['admin', 'member'])->default('member')->after('password');
            $table->string('avatar_path')->nullable()->after('role');
            $table->boolean('is_active')->default(true)->after('avatar_path');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['household_id']);
            $table->dropColumn(['household_id', 'role', 'avatar_path', 'is_active']);
        });
    }
};
