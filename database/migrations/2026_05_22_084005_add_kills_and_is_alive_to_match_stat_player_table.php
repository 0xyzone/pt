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
        Schema::table('match_stat_player', function (Blueprint $table) {
            $table->integer('kills')->default(0);
            $table->boolean('is_alive')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('match_stat_player', function (Blueprint $table) {
            $table->dropColumn(['kills', 'is_alive']);
        });
    }
};
