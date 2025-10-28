<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmailVerificationController extends Controller
{
    public function verify(Request $request, $token)
    {
        $user = User::where('email_verification_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Invalid verification token.');
        }

        if ($user->email_verified_at) {
            return redirect()->route('dashboard')
                ->with('info', 'Your email has already been verified.');
        }

        if ($user->email_verification_token_expires_at && $user->email_verification_token_expires_at->isPast()) {
            return redirect()->route('login')
                ->with('error', 'Verification token has expired. Please request a new one.');
        }

        $user->update([
            'email_verified_at' => now(),
            'email_verification_token' => null,
            'email_verification_token_expires_at' => null,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Your email has been verified successfully!');
    }

    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at) {
            return redirect()->route('login')
                ->with('info', 'Your email has already been verified.');
        }

        $token = Str::random(64);
        $expiresAt = now()->addHours(24);

        $user->update([
            'email_verification_token' => $token,
            'email_verification_token_expires_at' => $expiresAt,
        ]);

        try {
            Mail::to($user->email)->send(new \App\Mail\EmailVerificationMail($user, $token));
            
            return back()->with('success', 'Verification email has been sent. Please check your inbox.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send verification email. Please try again.');
        }
    }

    public function showVerificationNotice()
    {
        // Try to get authenticated user first, fallback to session data
        $user = Auth::user();
        
        // If not authenticated but we have email from login redirect, get user by email
        if (!$user && session('user_email')) {
            $user = User::where('email', session('user_email'))->first();
        }
        
        // If still no user found, redirect to login
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Session expired. Please log in again.');
        }
        
        // If email is already verified, redirect to dashboard
        if ($user->email_verified_at) {
            return redirect()->route('dashboard');
        }

        return view('auth.verify-email', compact('user'));
    }
}