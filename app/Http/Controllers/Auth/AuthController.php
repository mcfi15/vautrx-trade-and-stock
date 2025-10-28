<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\LoginHistoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;



class AuthController extends Controller
{
    protected $loginHistoryService;

    public function __construct(LoginHistoryService $loginHistoryService)
    {
        $this->loginHistoryService = $loginHistoryService;
    }
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'g-recaptcha-response' => ['required'],
        ]);

        // Verify reCAPTCHA
        if ($this->shouldValidateReCaptcha()) {
            $validationResult = $this->validateReCaptcha($request->input('g-recaptcha-response'), $request->ip());
            
            if (!$validationResult['success']) {
                return back()
                    ->withErrors(['g-recaptcha-response' => $validationResult['error']])
                    ->withInput();
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verification_token' => Str::random(64),
            'email_verification_token_expires_at' => now()->addHours(24),
        ]);

        // Send verification email
        try {
            Mail::to($user->email)->send(new \App\Mail\EmailVerificationMail($user, $user->email_verification_token));
        } catch (\Exception $e) {
            // Log error but don't interrupt registration
            \Log::error('Failed to send verification email: ' . $e->getMessage());
        }

        // Don't auto-login user - they need to verify email first
        return redirect()->route('login')
            ->with('success', 'Registration successful! Please check your email and click the verification link to activate your account.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }



    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'g-recaptcha-response' => ['required'],
        ]);

        // Verify reCAPTCHA
        if ($this->shouldValidateReCaptcha()) {
            $validationResult = $this->validateReCaptcha($request->input('g-recaptcha-response'), $request->ip());
            
            if (!$validationResult['success']) {
                // Track failed login attempt
                $credentials = $request->only('email', 'password');
                $user = User::where('email', $request->email)->first();
                
                if ($user) {
                    $this->loginHistoryService->trackLogin($user, $request, false, 'reCAPTCHA verification failed');
                }
                
                return back()
                    ->withErrors(['g-recaptcha-response' => $validationResult['error']])
                    ->withInput();
            }
        }

        $credentials = $request->only('email', 'password');
        $user = User::where('email', $request->email)->first();

        // Debug: Check user status before login attempt
        if ($user) {
            \Log::info('User login attempt status:', [
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'email_verified_for_login_at' => $user->email_verified_for_login_at,
                'is_active' => $user->is_active
            ]);
        } else {
            \Log::info('User not found for email:', ['email' => $request->email]);
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
    $request->session()->regenerate();

    $authenticatedUser = Auth::user();

    if (is_null($authenticatedUser->email_verified_at)) {
        \Log::info('User not verified. Redirecting to verification page.', [
            'user' => $authenticatedUser->email
        ]);

        // Store user email in session (do not logout yet)
        session(['pending_verification_email' => $authenticatedUser->email]);

        return redirect()
            ->route('verification.notice')
            ->with('error', 'Please verify your email address before logging in.');
    }

    // Normal login flow
    $authenticatedUser->update([
        'last_login_at' => now(),
        'last_login_ip' => $request->ip(),
        'email_verified_for_login_at' => $authenticatedUser->email_verified_for_login_at ?? now(),
    ]);

    $this->loginHistoryService->trackLogin($authenticatedUser, $request, true);

    return $authenticatedUser->is_admin
        ? redirect()->route('admin.dashboard')
        : redirect()->intended(route('dashboard'));
}


        // Track failed login attempt
        if ($user) {
            $this->loginHistoryService->trackLogin($user, $request, false, 'Invalid credentials');
        }

        \Log::info('Login failed - invalid credentials', ['email' => $request->email]);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Determine if reCAPTCHA validation should be performed
     */
    private function shouldValidateReCaptcha(): bool
    {
        // Skip if explicitly disabled in .env
        if (config('app.env') !== 'local' && env('RECAPTCHA_ENABLED') === 'false') {
            return false;
        }

        // Skip for development testing
        if (config('app.env') === 'local' && env('FAKE_RECAPTCHA') === 'true') {
            return false;
        }

        return true;
    }

    /**
     * Validate reCAPTCHA response using HTTP request (no external library required)
     */
    private function validateReCaptcha(string $response, string $remoteIp): array
    {
        $secret = env('RECAPTCHA_SECRET_KEY');
        
        // Skip validation if no secret key
        if (empty($secret)) {
            return ['success' => true, 'error' => ''];
        }

        // For local development with test keys
        if ($secret === '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe') {
            // Simple validation for test key
            if (empty($response) || strlen($response) < 10) {
                return ['success' => false, 'error' => 'reCAPTCHA verification failed.'];
            }
            return ['success' => true, 'error' => ''];
        }

        try {
            // Use Laravel's HTTP client to verify reCAPTCHA
            $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
            
            $response = \Http::asForm()->post($verifyUrl, [
                'secret' => $secret,
                'response' => $response,
                'remoteip' => $remoteIp,
            ]);

            if (!$response->successful()) {
                // HTTP request failed, allow login to proceed
                return ['success' => true, 'error' => ''];
            }

            $data = $response->json();
            
            if (isset($data['success']) && $data['success'] === true) {
                return ['success' => true, 'error' => ''];
            } else {
                $errorMessage = 'reCAPTCHA verification failed.';
                
                if (isset($data['error-codes']) && is_array($data['error-codes'])) {
                    $errorMessage = 'reCAPTCHA verification failed: ' . implode(', ', $data['error-codes']);
                }
                
                return ['success' => false, 'error' => $errorMessage];
            }
        } catch (\Exception $e) {
            // Log the error but don't block the user
            \Log::error('reCAPTCHA validation error: ' . $e->getMessage());
            
            // Allow login to proceed in case of validation service errors
            return ['success' => true, 'error' => ''];
        }
    }
}
