<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AirdropClaim;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\AirdropClaimApprovedMail;
use App\Mail\AirdropClaimRejectedMail;

class AirdropClaimController extends Controller
{
    public function index()
    {
        $claims = AirdropClaim::with('user','airdrop')->latest()->paginate(20);
        return view('admin.airdrops.claims', compact('claims'));
    }

    public function approve(Request $request, $id)
    {
        $claim = AirdropClaim::with('airdrop','user')->findOrFail($id);

        if ($claim->status !== 'pending') {
            return back()->with('error','Claim already processed.');
        }

        DB::beginTransaction();
        try {
            // credit user's wallet for airdrop currency
            $airdrop = $claim->airdrop;
            $currencyId = $airdrop->airdrop_currency_id;

            $wallet = Wallet::firstOrCreate(
                ['user_id'=>$claim->user_id,'cryptocurrency_id'=>$currencyId],
                ['address'=>null,'balance'=>0,'locked_balance'=>0]
            );

            // add amount
            $wallet->balance = bcadd((string)$wallet->balance, (string)$claim->claim_amount, 18);
            $wallet->save();

            // transaction
            Transaction::create([
                'user_id' => $claim->user_id,
                'cryptocurrency_id' => $currencyId,
                'type' => 'airdrop',
                'amount' => $claim->claim_amount,
                'fee' => 0,
                'balance_before' => bcsub((string)$wallet->balance, (string)$claim->claim_amount, 18),
                'balance_after' => (string)$wallet->balance,
                'description' => "Airdrop reward: {$airdrop->title}",
                'status' => 'completed'
            ]);

            $claim->status = 'approved';
            $claim->claimed_at = now();
            $claim->save();

            DB::commit();

            // email notify
            Mail::to($claim->user->email)->send(new AirdropClaimApprovedMail($claim));

            return back()->with('success','Claim approved & user credited.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error','Failed to approve claim: '.$e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['reason'=>'required|string|max:500']);
        $claim = AirdropClaim::with('user','airdrop')->findOrFail($id);

        if ($claim->status !== 'pending') {
            return back()->with('error','Claim already processed.');
        }

        $claim->status = 'rejected';
        $claim->admin_reason = $request->reason;
        $claim->save();

        Mail::to($claim->user->email)->send(new AirdropClaimRejectedMail($claim));

        return back()->with('success','Claim rejected.');
    }
}
