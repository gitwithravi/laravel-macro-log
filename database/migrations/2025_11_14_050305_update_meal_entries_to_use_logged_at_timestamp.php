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
        Schema::table('meal_entries', function (Blueprint $table) {
            // Add the new timestamp column
            $table->timestamp('logged_at')->after('user_id');

            // Drop the old columns
            $table->dropColumn(['logged_date', 'logged_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meal_entries', function (Blueprint $table) {
            // Restore the old columns
            $table->date('logged_date')->after('user_id');
            $table->time('logged_time')->nullable()->after('logged_date');

            // Drop the new timestamp column
            $table->dropColumn('logged_at');
        });
    }
};
