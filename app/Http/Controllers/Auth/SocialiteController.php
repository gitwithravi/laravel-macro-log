<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Socialite\CreateUserFromProvider;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect to the provider authentication page.
     */
    public function redirect(string $provider): RedirectResponse
    {
        $this->validateProvider($provider);

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the provider callback.
     */
    public function callback(string $provider, CreateUserFromProvider $createUser): RedirectResponse
    {
        $this->validateProvider($provider);

        try {
            $providerUser = Socialite::driver($provider)->user();

            $user = $createUser->create($provider, $providerUser);

            Auth::login($user, true);

            return redirect()->intended(route('dashboard', absolute: false));
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Unable to login with '.$provider.'. Please try again.',
            ]);
        }
    }

    /**
     * Handle Google One Tap callback.
     */
    public function handleOneTap(Request $request, CreateUserFromProvider $createUser): RedirectResponse
    {
        $request->validate([
            'credential' => 'required|string',
        ]);

        try {
            // Verify and decode the Google ID token
            $providerUser = Socialite::driver('google')->userFromToken($request->credential);

            $user = $createUser->create('google', $providerUser);

            Auth::login($user, true);

            return redirect()->intended(route('dashboard', absolute: false));
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Unable to login with Google. Please try again.',
            ]);
        }
    }

    /**
     * Validate the social provider.
     */
    protected function validateProvider(string $provider): void
    {
        if (! in_array($provider, ['google'])) {
            abort(404);
        }
    }
}
