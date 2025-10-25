<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OAuthSettingsController extends Controller
{
    public function index()
    {
        $oauthSettings = Setting::where('group', 'oauth')->get();
        
        return view('admin.settings.oauth', compact('oauthSettings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'google_oauth_enabled' => 'required|boolean',
            'google_client_id' => 'required_if:google_oauth_enabled,1|nullable|string',
            'google_client_secret' => 'required_if:google_oauth_enabled,1|nullable|string',
            'google_redirect_uri' => 'required_if:google_oauth_enabled,1|nullable|string|url',
        ], [
            'google_client_id.required_if' => 'Google Client ID is required when OAuth is enabled',
            'google_client_secret.required_if' => 'Google Client Secret is required when OAuth is enabled',
            'google_redirect_uri.required_if' => 'Google Redirect URI is required when OAuth is enabled',
        ]);

        try {
            // Update Google OAuth settings
            Setting::set('google_oauth_enabled', $request->google_oauth_enabled ? '1' : '0', 'boolean');
            Setting::set('google_client_id', $request->google_client_id ?? '', 'string');
            Setting::set('google_client_secret', $request->google_client_secret ?? '', 'string');
            Setting::set('google_redirect_uri', $request->google_redirect_uri ?? '', 'string');

            // Clear settings cache
            Cache::forget('oauth_settings');

            // Update .env file dynamically (optional but recommended)
            $this->updateEnvFile([
                'GOOGLE_OAUTH_ENABLED' => $request->google_oauth_enabled ? 'true' : 'false',
                'GOOGLE_CLIENT_ID' => $request->google_client_id ?? '',
                'GOOGLE_CLIENT_SECRET' => $request->google_client_secret ?? '',
                'GOOGLE_REDIRECT_URI' => $request->google_redirect_uri ?? '',
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
            
            if (empty($clientId) || empty($clientSecret)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Google OAuth credentials are not configured',
                ]);
            }

            // Basic validation - check if credentials are set
            return response()->json([
                'success' => true,
                'message' => 'Google OAuth credentials are configured. Test login to verify.',
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