<?php

namespace App\Http\Controllers\User;

use App\Models\TradingPair;
use Illuminate\Http\Request;
use App\Mail\KycSubmittedMail;
use App\Mail\PasswordChangedMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SettingController extends Controller
{
    public function index(){
        return view('user.setting.index');
    }

    public function kyc(){
        $user = Auth::user();
        return view('user.setting.kyc', compact('user'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'kyc_full_name'       => 'required|string|max:255',
            'kyc_document_type'   => 'required|string',
            'kyc_document_number' => 'required|string',

            'kyc_front'  => 'required|mimes:jpg,png,jpeg,pdf|max:4096',
            'kyc_back'   => 'required|mimes:jpg,png,jpeg,pdf|max:4096',
            'kyc_selfie' => 'required|mimes:jpg,png,jpeg,pdf|max:4096',
            'kyc_proof'  => 'required|mimes:jpg,png,jpeg,pdf|max:4096',
        ]);

        $user = Auth::user();

        // === File Uploads ===
        $uploadPath = 'uploads/kyc/';

        // FRONT
        if ($request->hasFile('kyc_front')) {
            $file = $request->file('kyc_front');
            $ext = $file->getClientOriginalExtension();
            $filename_front = time().'_front.'.$ext;
            $file->move($uploadPath, $filename_front);
        }

        // BACK
        if ($request->hasFile('kyc_back')) {
            $file = $request->file('kyc_back');
            $ext = $file->getClientOriginalExtension();
            $filename_back = time().'_back.'.$ext;
            $file->move($uploadPath, $filename_back);
        }

        // SELFIE
        if ($request->hasFile('kyc_selfie')) {
            $file = $request->file('kyc_selfie');
            $ext = $file->getClientOriginalExtension();
            $filename_selfie = time().'_selfie.'.$ext;
            $file->move($uploadPath, $filename_selfie);
        }

        // PROOF OF RESIDENCE
        if ($request->hasFile('kyc_proof')) {
            $file = $request->file('kyc_proof');
            $ext = $file->getClientOriginalExtension();
            $filename_proof = time().'_proof.'.$ext;
            $file->move($uploadPath, $filename_proof);
        }

        // === Update User ===
        $user->update([
            'kyc_full_name'       => $request->kyc_full_name,
            'kyc_document_type'   => $request->kyc_document_type,
            'kyc_document_number' => $request->kyc_document_number,
            'kyc_status'          => 'pending',

            'kyc_front'  => $uploadPath . $filename_front,
            'kyc_back'   => $uploadPath . $filename_back,
            'kyc_selfie' => $uploadPath . $filename_selfie,
            'kyc_proof'  => $uploadPath . $filename_proof,
        ]);

        // Send email to user
        Mail::to($user->email)->send(new KycSubmittedMail($user));

        return back()->with('success', 'KYC submitted successfully. Please wait for verification.');
    }


    public function fees(){
        $pairs = TradingPair::with(['baseCurrency', 'quoteCurrency'])
            ->where('is_active', true)
            ->get();
        return view('user.setting.fees', compact('pairs'));
    }

    public function log(){
        $loginHistories = Auth::user()->loginHistories()->paginate(20);
        return view('user.setting.login-history', compact('loginHistories'));
    }

    public function security(){
        return view('user.setting.security');
    }

    public function changePassword(){
        return view('user.setting.change-password');
    }

    public function changePasswordUpdate(Request $request)
    {
        $request->validate([
            'oldpassword' => 'required',
            'newpassword' => 'required|min:6',
            'repassword' => 'required|same:newpassword',
        ]);

        $user = Auth::user();

        // Check old password
        if (!Hash::check($request->oldpassword, $user->password)) {
            return back()->with('error', 'Old password is incorrect.');
        }

        // Update password
        $user->password = Hash::make($request->newpassword);
        $user->save();

        // Send email notification
        Mail::to($user->email)->send(new PasswordChangedMail($user));

        return back()->with('success', 'Password changed successfully.');
    }
}
