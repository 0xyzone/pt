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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_team_id')->constrained()->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('ign'); // In-game name
            $table->string('in_game_id')->nullable(); // In-game UID / Character ID
            $table->string('role')->default('player'); // player, igl, substitute, manager
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
