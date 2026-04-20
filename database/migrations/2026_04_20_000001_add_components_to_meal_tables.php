<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meal_entries', function (Blueprint $table) {
            $table->json('components')->nullable()->after('fat');
        });

        Schema::table('frequent_meals', function (Blueprint $table) {
            $table->json('components')->nullable()->after('fat');
        });
    }

    public function down(): void
    {
        Schema::table('meal_entries', function (Blueprint $table) {
            $table->dropColumn('components');
        });

        Schema::table('frequent_meals', function (Blueprint $table) {
            $table->dropColumn('components');
        });
    }
};
