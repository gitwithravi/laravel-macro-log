<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, backup and encrypt existing data in users table
        $users = DB::table('users')
            ->select('id', 'date_of_birth', 'gender', 'height')
            ->get();

        // Change column types to TEXT to accommodate encrypted data
        Schema::table('users', function (Blueprint $table) {
            $table->text('date_of_birth')->nullable()->change();
            $table->text('gender')->nullable()->change();
            $table->text('height')->nullable()->change();
        });

        // Encrypt existing user PII data
        foreach ($users as $user) {
            $updates = [];

            if ($user->date_of_birth && !$this->isEncrypted($user->date_of_birth)) {
                $updates['date_of_birth'] = Crypt::encryptString($user->date_of_birth);
            }

            if ($user->gender && !$this->isEncrypted($user->gender)) {
                $updates['gender'] = Crypt::encryptString($user->gender);
            }

            if ($user->height && !$this->isEncrypted($user->height)) {
                $updates['height'] = Crypt::encryptString($user->height);
            }

            if (!empty($updates)) {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update($updates);
            }
        }

        // Now handle goals table - encrypt weight data
        $goals = DB::table('goals')
            ->select('id', 'current_weight', 'target_weight')
            ->get();

        // Change column types in goals table
        Schema::table('goals', function (Blueprint $table) {
            $table->text('current_weight')->nullable()->change();
            $table->text('target_weight')->nullable()->change();
        });

        // Encrypt existing goal weight data
        foreach ($goals as $goal) {
            $updates = [];

            if ($goal->current_weight && !$this->isEncrypted($goal->current_weight)) {
                $updates['current_weight'] = Crypt::encryptString($goal->current_weight);
            }

            if ($goal->target_weight && !$this->isEncrypted($goal->target_weight)) {
                $updates['target_weight'] = Crypt::encryptString($goal->target_weight);
            }

            if (!empty($updates)) {
                DB::table('goals')
                    ->where('id', $goal->id)
                    ->update($updates);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Decrypt user data
        $users = DB::table('users')
            ->select('id', 'date_of_birth', 'gender', 'height')
            ->get();

        foreach ($users as $user) {
            $updates = [];

            if ($user->date_of_birth && $this->isEncrypted($user->date_of_birth)) {
                $updates['date_of_birth'] = Crypt::decryptString($user->date_of_birth);
            }

            if ($user->gender && $this->isEncrypted($user->gender)) {
                $updates['gender'] = Crypt::decryptString($user->gender);
            }

            if ($user->height && $this->isEncrypted($user->height)) {
                $updates['height'] = Crypt::decryptString($user->height);
            }

            if (!empty($updates)) {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update($updates);
            }
        }

        // Revert column types in users table
        Schema::table('users', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->change();
            $table->string('gender', 10)->nullable()->change();
            $table->decimal('height', 5, 2)->nullable()->change();
        });

        // Decrypt goal data
        $goals = DB::table('goals')
            ->select('id', 'current_weight', 'target_weight')
            ->get();

        foreach ($goals as $goal) {
            $updates = [];

            if ($goal->current_weight && $this->isEncrypted($goal->current_weight)) {
                $updates['current_weight'] = Crypt::decryptString($goal->current_weight);
            }

            if ($goal->target_weight && $this->isEncrypted($goal->target_weight)) {
                $updates['target_weight'] = Crypt::decryptString($goal->target_weight);
            }

            if (!empty($updates)) {
                DB::table('goals')
                    ->where('id', $goal->id)
                    ->update($updates);
            }
        }

        // Revert column types in goals table
        Schema::table('goals', function (Blueprint $table) {
            $table->decimal('current_weight', 5, 2)->nullable()->change();
            $table->decimal('target_weight', 5, 2)->nullable()->change();
        });
    }

    /**
     * Check if a value appears to be encrypted
     */
    private function isEncrypted($value): bool
    {
        if (empty($value)) {
            return false;
        }

        try {
            // If we can decrypt it, it's encrypted
            Crypt::decryptString($value);
            return true;
        } catch (\Exception $e) {
            // If decryption fails, it's not encrypted
            return false;
        }
    }
};