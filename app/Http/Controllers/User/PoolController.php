<?php

namespace App\Http\Controllers\User;

use App\Models\Wallet;
use App\Models\MiningPool;
use App\Models\Transaction;
use App\Models\MiningReward;
use Illuminate\Http\Request;
use App\Models\Cryptocurrency;
use App\Models\UserMiningMachine;
use App\Mail\MiningRewardReceived;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\MiningPurchaseConfirmed;

class PoolController extends Controller
{
    public function index()
    {
        $pools = MiningPool::active()->get();
        return view('pool.index', compact('pools'));
    }

    public function myMachines()
    {
        $machines = UserMiningMachine::with('miningPool')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
            
        return view('pool.my-machines', compact('machines'));
    }

    public function myRewards()
    {
        $rewards = MiningReward::with('miningPool')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
            
        return view('pool.my-rewards', compact('rewards'));
    }

    public function rent(Request $request, $poolId)
    {
        $request->validate([
            'amount' => 'required|integer|min:1'
        ]);

        return DB::transaction(function () use ($request, $poolId) {
            $pool = MiningPool::active()->findOrFail($poolId);
            $user = Auth::user();
            $amount = $request->amount;

            // Check availability
            if ($pool->available < $amount) {
                return back()->with('error', 'Not enough machines available.');
            }

            // Check user limit
            if ($pool->user_limit > 0) {
                $userMachinesCount = UserMiningMachine::where('user_id', $user->id)
                    ->where('mining_pool_id', $pool->id)
                    ->count();
                    
                if ($userMachinesCount + $amount > $pool->user_limit) {
                    return back()->with('error', 'You have reached your limit for this pool.');
                }
            }

            $totalCost = $pool->price * $amount;
            $dailyReward = $pool->daily_reward * $amount;

            // Get user's LTC wallet
            $ltcWallet = $user->getWalletBySymbol('LTC');
            if (!$ltcWallet || $ltcWallet->available_balance < $totalCost) {
                return back()->with('error', 'Insufficient LTC balance.');
            }

            // Deduct balance
            if (!$ltcWallet->subtractBalance($totalCost)) {
                return back()->with('error', 'Failed to process payment.');
            }

            // Create mining machine record
            $miningMachine = UserMiningMachine::create([
                'user_id' => $user->id,
                'mining_pool_id' => $pool->id,
                'quantity' => $amount,
                'total_cost' => $totalCost,
                'daily_reward' => $dailyReward,
                'start_date' => now(),
                'end_date' => now()->addDays($pool->duration_days),
                'status' => 'active'
            ]);

            // Update pool availability
            $pool->decrement('available', $amount);

            // Create transaction record
            Transaction::create([
                'user_id' => $user->id,
                'cryptocurrency_id' => $this->getLtcCryptoId(),
                'type' => 'mining_purchase',
                'amount' => $totalCost,
                'fee' => 0,
                'balance_before' => $ltcWallet->balance + $totalCost,
                'balance_after' => $ltcWallet->balance,
                'status' => 'completed',
                'description' => "Purchased {$amount} {$pool->name} mining machine(s)"
            ]);

            // Send confirmation email
            Mail::to($user->email)->send(new MiningPurchaseConfirmed($miningMachine));

            return redirect()->route('pool.myMachines')->with('success', 'Mining machines rented successfully!');
        });
    }

    public function claimReward($rewardId)
    {
        return DB::transaction(function () use ($rewardId) {
            $reward = MiningReward::where('user_id', Auth::id())
                ->where('is_paid', false)
                ->findOrFail($rewardId);

            $user = Auth::user();
            $ltcWallet = $user->getWalletBySymbol('LTC');

            if (!$ltcWallet) {
                return back()->with('error', 'LTC wallet not found.');
            }

            // Add reward to wallet
            $ltcWallet->addBalance($reward->amount);

            // Update reward status
            $reward->update([
                'is_paid' => true,
                'paid_at' => now()
            ]);

            // Create transaction record
            Transaction::create([
                'user_id' => $user->id,
                'cryptocurrency_id' => $this->getLtcCryptoId(),
                'type' => 'mining_reward',
                'amount' => $reward->amount,
                'fee' => 0,
                'balance_before' => $ltcWallet->balance - $reward->amount,
                'balance_after' => $ltcWallet->balance,
                'status' => 'completed',
                'description' => "Mining reward from {$reward->miningPool->name}"
            ]);

            // Send reward email
            Mail::to($user->email)->send(new MiningRewardReceived($reward));

            return back()->with('success', 'Reward claimed successfully!');
        });
    }

    private function getLtcCryptoId()
    {
        $ltc = Cryptocurrency::where('symbol', 'LTC')->first();
        return $ltc ? $ltc->id : 1; // Fallback to ID 1 if LTC not found
    }
}