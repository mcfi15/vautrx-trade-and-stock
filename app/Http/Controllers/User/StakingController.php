<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\Wallet;
use App\Models\StakePlan;
use App\Models\UserStake;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Mail\StakeCreatedMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class StakingController extends Controller
{
    // show user-facing page (you may route this to the blade you provided)
    public function staking()
    {
        $plans = StakePlan::with('cryptocurrency')->where('is_active', true)->get();

        // load user balances for each crypto (if logged in)
        $userBalances = [];
        if (Auth::check()) {
            $userId = Auth::id();
            $wallets = Wallet::where('user_id', $userId)->get();
            foreach ($wallets as $w) {
                $userBalances[$w->cryptocurrency_id] = (string)$w->available_balance;
            }
        }

        return view('invest.index', compact('plans','userBalances'));
    }

    // handle staking submission (AJAX or standard POST)
    public function invest(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:stake_plans,id',
            'duration' => 'required|integer',
            'amount' => 'required|numeric|min:0.00000001',
        ]);

        $user = Auth::user();
        $plan = StakePlan::findOrFail($request->plan_id);

        // Validate duration
        if (!in_array($request->duration, $plan->durations)) {
            return redirect()->back()->with('error', 'Invalid duration selected.');
        }

        // Check wallet balance
        $wallet = Wallet::where('user_id', $user->id)
                        ->where('cryptocurrency_id', $plan->cryptocurrency_id)
                        ->first();

        if (!$wallet || $request->amount > $wallet->available_balance) {
            return redirect()->back()->with('error', 'Insufficient balance.');
        }

        if ($request->amount < $plan->min_amount) {
            return redirect()->back()->with('error', 'Amount below minimum required.');
        }

        // Deduct balance
        $wallet->subtractBalance($request->amount);

        // Create user stake
        $endsAt = now()->addDays($request->duration);
        $userStake = UserStake::create([
            'user_id' => $user->id,
            'stake_plan_id' => $plan->id,
            'cryptocurrency_id' => $plan->cryptocurrency_id,
            'amount' => $request->amount,
            'duration' => $request->duration,
            'yield_percent' => $plan->percent,
            'status' => 'pending',
            'started_at' => now(),
            'ends_at' => $endsAt,
        ]);

        // Create transaction
        Transaction::create([
            'user_id' => $user->id,
            'cryptocurrency_id' => $plan->cryptocurrency_id,
            'type' => 'stake',
            'amount' => $request->amount,
            'balance_before' => $wallet->balance + $request->amount,
            'balance_after' => $wallet->balance,
            'status' => 'completed',
            'description' => 'Staked ' . $request->amount . ' ' . $plan->cryptocurrency->symbol,
        ]);

        Mail::to($user->email)->send(new StakeCreatedMail($userStake));

        return redirect()->back()->with('success', 'Stake successful!');
    }
}
