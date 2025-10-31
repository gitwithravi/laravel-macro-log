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
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('current_weight', 8, 2);
            $table->decimal('target_weight', 8, 2);
            $table->integer('daily_goal_calories');
            $table->decimal('daily_goal_protein', 8, 2);
            $table->decimal('daily_goal_carb', 8, 2);
            $table->decimal('daily_goal_fat', 8, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};
