<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Crypt;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, increase the column size to accommodate encrypted data
        Schema::table('users', function (Blueprint $table) {
            $table->text('open_api_key')->nullable()->change();
        });

        // Get all users with API keys
        $users = DB::table('users')
            ->whereNotNull('open_api_key')
            ->where('open_api_key', '!=', '')
            ->get();

        foreach ($users as $user) {
            // Check if the API key is already encrypted
            try {
                // Try to decrypt it - if it works, it's already encrypted
                Crypt::decryptString($user->open_api_key);
                // Already encrypted, skip
                continue;
            } catch (\Exception $e) {
                // Not encrypted, so encrypt it
                $encryptedKey = Crypt::encryptString($user->open_api_key);

                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['open_api_key' => $encryptedKey]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get all users with API keys
        $users = DB::table('users')
            ->whereNotNull('open_api_key')
            ->where('open_api_key', '!=', '')
            ->get();

        foreach ($users as $user) {
            try {
                // Try to decrypt the key
                $decryptedKey = Crypt::decryptString($user->open_api_key);

                // If successful, store the decrypted version
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['open_api_key' => $decryptedKey]);
            } catch (\Exception $e) {
                // If decryption fails, it's probably already plain text, skip
                continue;
            }
        }

        // Revert column type back to varchar
        Schema::table('users', function (Blueprint $table) {
            $table->string('open_api_key', 255)->nullable()->change();
        });
    }
};