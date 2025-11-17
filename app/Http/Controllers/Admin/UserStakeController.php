<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Wallet;
use App\Models\UserStake;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Mail\StakeApprovedMail;
use App\Mail\StakeRejectedMail;
use App\Mail\StakeCompletedMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class UserStakeController extends Controller
{
    public function index()
    {
        $stakes = UserStake::with(['user','plan','cryptocurrency'])->latest()->paginate(30);
        return view('admin.user_stakes.index', compact('stakes'));
    }

    public function approve($id)
    {
        $stake = UserStake::findOrFail($id);

        if ($stake->status !== 'pending') {
            return back()->with('error', 'Stake already processed.');
        }

        $stake->status = 'approved';
        $stake->started_at = now();
        $stake->ends_at = now()->addDays($stake->duration);
        $stake->save();

        // Email
        Mail::to($stake->user->email)->send(new StakeApprovedMail($stake));

        return back()->with('success', 'Stake approved.');
    }


    // Complete: release locked funds + credit reward + create transaction
    public function complete($id)
    {
        
        $stake = UserStake::findOrFail($id);

        if ($stake->status !== 'approved') {
            return back()->with('error', 'Stake not approved or already completed.');
        }

        DB::beginTransaction();
        try {
            // compute reward: simple interest for the lock period relative to yearly percent
            // reward = amount * (percent/100) * (duration/365)
            $reward = bcmul((string)$stake->amount, bcdiv((string)$stake->yield_percent, '100', 8), 18);
            $reward = bcmul((string)$reward, bcdiv((string)$stake->duration, '365', 8), 18);

            // Update wallet: reduce locked_balance, add back amount and reward to balance
            $wallet = Wallet::where('user_id', $stake->user_id)
                    ->where('cryptocurrency_id', $stake->cryptocurrency_id)
                    ->lockForUpdate()
                    ->firstOrFail();

            // ensure locked_balance is sufficient
            $wallet->locked_balance = bcsub((string)$wallet->locked_balance, (string)$stake->amount, 18);
            if (bccomp($wallet->locked_balance, '0', 18) < 0) {
                $wallet->locked_balance = '0';
            }

            $wallet->balance = bcadd((string)$wallet->balance, bcadd((string)$stake->amount, (string)$reward, 18), 18);
            $wallet->save();

            // create transaction record for reward
            Transaction::create([
                'user_id' => $stake->user_id,
                'cryptocurrency_id' => $stake->cryptocurrency_id,
                'type' => 'stake_reward',
                'amount' => $reward,
                'fee' => 0,
                'balance_before' => $wallet->balance - $reward,
                'balance_after' => $wallet->balance,
                'description' => "Stake reward for stake #{$stake->id}",
                'status' => 'completed',
            ]);

            $stake->status = 'completed';
            $stake->completed_at = Carbon::now();
            $stake->save();

            DB::commit();

            Mail::to($stake->user->email)->send(new StakeCompletedMail($stake, $reward));
            
            return back()->with('success', 'Stake completed and reward credited.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to complete stake: '.$e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string']);
        $stake = UserStake::findOrFail($id);

        DB::beginTransaction();
        try {
            // unlock funds back to balance
            $wallet = Wallet::where('user_id', $stake->user_id)
                        ->where('cryptocurrency_id', $stake->cryptocurrency_id)
                        ->lockForUpdate()->firstOrFail();

            $wallet->locked_balance = bcsub((string)$wallet->locked_balance, (string)$stake->amount, 18);
            if (bccomp($wallet->locked_balance, '0', 18) < 0) {
                $wallet->locked_balance = '0';
            }
            $wallet->balance = bcadd((string)$wallet->balance, (string)$stake->amount, 18);
            $wallet->save();

            // create transaction record for unlock/refund
            Transaction::create([
                'user_id' => $stake->user_id,
                'cryptocurrency_id' => $stake->cryptocurrency_id,
                'type' => 'stake_rejected_refund',
                'amount' => $stake->amount,
                'fee' => 0,
                'balance_before' => $wallet->balance - $stake->amount,
                'balance_after' => $wallet->balance,
                'description' => "Stake rejected; funds returned for stake #{$stake->id}",
                'status' => 'completed',
            ]);

            $stake->status = 'rejected';
            $stake->rejection_reason = $request->reason;
            $stake->save();

            DB::commit();

            Mail::to($stake->user->email)->send(new StakeRejectedMail($stake));

            return back()->with('success', 'Stake rejected and funds returned.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reject stake: '.$e->getMessage());
        }
    }
}
