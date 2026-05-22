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
        Schema::create('match_stat_player', function (Blueprint $table) {
            $table->foreignId('match_stat_id')->constrained('match_stats')->cascadeOnDelete();
            $table->foreignId('player_id')->constrained('players')->cascadeOnDelete();
            $table->primary(['match_stat_id', 'player_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_stat_player');
    }
};
