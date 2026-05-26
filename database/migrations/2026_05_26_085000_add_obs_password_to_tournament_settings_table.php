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
        Schema::table('tournament_settings', function (Blueprint $table) {
            $table->string('obs_password')->nullable()->after('kill_points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tournament_settings', function (Blueprint $table) {
            $table->dropColumn('obs_password');
        });
    }
};
