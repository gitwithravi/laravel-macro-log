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
        Schema::create('meal_insights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meal_entry_id')->constrained()->onDelete('cascade');
            $table->text('insight');
            $table->timestamps();

            // Index for faster lookups
            $table->index('meal_entry_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_insights');
    }
};
