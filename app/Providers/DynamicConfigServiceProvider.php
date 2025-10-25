<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Setting;

class DynamicConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Configure Google OAuth dynamically from database settings
        try {
            if (schema()->hasTable('settings')) {
                $this->configureGoogleOAuth();
            }
        } catch (\Exception $e) {
            // Silently fail if database is not available yet (during migrations)
        }
    }

    /**
     * Configure Google OAuth from database settings
     */
    private function configureGoogleOAuth()
    {
        $googleEnabled = Setting::get('google_oauth_enabled', false);
        
        if ($googleEnabled) {
            $clientId = Setting::get('google_client_id', '');
            $clientSecret = Setting::get('google_client_secret', '');
            $redirectUri = Setting::get('google_redirect_uri', url('/auth/google/callback'));

            if (!empty($clientId) && !empty($clientSecret)) {
                config([
                    'services.google.client_id' => $clientId,
                    'services.google.client_secret' => $clientSecret,
                    'services.google.redirect' => $redirectUri,
                ]);
            }
        }
    }
}