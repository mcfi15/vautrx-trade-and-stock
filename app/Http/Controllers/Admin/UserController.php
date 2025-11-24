<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by auth provider
        if ($request->has('auth_provider') && $request->auth_provider !== '') {
            $query->where('auth_provider', $request->auth_provider);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['wallets.cryptocurrency', 'orders', 'transactions']);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Toggle user status.
     */
    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "User {$status} successfully.");
    }

    /**
     * Display user wallets.
     */
    public function wallets(User $user)
    {
        $wallets = $user->wallets()->with('cryptocurrency')->get();

        return view('admin.users.wallets', compact('user', 'wallets'));
    }

    public function updateWalletForm(User $user, $walletId)
    {
        $wallet = Wallet::with('cryptocurrency')->findOrFail($walletId);

        return view('admin.users.wallet-edit', compact('user', 'wallet'));
    }

    public function updateWalletBalance(Request $request, User $user, $walletId)
    {
        $wallet = Wallet::with('cryptocurrency')->findOrFail($walletId);

        $request->validate([
            'type' => 'required|in:credit,debit',
            'amount' => 'required|numeric|min:0.00000001',
            'note' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $oldBalance = $wallet->balance;

            if ($request->type === 'credit') {
                $wallet->balance = bcadd($wallet->balance, $request->amount, 18);
            } else { // debit
                if (bccomp($wallet->balance, $request->amount, 18) < 0) {
                    return back()->with('error', 'Insufficient balance for debit.');
                }
                $wallet->balance = bcsub($wallet->balance, $request->amount, 18);
            }

            $wallet->save();

            // Log transaction
            // Transaction::create([
            //     'user_id' => $user->id,
            //     'cryptocurrency_id' => $wallet->cryptocurrency_id,
            //     'type' => $request->type,
            //     'amount' => $request->amount,
            //     'balance_before' => $oldBalance,
            //     'balance_after' => $wallet->balance,
            //     'description' => $request->note ?? "Admin {$request->type} operation",
            //     'status' => 'completed',
            // ]);

            DB::commit();

            return back()->with('success', "Wallet successfully {$request->type}ed.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Wallet update failed', [
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to update wallet.');
        }
    }


    public function updateWithdrawalPermission(Request $request, User $user)
    {
        $request->validate([
            'withdrawal_permission' => 'required|in:active,suspended,exceed_limit'
        ]);

        try {
            $user->updateWithdrawalPermission($request->withdrawal_permission);
            
            return redirect()->back()->with('success', 'Withdrawal permission updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update withdrawal permission.');
        }
    }

    /**
     * Suspend user withdrawals
     */
    public function suspendWithdrawals(User $user)
    {
        try {
            $user->suspendWithdrawals();
            return redirect()->back()->with('success', 'User withdrawals suspended successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to suspend withdrawals.');
        }
    }

    /**
     * Activate user withdrawals
     */
    public function activateWithdrawals(User $user)
    {
        try {
            $user->activateWithdrawals();
            return redirect()->back()->with('success', 'User withdrawals activated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to activate withdrawals.');
        }
    }
}
