<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the check constraint from gender column
        // This is needed because gender values are encrypted, so check constraints won't work
        DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_gender_check');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add the check constraint
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_gender_check CHECK (gender = ANY (ARRAY['male'::character varying::text, 'female'::character varying::text, 'other'::character varying::text, 'prefer_not_to_say'::character varying::text]))");
    }
};
