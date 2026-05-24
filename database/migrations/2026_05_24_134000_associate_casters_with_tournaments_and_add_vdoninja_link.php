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
        Schema::table('casters', function (Blueprint $table) {
            $table->string('vdoninja_link')->nullable()->after('image');
        });

        Schema::create('caster_tournament', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caster_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tournament_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caster_tournament');

        Schema::table('casters', function (Blueprint $table) {
            $table->dropColumn('vdoninja_link');
        });
    }
};
