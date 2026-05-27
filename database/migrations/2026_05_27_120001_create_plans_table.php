<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // e.g. "Pro"
            $table->string('slug')->unique(); // e.g. "pro"
            $table->text('description')->nullable();
            $table->string('price_display')->nullable(); // e.g. "NPR 2,500/mo" — display only
            $table->json('features');          // feature flag object
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
