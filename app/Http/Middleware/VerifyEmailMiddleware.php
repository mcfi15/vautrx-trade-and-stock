<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyEmailMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Skip email verification for admin users
        if ($user->is_admin) {
            return $next($request);
        }

        // Skip email verification in development mode if disabled
        if (app()->environment('local') && env('DISABLE_EMAIL_VERIFICATION') === 'true') {
            return $next($request);
        }

        // Define routes that require email verification (trading and financial actions only)
        $verificationRequiredRoutes = [
            'trading.order',
            'trading.cancel',
            'trading.orders',
            'trading.trades',
            'wallet.withdraw',
            'wallet.process',
            'wallet.transactions',
            'wallet.deposits.manual',
            'wallet.deposits.payment-proof',
        ];

        // Check if current route requires email verification
        $currentRouteName = $request->route()->getName();
        $currentPath = $request->path();

        $requiresVerification = false;
        
        // Check route name patterns
        foreach ($verificationRequiredRoutes as $pattern) {
            if (str_contains($currentRouteName, $pattern) || str_contains($currentPath, $pattern)) {
                $requiresVerification = true;
                break;
            }
        }

        // Check if email is verified
        if ($requiresVerification && !$user->email_verified_at) {
            return redirect()->route('verification.notice')
                ->with('error', 'Please verify your email address to access trading and wallet features.');
        }

        return $next($request);
    }
}