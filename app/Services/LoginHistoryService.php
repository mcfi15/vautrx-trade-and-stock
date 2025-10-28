<?php

namespace App\Services;

use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class LoginHistoryService
{
    /**
     * Track user login and send notification
     */
    public function trackLogin(User $user, $request, $success = true, $failureReason = null)
    {
        $userAgent = $request->userAgent() ?: '';
        $deviceInfo = $this->parseUserAgent($userAgent);
        $locationInfo = $this->getLocationInfo($request->ip());

        $loginHistory = LoginHistory::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $userAgent,
            'browser' => $deviceInfo['browser'],
            'device' => $deviceInfo['device'],
            'platform' => $deviceInfo['platform'],
            'location' => $locationInfo['location'],
            'latitude' => $locationInfo['latitude'],
            'longitude' => $locationInfo['longitude'],
            'success' => $success,
            'failure_reason' => $failureReason,
            'country' => $locationInfo['country'],
            'city' => $locationInfo['city'],
            'isp' => $locationInfo['isp'],
            'login_at' => now(),
        ]);

        // Send notification email only for successful logins
        if ($success) {
            $this->sendLoginNotification($user, $loginHistory);
        }

        return $loginHistory;
    }

    /**
     * Track password reset
     */
    public function logPasswordReset(User $user)
    {
        return LoginHistory::create([
            'user_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'browser' => 'N/A',
            'device' => 'Password Reset',
            'platform' => 'Web Interface',
            'location' => 'Unknown',
            'success' => true,
            'failure_reason' => 'Password Reset',
            'login_at' => now(),
        ]);
    }

    /**
     * Send login notification email
     */
    private function sendLoginNotification(User $user, LoginHistory $loginHistory)
    {
        try {
            Mail::to($user->email)->send(new \App\Mail\LoginNotificationMail($loginHistory));
        } catch (\Exception $e) {
            // Log the error but don't interrupt the login process
            \Log::error('Failed to send login notification: ' . $e->getMessage());
        }
    }

    /**
     * Parse user agent string to extract device/browser info
     */
    private function parseUserAgent($userAgent)
    {
        $result = [
            'browser' => 'Unknown',
            'device' => 'Unknown',
            'platform' => 'Unknown',
        ];

        if (empty($userAgent)) {
            return $result;
        }

        // Simple browser detection
        if (preg_match('/Chrome\/([0-9.]+)/', $userAgent)) {
            $result['browser'] = 'Chrome';
        } elseif (preg_match('/Firefox\/([0-9.]+)/', $userAgent)) {
            $result['browser'] = 'Firefox';
        } elseif (preg_match('/Safari\/([0-9.]+)/', $userAgent)) {
            $result['browser'] = 'Safari';
        } elseif (preg_match('/Edge\/([0-9.]+)/', $userAgent)) {
            $result['browser'] = 'Edge';
        }

        // Device detection
        if (preg_match('/Mobile/', $userAgent)) {
            $result['device'] = 'Mobile';
        } elseif (preg_match('/Tablet/', $userAgent)) {
            $result['device'] = 'Tablet';
        } else {
            $result['device'] = 'Desktop';
        }

        // Platform detection
        if (preg_match('/Windows/', $userAgent)) {
            $result['platform'] = 'Windows';
        } elseif (preg_match('/Mac OS X/', $userAgent)) {
            $result['platform'] = 'macOS';
        } elseif (preg_match('/Linux/', $userAgent)) {
            $result['platform'] = 'Linux';
        } elseif (preg_match('/Android/', $userAgent)) {
            $result['platform'] = 'Android';
        } elseif (preg_match('/iPhone|iPad/', $userAgent)) {
            $result['platform'] = 'iOS';
        }

        return $result;
    }

    /**
     * Get location info from IP address
     */
    private function getLocationInfo($ip)
    {
        $result = [
            'location' => 'Unknown',
            'latitude' => null,
            'longitude' => null,
            'country' => null,
            'city' => null,
            'isp' => null,
        ];

        // For demo purposes, we'll use a simple IP geolocation
        // In production, you might want to use a service like MaxMind or IPinfo
        
        try {
            // This is a simplified example - you would typically use a real IP geolocation service
            if ($ip === '127.0.0.1' || $ip === '::1') {
                $result['location'] = 'Localhost';
                $result['country'] = 'Local';
            } else {
                // Use a free IP geolocation API for demo
                $response = Http::timeout(5)->get("http://ip-api.com/json/{$ip}");
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    if ($data['status'] === 'success') {
                        $result['location'] = "{$data['city']}, {$data['country']}";
                        $result['latitude'] = $data['lat'];
                        $result['longitude'] = $data['lon'];
                        $result['country'] = $data['country'];
                        $result['city'] = $data['city'];
                        $result['isp'] = $data['isp'] ?? null;
                    }
                }
            }
        } catch (\Exception $e) {
            // If geolocation fails, we'll just use basic info
            \Log::warning('IP geolocation failed: ' . $e->getMessage());
        }

        return $result;
    }
}