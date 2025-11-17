<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StakePlan;
use App\Models\UserStake;
use App\Models\Wallet;
use App\Models\Cryptocurrency;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class StakingController extends Controller
{
    // Show all plans and user stakes
    public function index()
    {
        $stakes = StakePlan::all();
        $userStakes = UserStake::with(['user', 'cryptocurrency'])->get();

        return view('admin.stakes.index', compact('stakes', 'userStakes'));
    }

    // Show create form
    public function create()
    {
        $coins = Cryptocurrency::active()->get();
        return view('admin.stakes.create', compact('coins'));
    }

    // Store new plan
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cryptocurrency_id' => 'required|exists:cryptocurrencies,id',
            'percent' => 'required|numeric|min:0',
            'lock_period' => 'required|integer|min:1',
            'minimum_amount' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        StakePlan::create($request->all());

        return redirect()->route('admin.stakes.index')->with('success', 'Stake plan created.');
    }

    // Show edit form
    public function edit(StakePlan $stake)
    {
        $coins = Cryptocurrency::active()->get();
        return view('admin.stakes.edit', compact('stake', 'coins'));
    }

    // Update plan
    public function update(Request $request, StakePlan $stake)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cryptocurrency_id' => 'required|exists:cryptocurrencies,id',
            'percent' => 'required|numeric|min:0',
            'lock_period' => 'required|integer|min:1',
            'minimum_amount' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        $stake->update($request->all());

        return redirect()->route('admin.stakes.index')->with('success', 'Stake plan updated.');
    }

    // Delete plan
    public function destroy(StakePlan $stake)
    {
        $stake->delete();
        return redirect()->route('admin.stakes.index')->with('success', 'Stake plan deleted.');
    }

    // Approve user stake
    public function approve(UserStake $userStake)
    {
        if ($userStake->status !== 'pending') {
            return back()->with('error', 'Stake already processed.');
        }

        $userStake->status = 'approved';
        $userStake->save();

        // Credit user wallet
        $wallet = Wallet::firstOrCreate([
            'user_id' => $userStake->user_id,
            'cryptocurrency_id' => $userStake->cryptocurrency_id,
        ]);

        $wallet->addBalance($userStake->amount);

        // Log transaction
        Transaction::create([
            'user_id' => $userStake->user_id,
            'cryptocurrency_id' => $userStake->cryptocurrency_id,
            'type' => 'stake',
            'amount' => $userStake->amount,
            'balance_before' => $wallet->balance - $userStake->amount,
            'balance_after' => $wallet->balance,
            'description' => 'Stake approved: '.$userStake->id,
            'status' => 'completed',
        ]);

        // Send email
        Mail::raw("Your stake of {$userStake->amount} {$userStake->cryptocurrency->symbol} has been approved.", function ($message) use ($userStake) {
            $message->to($userStake->user->email)
                    ->subject('Stake Approved');
        });

        return back()->with('success', 'Stake approved and credited.');
    }

    // Reject user stake
    public function reject(Request $request, UserStake $userStake)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $userStake->status = 'rejected';
        $userStake->rejection_reason = $request->reason;
        $userStake->save();

        // Send email
        Mail::raw("Your stake of {$userStake->amount} {$userStake->cryptocurrency->symbol} has been rejected. Reason: {$request->reason}", function ($message) use ($userStake) {
            $message->to($userStake->user->email)
                    ->subject('Stake Rejected');
        });

        return back()->with('success', 'Stake rejected.');
    }
}

