<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\KycApprovedMail;
use App\Mail\KycRejectedMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class AdminKycController extends Controller
{
    public function index()
    {
        $users = User::where('kyc_status', 'pending')->paginate(20);
        return view('admin.kyc.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('admin.kyc.show', compact('user'));
    }

    public function approve(User $user)
    {
        $user->update([
            'kyc_status' => 'approved',
            'kyc_verified' => true,
        ]);

        Mail::to($user->email)->send(new KycApprovedMail($user));

        return back()->with('success', 'KYC approved successfully.');
    }

    public function reject(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $user->update([
            'kyc_status' => 'rejected',
            'kyc_rejection_reason' => $request->reason,
        ]);

        Mail::to($user->email)->send(new KycRejectedMail($user, $request->reason));

        return back()->with('error', 'KYC has been rejected.');
    }
}
