<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use App\Models\Cryptocurrency;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Mail\WithdrawalApprovedMail;
use App\Mail\WithdrawalRejectedMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\WithdrawalCompletedMail;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class WithdrawalController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->middleware(['auth:admin']);
        $this->notificationService = $notificationService;
    }

    /**
     * Display withdrawals list
     */
    public function index(Request $request)
    {
        $query = Withdrawal::with(['user', 'cryptocurrency'])
            ->latest();

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('cryptocurrency_id')) {
            $query->where('cryptocurrency_id', $request->cryptocurrency_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('email', 'LIKE', "%{$search}%")
                              ->orWhere('name', 'LIKE', "%{$search}%");
                })->orWhere('withdrawal_address', 'LIKE', "%{$search}%")
                  ->orWhere('tx_hash', 'LIKE', "%{$search}%");
            });
        }

        $withdrawals = $query->paginate(20);
        $cryptocurrencies = Cryptocurrency::active()->get();
        
        return view('admin.withdrawals.index', compact('withdrawals', 'cryptocurrencies'));
    }

    /**
     * Show create manual withdrawal form
     */
    public function create()
    {
        $users = User::orderBy('email')->get();
        $cryptocurrencies = Cryptocurrency::active()->get();
        
        return view('admin.withdrawals.create', compact('users', 'cryptocurrencies'));
    }

    /**
     * Store manual withdrawal
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'cryptocurrency_id' => 'required|exists:cryptocurrencies,id',
            'withdrawal_address' => 'required|string',
            'amount' => 'required|numeric|min:0.00000001',
            'fee' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,processing,completed,failed,cancelled',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $user = User::findOrFail($request->user_id);
            $cryptocurrency = Cryptocurrency::findOrFail($request->cryptocurrency_id);
            
            // Get user wallet
            $wallet = $user->getOrCreateWallet($request->cryptocurrency_id);
            
            // Check if user has sufficient balance
            $totalAmount = $request->amount + ($request->fee ?? 0);
            if (bccomp($wallet->balance, $totalAmount, 18) < 0) {
                throw new \Exception('Insufficient balance for withdrawal');
            }
            
            // Create withdrawal record
            $withdrawal = Withdrawal::create([
                'user_id' => $user->id,
                'cryptocurrency_id' => $cryptocurrency->id,
                'withdrawal_address' => $request->withdrawal_address,
                'amount' => $request->amount,
                'fee' => $request->fee ?? 0,
                'status' => $request->status,
                'admin_notes' => $request->notes,
                'processed_by' => Auth::guard('admin')->id(),
            ]);

            // Create transaction record
            $transaction = \App\Models\Transaction::create([
                'user_id' => $user->id,
                'cryptocurrency_id' => $cryptocurrency->id,
                'type' => 'withdrawal',
                'amount' => -$request->amount, // Negative for withdrawal
                'fee' => $request->fee ?? 0,
                'balance_before' => $wallet->balance,
                'balance_after' => $request->status === 'completed' 
                    ? bcsub($wallet->balance, $totalAmount, 18) 
                    : $wallet->balance,
                'tx_hash' => $request->tx_hash ?? null,
                'status' => $request->status,
                'description' => "Manual withdrawal by admin - {$request->notes}",
            ]);

            // Update withdrawal with transaction ID
            $withdrawal->transaction_id = $transaction->id;
            $withdrawal->save();

            // Update wallet balance if withdrawal is completed
            if (in_array($request->status, ['completed', 'processing'])) {
                $wallet->balance = bcsub($wallet->balance, $totalAmount, 18);
                $wallet->save();
            }

            DB::commit();

            // Send email notification for manual withdrawal creation
            if ($request->status !== 'pending') {
                $this->notificationService->sendWithdrawalNotification(
                    $withdrawal->load('user', 'cryptocurrency'), 
                    'created'
                );
            }

            // Log admin action
            Log::channel('admin')->info('Manual withdrawal created', [
                'admin_id' => Auth::guard('admin')->id(),
                'admin_email' => Auth::guard('admin')->user()->email,
                'user_id' => $user->id,
                'user_email' => $user->email,
                'cryptocurrency' => $cryptocurrency->symbol,
                'amount' => $request->amount,
                'fee' => $request->fee ?? 0,
                'withdrawal_address' => $request->withdrawal_address,
                'status' => $request->status,
            ]);

            return redirect()->route('admin.withdrawals.show', $withdrawal)
                ->with('success', 'Manual withdrawal created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating manual withdrawal', [
                'admin_id' => Auth::guard('admin')->id(),
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'Error creating withdrawal: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show withdrawal details
     */
    public function show(Withdrawal $withdrawal)
    {
        $withdrawal->load(['user', 'cryptocurrency', 'transaction']);
        
        return view('admin.withdrawals.show', compact('withdrawal'));
    }

    /**
     * Edit withdrawal
     */
    public function edit(Withdrawal $withdrawal)
    {
        $withdrawal->load(['user', 'cryptocurrency']);
        $cryptocurrencies = Cryptocurrency::active()->get();
        
        return view('admin.withdrawals.edit', compact('withdrawal', 'cryptocurrencies'));
    }

    /**
     * Update withdrawal
     */
    public function update(Request $request, Withdrawal $withdrawal)
    {
        $validator = Validator::make($request->all(), [
            'cryptocurrency_id' => 'required|exists:cryptocurrencies,id',
            'withdrawal_address' => 'required|string',
            'amount' => 'required|numeric|min:0.00000001',
            'fee' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,processing,completed,failed,cancelled',
            'tx_hash' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $oldStatus = $withdrawal->status;
            $oldAmount = $withdrawal->amount;
            
            $withdrawal->update([
                'cryptocurrency_id' => $request->cryptocurrency_id,
                'withdrawal_address' => $request->withdrawal_address,
                'amount' => $request->amount,
                'fee' => $request->fee ?? 0,
                'status' => $request->status,
                'tx_hash' => $request->tx_hash,
                'admin_notes' => $request->notes,
            ]);

            // Update transaction record
            if ($withdrawal->transaction) {
                $withdrawal->transaction->update([
                    'amount' => -$request->amount, // Negative for withdrawal
                    'fee' => $request->fee ?? 0,
                    'status' => $request->status,
                    'tx_hash' => $request->tx_hash,
                    'description' => "Updated manual withdrawal - {$request->notes}",
                ]);
            }

            // Handle wallet balance changes
            $wallet = $withdrawal->user->getOrCreateWallet($withdrawal->cryptocurrency_id);
            $totalOldAmount = $oldAmount + ($withdrawal->fee ?? 0);
            $totalNewAmount = $request->amount + ($request->fee ?? 0);
            
            if ($oldStatus !== $request->status || $oldAmount !== $request->amount) {
                // Revert old balance impact
                if (in_array($oldStatus, ['completed', 'processing'])) {
                    $wallet->balance = bcadd($wallet->balance, $totalOldAmount, 18);
                }
                
                // Apply new balance impact
                if (in_array($request->status, ['completed', 'processing'])) {
                    $wallet->balance = bcsub($wallet->balance, $totalNewAmount, 18);
                }
                
                $wallet->save();
            }

            DB::commit();

            // Send email notification if status changed
            if ($oldStatus !== $request->status) {
                $action = $request->status === 'processing' ? 'approved' : 
                         ($request->status === 'completed' ? 'completed' : 
                         ($request->status === 'cancelled' ? 'cancelled' : 'updated'));
                
                $this->notificationService->sendWithdrawalNotification(
                    $withdrawal->load('user', 'cryptocurrency'), 
                    $action
                );
            }

            // Log admin action
            Log::channel('admin')->info('Withdrawal updated', [
                'admin_id' => Auth::guard('admin')->id(),
                'admin_email' => Auth::guard('admin')->user()->email,
                'withdrawal_id' => $withdrawal->id,
                'user_id' => $withdrawal->user_id,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'old_amount' => $oldAmount,
                'new_amount' => $request->amount,
                'withdrawal_address' => $request->withdrawal_address,
            ]);

            return redirect()->route('admin.withdrawals.show', $withdrawal)
                ->with('success', 'Withdrawal updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating withdrawal', [
                'admin_id' => Auth::guard('admin')->id(),
                'withdrawal_id' => $withdrawal->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Error updating withdrawal: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete withdrawal
     */
    public function destroy(Withdrawal $withdrawal)
    {
        try {
            DB::beginTransaction();

            $user = $withdrawal->user;
            $cryptocurrency = $withdrawal->cryptocurrency;
            $totalAmount = $withdrawal->amount + ($withdrawal->fee ?? 0);
            
            // Restore wallet balance if withdrawal was processed
            if (in_array($withdrawal->status, ['completed', 'processing'])) {
                $wallet = $user->getOrCreateWallet($cryptocurrency->id);
                $wallet->balance = bcadd($wallet->balance, $totalAmount, 18);
                $wallet->save();
            }

            // Delete transaction record
            if ($withdrawal->transaction) {
                $withdrawal->transaction->delete();
            }

            $withdrawal->delete();

            DB::commit();

            // Log admin action
            Log::channel('admin')->info('Withdrawal deleted', [
                'admin_id' => Auth::guard('admin')->id(),
                'admin_email' => Auth::guard('admin')->user()->email,
                'deleted_withdrawal_id' => $withdrawal->id,
                'user_id' => $user->id,
                'user_email' => $user->email,
                'cryptocurrency' => $cryptocurrency->symbol,
                'amount' => $withdrawal->amount,
                'withdrawal_address' => $withdrawal->withdrawal_address,
                'status' => $withdrawal->status,
            ]);

            return redirect()->route('admin.withdrawals.index')
                ->with('success', 'Withdrawal deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting withdrawal', [
                'admin_id' => Auth::guard('admin')->id(),
                'withdrawal_id' => $withdrawal->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Error deleting withdrawal: ' . $e->getMessage());
        }
    }

    /**
     * Approve withdrawal
     */
    public function approve(Request $request, Withdrawal $withdrawal)
    {
        $validator = Validator::make($request->all(), [
            'admin_notes' => 'nullable|string|max:1000',
            'tx_hash' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $oldStatus = $withdrawal->status;

            $withdrawal->update([
                'status' => 'processing',
                'tx_hash' => $request->tx_hash,
                'admin_notes' => $request->admin_notes,
                'processed_by' => Auth::guard('admin')->id(),
                'processed_at' => now(),
            ]);

            // Deduct balance only if status was pending
            if ($oldStatus === 'pending') {
                $wallet = $withdrawal->user->getOrCreateWallet($withdrawal->cryptocurrency_id);
                $totalAmount = $withdrawal->amount + ($withdrawal->fee ?? 0);

                $wallet->balance = bcsub($wallet->balance, $totalAmount, 18);
                $wallet->save();

                // Update related transaction
                if ($withdrawal->transaction) {
                    $withdrawal->transaction->update([
                        'status' => 'processing',
                        'balance_after' => $wallet->balance,
                    ]);
                }
            }

            DB::commit();

            // ✅ Send email directly
            Mail::to($withdrawal->user->email)->send(
                new WithdrawalApprovedMail(
                    $withdrawal->load('user', 'cryptocurrency'),
                    'approved',
                    $request->admin_notes
                )
            );

            // Log admin action
            Log::channel('admin')->info('Withdrawal approved', [
                'admin_id' => Auth::guard('admin')->id(),
                'admin_email' => Auth::guard('admin')->user()->email,
                'withdrawal_id' => $withdrawal->id,
                'user_id' => $withdrawal->user_id,
                'user_email' => $withdrawal->user->email,
                'cryptocurrency' => $withdrawal->cryptocurrency->symbol,
                'amount' => $withdrawal->amount,
                'old_status' => $oldStatus,
                'tx_hash' => $request->tx_hash,
            ]);

            return redirect()->route('admin.withdrawals.show', $withdrawal)
                ->with('success', 'Withdrawal approved and email sent successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error approving withdrawal', [
                'admin_id' => Auth::guard('admin')->id(),
                'withdrawal_id' => $withdrawal->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Error approving withdrawal: ' . $e->getMessage());
        }
    }

    /**
     * Complete withdrawal
     */
    public function complete(Request $request, Withdrawal $withdrawal)
    {
        $validator = Validator::make($request->all(), [
            'tx_hash' => 'required|string',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $withdrawal->update([
                'status' => 'completed',
                'tx_hash' => $request->tx_hash,
                'admin_notes' => $request->admin_notes,
                'processed_by' => Auth::guard('admin')->id(),
                'processed_at' => now(),
            ]);

            if ($withdrawal->transaction) {
                $withdrawal->transaction->update([
                    'status' => 'completed',
                    'tx_hash' => $request->tx_hash,
                ]);
            }

            DB::commit();

            // ✅ Send email notification only
            Mail::to($withdrawal->user->email)
                ->send(new WithdrawalCompletedMail($withdrawal->load('user', 'cryptocurrency'), $request->admin_notes));

            // Log admin action
            Log::channel('admin')->info('Withdrawal completed', [
                'admin_id' => Auth::guard('admin')->id(),
                'admin_email' => Auth::guard('admin')->user()->email,
                'withdrawal_id' => $withdrawal->id,
                'user_id' => $withdrawal->user_id,
                'user_email' => $withdrawal->user->email,
                'cryptocurrency' => $withdrawal->cryptocurrency->symbol,
                'amount' => $withdrawal->amount,
                'tx_hash' => $request->tx_hash,
            ]);

            return redirect()->route('admin.withdrawals.show', $withdrawal)
                ->with('success', 'Withdrawal completed & email sent');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error completing withdrawal', [
                'admin_id' => Auth::guard('admin')->id(),
                'withdrawal_id' => $withdrawal->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Error completing withdrawal: ' . $e->getMessage());
        }
    }

    /**
     * Reject withdrawal
     */
    public function reject(Request $request, Withdrawal $withdrawal)
    {
        $validator = Validator::make($request->all(), [
            'admin_notes' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $oldStatus = $withdrawal->status;

            $withdrawal->update([
                'status' => 'cancelled',
                'admin_notes' => $request->admin_notes,
                'processed_by' => Auth::guard('admin')->id(),
                'processed_at' => now(),
            ]);

            if (in_array($oldStatus, ['processing', 'completed'])) {
                $wallet = $withdrawal->user->getOrCreateWallet($withdrawal->cryptocurrency_id);
                $totalAmount = $withdrawal->amount + ($withdrawal->fee ?? 0);
                $wallet->balance = bcadd($wallet->balance, $totalAmount, 18);
                $wallet->save();

                if ($withdrawal->transaction) {
                    $withdrawal->transaction->update([
                        'status' => 'cancelled',
                        'balance_after' => $wallet->balance,
                    ]);
                }
            }

            DB::commit();

            // ✅ Send email only
            Mail::to($withdrawal->user->email)
                ->send(new WithdrawalRejectedMail($withdrawal->load('user', 'cryptocurrency'), $request->admin_notes));

            Log::channel('admin')->info('Withdrawal rejected', [
                'admin_id' => Auth::guard('admin')->id(),
                'admin_email' => Auth::guard('admin')->user()->email,
                'withdrawal_id' => $withdrawal->id,
                'user_id' => $withdrawal->user_id,
                'user_email' => $withdrawal->user->email,
                'cryptocurrency' => $withdrawal->cryptocurrency->symbol,
                'amount' => $withdrawal->amount,
                'old_status' => $oldStatus,
                'admin_notes' => $request->admin_notes,
            ]);

            return redirect()->route('admin.withdrawals.show', $withdrawal)
                ->with('success', 'Withdrawal rejected & email sent');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error rejecting withdrawal', [
                'admin_id' => Auth::guard('admin')->id(),
                'withdrawal_id' => $withdrawal->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Error rejecting withdrawal: ' . $e->getMessage());
        }
    }

    /**
     * Get user withdrawals for AJAX
     */
    public function getUserWithdrawals(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $withdrawals = $user->withdrawals()->with('cryptocurrency')->latest()->take(10)->get();

        return response()->json([
            'withdrawals' => $withdrawals,
            'success' => true,
        ]);
    }


    
}