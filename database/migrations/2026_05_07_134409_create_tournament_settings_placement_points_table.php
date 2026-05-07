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
        Schema::create('tournament_placement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_setting_id')->constrained()->cascadeOnDelete();
            $table->integer('placement');
            $table->bigInteger('points')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_settings_placement_points');
    }
};
