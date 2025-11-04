<?php

namespace App\Actions\Socialite;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as ProviderUser;

class CreateUserFromProvider
{
    /**
     * Create or update a user from the social provider.
     */
    public function create(string $provider, ProviderUser $providerUser): User
    {
        // Check if user exists by provider ID
        $user = User::where($provider.'_id', $providerUser->getId())->first();

        if ($user) {
            // Update existing user's information
            $user->update([
                'name' => $providerUser->getName() ?: $user->name,
                'avatar' => $providerUser->getAvatar(),
            ]);

            return $user;
        }

        // Check if user exists by email
        $user = User::where('email', $providerUser->getEmail())->first();

        if ($user) {
            // Link the social account to existing user
            $user->update([
                $provider.'_id' => $providerUser->getId(),
                'avatar' => $providerUser->getAvatar(),
                'email_verified_at' => $user->email_verified_at ?? now(),
            ]);

            return $user;
        }

        // Create new user
        return User::create([
            'name' => $providerUser->getName(),
            'email' => $providerUser->getEmail(),
            $provider.'_id' => $providerUser->getId(),
            'avatar' => $providerUser->getAvatar(),
            'email_verified_at' => now(), // Auto-verify email for social logins
            'password' => Hash::make(Str::random(24)), // Random password for social users
        ]);
    }
}
