<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class OAuthSettingsController extends Controller
{
    public function index()
    {
        $oauthSettings = Setting::whereIn('key', [
            'google_oauth_enabled',
            'google_client_id',
            'google_client_secret',
            'google_redirect_uri',
        ])->get();

        return view('admin.settings.oauth', compact('oauthSettings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'google_oauth_enabled' => 'nullable|boolean',
            'google_client_id' => 'required_if:google_oauth_enabled,1|nullable|string',
            'google_client_secret' => 'required_if:google_oauth_enabled,1|nullable|string',
            'google_redirect_uri' => 'required_if:google_oauth_enabled,1|nullable|string|url',
        ], [
            'google_client_id.required_if' => 'Google Client ID is required when OAuth is enabled',
            'google_client_secret.required_if' => 'Google Client Secret is required when OAuth is enabled',
            'google_redirect_uri.required_if' => 'Google Redirect URI is required when OAuth is enabled',
        ]);

        try {
            $enabled = $request->boolean('google_oauth_enabled');

            // Keep previously saved credentials when the inputs were disabled (e.g. unchecking "Enable")
            $clientId = $request->google_client_id ?? Setting::get('google_client_id', '');
            $clientSecret = $request->google_client_secret ?? Setting::get('google_client_secret', '');
            $redirectUri = $request->google_redirect_uri ?? Setting::get('google_redirect_uri', url('/auth/google/callback'));

            // Update Google OAuth settings
            Setting::set('google_oauth_enabled', $enabled ? '1' : '0', 'boolean');
            Setting::set('google_client_id', !empty($clientId) ? $clientId : '', 'string');
            Setting::set('google_client_secret', !empty($clientSecret) ? $clientSecret : '', 'string');
            Setting::set('google_redirect_uri', !empty($redirectUri) ? $redirectUri : '', 'string');

            // Ensure the four OAuth keys are grouped together
            Setting::whereIn('key', [
                'google_oauth_enabled',
                'google_client_id',
                'google_client_secret',
                'google_redirect_uri',
            ])->update(['group' => 'oauth']);

            // Clear settings cache
            Cache::forget('oauth_settings');

            // Update .env file dynamically (optional but recommended)
            $this->updateEnvFile([
                'GOOGLE_OAUTH_ENABLED' => $enabled ? 'true' : 'false',
                'GOOGLE_CLIENT_ID' => $clientId,
                'GOOGLE_CLIENT_SECRET' => $clientSecret,
                'GOOGLE_REDIRECT_URI' => $redirectUri,
            ]);

            return redirect()->back()->with('success', 'OAuth settings updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }

    public function testConnection()
    {
        try {
            $clientId = Setting::get('google_client_id');
            $clientSecret = Setting::get('google_client_secret');
            $redirectUri = Setting::get('google_redirect_uri', url('/auth/google/callback'));

            if (empty($clientId) || empty($clientSecret)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Google OAuth credentials are not configured',
                ]);
            }

            // Real check: ask Google's authorize endpoint with the saved Client ID + Redirect URI.
            // Valid client/redirect => Google answers with its sign-in page (HTTP 200).
            // Bad client or unregistered redirect => HTTP 400/403 with an error marker.
            $authorizeUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
                'client_id' => $clientId,
                'redirect_uri' => $redirectUri,
                'response_type' => 'code',
                'scope' => 'openid profile email',
                'access_type' => 'online',
            ]);

            $response = Http::withoutVerifying()
                ->timeout(20)
                ->withOptions(['allow_redirects' => ['max' => 3]])
                ->get($authorizeUrl);

            $status = $response->status();
            $body = $response->body();

            $errorMarkers = ['redirect_uri_mismatch', 'invalid_client', 'Error 400', 'Error 403', 'access_denied'];

            if ($status === 200 && !collect($errorMarkers)->contains(fn ($m) => str_contains($body, $m))) {
                return response()->json([
                    'success' => true,
                    'message' => 'Client ID and Redirect URI are valid — Google accepted the credentials. (The Client Secret is confirmed only when a user completes a login.)',
                ]);
            }

            $reason = collect($errorMarkers)->first(fn ($m) => str_contains($body, $m));

            return response()->json([
                'success' => false,
                'message' => 'Google rejected the credentials' . ($reason ? ": {$reason}" : ' (HTTP ' . $status . ')') . '. Check the Client ID and that the Redirect URI is exactly "' . $redirectUri . '" in the Google Cloud Console.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update .env file with new values
     */
    private function updateEnvFile(array $data)
    {
        $envPath = base_path('.env');
        
        if (!file_exists($envPath)) {
            return;
        }

        $envContent = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            // Escape special characters in value
            $value = str_replace('"', '\\"', $value);
            
            // Check if key exists in .env
            if (preg_match("/^{$key}=.*/m", $envContent)) {
                // Update existing key
                $envContent = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}=\"{$value}\"",
                    $envContent
                );
            } else {
                // Add new key
                $envContent .= "\n{$key}=\"{$value}\"";
            }
        }

        file_put_contents($envPath, $envContent);
    }
}