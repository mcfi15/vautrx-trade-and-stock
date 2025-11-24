<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class FundPasswordController extends Controller
{
    public function index()
    {
        return view('user.setting.fund-password');
    }

    /**
     * SEND OTP TO USER EMAIL
     */
    public function sendOtp(Request $request)
    {
        $user = Auth::user();

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);

        // Save OTP in database
        $user->fund_password_otp = $otp;
        $user->fund_password_otp_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        // Send email
        Mail::to($user->email)->send(new \App\Mail\FundPasswordOtpMail($otp));

        return response()->json([
            'success' => true,
            'message' => 'OTP has been sent to your email.'
        ]);
    }

    /**
     * UPDATE FUND PASSWORD
     */
    public function update(Request $request)
    {
        $request->validate([
            // 'captcha' => 'required',
            'otp'       => 'required',
            'password'  => 'required|min:4',
        ]);

        $user = Auth::user();

        // DEBUGGING (remove after confirmed working)
        // dd([
        //     'otp_in_db' => $user->fund_password_otp,
        //     'otp_input' => $request->otp,
        //     'expires_at' => $user->fund_password_otp_expires_at,
        //     'is_past' => $user->fund_password_otp_expires_at ? $user->fund_password_otp_expires_at->isPast() : null,
        // ]);

        // OTP VALIDATION
        if (
            empty($user->fund_password_otp) ||
            $user->fund_password_otp != $request->otp ||
            !$user->fund_password_otp_expires_at ||
            $user->fund_password_otp_expires_at->isPast()
        ) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP']);
        }

        // Update fund password
        $user->fund_password = bcrypt($request->password);

        // Clear OTP
        $user->fund_password_otp = null;
        $user->fund_password_otp_expires_at = null;
        $user->save();

        return redirect()->back()->with('success', 'Fund password updated successfully!');
    }
}
