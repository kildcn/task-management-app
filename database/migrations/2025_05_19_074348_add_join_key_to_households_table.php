<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('households', function (Blueprint $table) {
            $table->string('join_key', 10)->after('timezone')->unique()->nullable();
        });

        // Generate unique join keys for existing households
        $households = \App\Models\Household::all();
        foreach ($households as $household) {
            $household->join_key = Str::random(8);
            $household->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('households', function (Blueprint $table) {
            $table->dropColumn('join_key');
        });
    }
};
