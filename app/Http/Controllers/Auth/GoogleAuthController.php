<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        // Check if Google OAuth is enabled
        if (!Setting::get('google_oauth_enabled', false)) {
            return redirect()->route('login')
                ->with('error', 'Google authentication is currently disabled.');
        }

        try {
            return Socialite::driver('google')
                ->redirect();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Google authentication is not configured properly.');
        }
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        // Check if Google OAuth is enabled
        if (!Setting::get('google_oauth_enabled', false)) {
            return redirect()->route('login')
                ->with('error', 'Google authentication is currently disabled.');
        }

        try {
            $googleUser = Socialite::driver('google')->user();

            // Find or create user
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                // Update existing user
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'auth_provider' => 'google',
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ]);
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'auth_provider' => 'google',
                    'password' => Hash::make(Str::random(24)), // Random password for Google users
                    'email_verified_at' => now(),
                    'is_active' => true,
                    'is_admin' => false,
                ]);
            }

            // Update login information
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => request()->ip(),
            ]);

            // Log the user in
            Auth::login($user, true);

            return redirect()->route('dashboard')
                ->with('success', 'Welcome back, ' . $user->name . '!');

        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Failed to authenticate with Google. Please try again.');
        }
    }
}