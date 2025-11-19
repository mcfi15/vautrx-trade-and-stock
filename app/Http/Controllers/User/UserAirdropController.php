<?php

namespace App\Http\Controllers\User;

use App\Models\Wallet;
use App\Models\Airdrop;
use App\Models\Transaction;
use App\Models\AirdropClaim;
use Illuminate\Http\Request;
use App\Mail\AirdropClaimedMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AirdropClaimApprovedMail; // used later if admin approves via queue/send

class UserAirdropController extends Controller
{
    public function index()
    {
        $now = now();

        $processing = Airdrop::where('is_active', [1, '1', true, 'true'])
            ->where('start_at', '<=', $now)
            ->where('end_at', '>=', $now)
            ->orderBy('start_at', 'asc')
            ->get();

        $upcoming = Airdrop::where('is_active', [1, '1', true, 'true'])
            ->where('start_at', '>', $now)
            ->orderBy('start_at', 'asc')
            ->get();

        $ended = Airdrop::where('end_at', '<', $now)
            ->orderBy('end_at', 'desc')
            ->get();

        return view('airdrop.index', compact('processing', 'upcoming', 'ended'));
    }

    public function show(Airdrop $airdrop)
    {
        return view('airdrop.show', compact('airdrop'));
    }

    public function claim(Request $request, Airdrop $airdrop)
    {
        $user = Auth::user();
        // check active and within time
        if (!$airdrop->is_active || ($airdrop->start_at && $airdrop->start_at > now()) || ($airdrop->end_at && $airdrop->end_at < now())) {
            return redirect()->back()->with('error','Airdrop not active.');
        }

        // Ensure user hasn't already claimed
        if (AirdropClaim::where('airdrop_id', $airdrop->id)->where('user_id', $user->id)->exists()) {
            return redirect()->back()->with('error','You have already claimed this airdrop.');
        }

        // Eligibility check: does user hold required currency?
        $holdingCurrencyId = $airdrop->holding_currency_id;
        if ($holdingCurrencyId) {
            $wallet = Wallet::where('user_id', $user->id)->where('cryptocurrency_id', $holdingCurrencyId)->first();
            $available = $wallet ? $wallet->available_balance : 0;
            if (bccomp((string)$available, (string)$airdrop->min_hold_amount, 18) < 0) {
                return redirect()->back()->with('error','You do not meet the minimum holding requirement to claim this airdrop.');
            }
        }

        $claim = AirdropClaim::create([
            'airdrop_id' => $airdrop->id,
            'user_id' => $user->id,
            'claim_amount' => $airdrop->airdrop_amount,
            'status' => 'pending',
        ]);

        Mail::to($user->email)->send(new AirdropClaimedMail($user, $airdrop, $airdrop->airdrop_amount));

        return redirect()->back()->with('success','Claim submitted. Awaiting admin approval.');
    }
}