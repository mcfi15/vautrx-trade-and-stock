<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Deposit;
use Illuminate\Http\Request;
use App\Models\Cryptocurrency;
use App\Mail\DepositStatusMail;
use App\Mail\DepositRejectedMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
// use App\Services\NotificationService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class DepositController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:admin']);
    }

    /**
     * Display deposits list
     */
    public function index(Request $request)
    {
        $query = Deposit::with(['user', 'cryptocurrency'])
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
                })->orWhere('transaction_hash', 'LIKE', "%{$search}%");
            });
        }

        $deposits = $query->paginate(20);
        $cryptocurrencies = Cryptocurrency::active()->get();
        
        return view('admin.deposits.index', compact('deposits', 'cryptocurrencies'));
    }

    /**
     * Show create manual deposit form
     */
    public function create()
    {
        $users = User::orderBy('email')->get();
        $cryptocurrencies = Cryptocurrency::active()->get();
        
        return view('admin.deposits.create', compact('users', 'cryptocurrencies'));
    }

    /**
     * Store manual deposit
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'cryptocurrency_id' => 'required|exists:cryptocurrencies,id',
            'transaction_hash' => 'required|string|unique:deposits,transaction_hash',
            'amount' => 'required|numeric|min:0.00000001',
            'fee' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,confirmed,completed,failed',
            'confirmations' => 'nullable|integer|min:0',
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
            
            // Get or create user wallet
            $wallet = $user->getOrCreateWallet($request->cryptocurrency_id);
            
            // Create deposit record
            $deposit = Deposit::create([
                'user_id' => $user->id,
                'cryptocurrency_id' => $cryptocurrency->id,
                'transaction_hash' => $request->transaction_hash,
                'amount' => $request->amount,
                'fee' => $request->fee ?? 0,
                'status' => $request->status,
                'confirmations' => $request->confirmations ?? ($request->status === 'confirmed' ? 3 : 0),
                'required_confirmations' => 3,
            ]);

            // Create transaction record
            $transaction = \App\Models\Transaction::create([
                'user_id' => $user->id,
                'cryptocurrency_id' => $cryptocurrency->id,
                'type' => 'deposit',
                'amount' => $request->amount,
                'fee' => $request->fee ?? 0,
                'balance_before' => $wallet->balance,
                'balance_after' => $request->status === 'completed' 
                    ? bcadd($wallet->balance, $request->amount, 18) 
                    : $wallet->balance,
                'transaction_hash' => $request->transaction_hash,
                'status' => $request->status,
                'description' => "Manual deposit by admin - {$request->notes}",
            ]);

            // Update deposit with transaction ID
            $deposit->transaction_id = $transaction->id;
            $deposit->save();

            // Update wallet balance if deposit is confirmed/completed
            if (in_array($request->status, ['confirmed', 'completed'])) {
                $wallet->balance = bcadd($wallet->balance, $request->amount, 18);
                $wallet->save();
            }

            DB::commit();

            // Send email notification for manual deposit creation
            // if ($request->status !== 'pending') {
            //     $this->notificationService->sendDepositNotification(
            //         $deposit->load('user', 'cryptocurrency'), 
            //         'created'
            //     );
            // }

            // Log admin action
            Log::channel('admin')->info('Manual deposit created', [
                'admin_id' => Auth::guard('admin')->id(),
                'admin_email' => Auth::guard('admin')->user()->email,
                'user_id' => $user->id,
                'user_email' => $user->email,
                'cryptocurrency' => $cryptocurrency->symbol,
                'amount' => $request->amount,
                'transaction_hash' => $request->transaction_hash,
                'status' => $request->status,
            ]);

            return redirect()->route('admin.deposits.show', $deposit)
                ->with('success', 'Manual deposit created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating manual deposit', [
                'admin_id' => Auth::guard('admin')->id(),
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'Error creating deposit: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show deposit details
     */
    public function show(Deposit $deposit)
    {
        $deposit->load(['user', 'cryptocurrency']);
        
        return view('admin.deposits.show', compact('deposit'));
    }

    /**
     * Edit deposit
     */
    public function edit(Deposit $deposit)
    {
        $deposit->load(['user', 'cryptocurrency']);
        $cryptocurrencies = Cryptocurrency::active()->get();
        
        return view('admin.deposits.edit', compact('deposit', 'cryptocurrencies'));
    }

    /**
     * Update deposit
     */
    public function update(Request $request, Deposit $deposit)
    {
        $validator = Validator::make($request->all(), [
            'cryptocurrency_id' => 'required|exists:cryptocurrencies,id',
            'transaction_hash' => 'required|string|unique:deposits,transaction_hash,' . $deposit->id,
            'amount' => 'required|numeric|min:0.00000001',
            'fee' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,confirmed,completed,failed',
            'confirmations' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $oldStatus = $deposit->status;
            $oldAmount = $deposit->amount;
            
            $deposit->update([
                'cryptocurrency_id' => $request->cryptocurrency_id,
                'transaction_hash' => $request->transaction_hash,
                'amount' => $request->amount,
                'fee' => $request->fee ?? 0,
                'status' => $request->status,
                'confirmations' => $request->confirmations ?? 0,
            ]);

            // Update transaction record
            if ($deposit->transaction) {
                $deposit->transaction->update([
                    'amount' => $request->amount,
                    'fee' => $request->fee ?? 0,
                    'status' => $request->status,
                    'description' => "Updated manual deposit - {$request->notes}",
                ]);
            }

            // Handle wallet balance changes
            $wallet = $deposit->user->getOrCreateWallet($deposit->cryptocurrency_id);
            
            if ($oldStatus !== $request->status || $oldAmount !== $request->amount) {
                // Revert old balance impact
                if (in_array($oldStatus, ['confirmed', 'completed'])) {
                    $wallet->balance = bcsub($wallet->balance, $oldAmount, 18);
                }
                
                // Apply new balance impact
                if (in_array($request->status, ['confirmed', 'completed'])) {
                    $wallet->balance = bcadd($wallet->balance, $request->amount, 18);
                }
                
                $wallet->save();
            }

            DB::commit();

            // Send email notification if status changed
            if ($oldStatus !== $request->status) {
                $action = $request->status === 'confirmed' ? 'confirmed' : 
                         ($request->status === 'completed' ? 'completed' : 
                         ($request->status === 'failed' ? 'failed' : 'updated'));
                
                // $this->notificationService->sendDepositNotification(
                //     $deposit->load('user', 'cryptocurrency'), 
                //     $action
                // );
            }

            // Log admin action
            Log::channel('admin')->info('Deposit updated', [
                'admin_id' => Auth::guard('admin')->id(),
                'admin_email' => Auth::guard('admin')->user()->email,
                'deposit_id' => $deposit->id,
                'user_id' => $deposit->user_id,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'old_amount' => $oldAmount,
                'new_amount' => $request->amount,
                'transaction_hash' => $request->transaction_hash,
            ]);

            return redirect()->route('admin.deposits.show', $deposit)
                ->with('success', 'Deposit updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating deposit', [
                'admin_id' => Auth::guard('admin')->id(),
                'deposit_id' => $deposit->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Error updating deposit: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete deposit
     */
    public function destroy(Deposit $deposit)
    {
        try {
            DB::beginTransaction();

            $user = $deposit->user;
            $cryptocurrency = $deposit->cryptocurrency;
            
            // Revert wallet balance if deposit was confirmed/completed
            if (in_array($deposit->status, ['confirmed', 'completed'])) {
                $wallet = $user->getOrCreateWallet($cryptocurrency->id);
                $wallet->balance = bcsub($wallet->balance, $deposit->amount, 18);
                $wallet->save();
            }

            // Delete transaction record
            if ($deposit->transaction) {
                $deposit->transaction->delete();
            }

            $deposit->delete();

            DB::commit();

            // Log admin action
            Log::channel('admin')->info('Deposit deleted', [
                'admin_id' => Auth::guard('admin')->id(),
                'admin_email' => Auth::guard('admin')->user()->email,
                'deleted_deposit_id' => $deposit->id,
                'user_id' => $user->id,
                'user_email' => $user->email,
                'cryptocurrency' => $cryptocurrency->symbol,
                'amount' => $deposit->amount,
                'transaction_hash' => $deposit->transaction_hash,
                'status' => $deposit->status,
            ]);

            return redirect()->route('admin.deposits.index')
                ->with('success', 'Deposit deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting deposit', [
                'admin_id' => Auth::guard('admin')->id(),
                'deposit_id' => $deposit->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Error deleting deposit: ' . $e->getMessage());
        }
    }

    /**
     * Quick confirm deposit
     */
    public function confirm(Deposit $deposit)
    {
        try {
            DB::beginTransaction();

            if ($deposit->status === 'pending') {
                $deposit->status = 'confirmed';
                $deposit->confirmations = 3;
                $deposit->save();

                // Update wallet balance
                $wallet = $deposit->user->getOrCreateWallet($deposit->cryptocurrency_id);
                $wallet->balance = bcadd($wallet->balance, $deposit->amount, 18);
                $wallet->save();

                // Update transaction record
                if ($deposit->transaction) {
                    $deposit->transaction->update([
                        'status' => 'confirmed',
                        'balance_after' => $wallet->balance,
                    ]);
                }

                DB::commit();

                // Log admin action
                Log::channel('admin')->info('Deposit confirmed', [
                    'admin_id' => Auth::guard('admin')->id(),
                    'admin_email' => Auth::guard('admin')->user()->email,
                    'deposit_id' => $deposit->id,
                    'user_id' => $deposit->user_id,
                    'user_email' => $deposit->user->email,
                    'cryptocurrency' => $deposit->cryptocurrency->symbol,
                    'amount' => $deposit->amount,
                    'transaction_hash' => $deposit->transaction_hash,
                ]);

                return redirect()->back()->with('success', 'Deposit confirmed successfully');
            }

            return redirect()->back()->with('error', 'Deposit is not in pending status');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error confirming deposit', [
                'admin_id' => Auth::guard('admin')->id(),
                'deposit_id' => $deposit->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Error confirming deposit: ' . $e->getMessage());
        }
    }

    /**
     * Get user deposits for AJAX
     */
    public function getUserDeposits(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $deposits = $user->deposits()->with('cryptocurrency')->latest()->take(10)->get();

        return response()->json([
            'deposits' => $deposits,
            'success' => true,
        ]);
    }

    /**
     * View payment proof image
     */
    public function viewPaymentProof(Deposit $deposit)
    {
        if (!$deposit->hasPaymentProof()) {
            return response()->json(['error' => 'No payment proof uploaded'], 404);
        }

        // File is saved like: /public/uploads/deposits/filename.extension
        $filePath = public_path($deposit->payment_proof_path);

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'Payment proof file not found'], 404);
        }

        return response()->file($filePath);
    }

    /**
     * Approve deposit with admin notes
     */
    public function approve(Request $request, Deposit $deposit)
    {
        $validator = Validator::make($request->all(), [
            'admin_notes' => 'nullable|string|max:1000',
            'status' => 'required|in:confirmed,completed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $oldStatus = $deposit->status;
            
            $deposit->update([
                'status' => $request->status,
                'admin_notes' => $request->admin_notes,
                'reviewed_at' => now(),
                'reviewed_by_admin' => Auth::guard('admin')->id(),
            ]);

            if (in_array($oldStatus, ['pending']) && in_array($request->status, ['confirmed', 'completed'])) {
                $wallet = $deposit->user->getOrCreateWallet($deposit->cryptocurrency_id);
                $wallet->balance = bcadd($wallet->balance, $deposit->amount, 18);
                $wallet->save();

                if (!$deposit->transaction) {
                    $transaction = \App\Models\Transaction::create([
                        'user_id' => $deposit->user_id,
                        'cryptocurrency_id' => $deposit->cryptocurrency_id,
                        'type' => 'deposit',
                        'amount' => $deposit->amount,
                        'fee' => $deposit->fee ?? 0,
                        'balance_before' => bcsub($wallet->balance, $deposit->amount, 18),
                        'balance_after' => $wallet->balance,
                        'transaction_hash' => $deposit->transaction_hash,
                        'status' => $request->status,
                        'description' => "Deposit approved by admin - {$request->admin_notes}",
                    ]);

                    $deposit->transaction_id = $transaction->id;
                    $deposit->save();
                } else {
                    $deposit->transaction->update([
                        'status' => $request->status,
                        'balance_after' => $wallet->balance,
                    ]);
                }
            }

            DB::commit();

            // ✅ Send email to user
            try {
                Mail::to($deposit->user->email)->send(
                    new DepositStatusMail($deposit->load('user', 'cryptocurrency'), $request->status, $request->admin_notes)
                );
            } catch (\Exception $mailError) {
                Log::error('Deposit email sending failed', [
                    'deposit_id' => $deposit->id,
                    'user_id' => $deposit->user_id,
                    'error' => $mailError->getMessage(),
                ]);
            }

            Log::channel('admin')->info('Deposit approved', [
                'admin_id' => Auth::guard('admin')->id(),
                'deposit_id' => $deposit->id,
                'user_id' => $deposit->user_id,
                'status' => $request->status,
                'admin_notes' => $request->admin_notes,
            ]);

            return redirect()->route('admin.deposits.show', $deposit)
                ->with('success', 'Deposit approved & user notified');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error approving deposit', [
                'admin_id' => Auth::guard('admin')->id(),
                'deposit_id' => $deposit->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Error approving deposit: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Reject deposit with admin notes
     */
    public function reject(Request $request, Deposit $deposit)
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

            $deposit->update([
                'status' => 'failed',
                'admin_notes' => $request->admin_notes,
                'reviewed_at' => now(),
                'reviewed_by_admin' => Auth::guard('admin')->id(),
            ]);

            // Update transaction record
            if ($deposit->transaction) {
                $deposit->transaction->update([
                    'status' => 'failed',
                ]);
            }

            DB::commit();

            // ✅ NEW: Send email notification to user
            try {
                Mail::to($deposit->user->email)->send(
                    new DepositRejectedMail($deposit, $request->admin_notes)
                );
            } catch (\Exception $mailError) {
                Log::error('Deposit rejection email failed', [
                    'deposit_id' => $deposit->id,
                    'error' => $mailError->getMessage()
                ]);
            }

            // Existing internal app notification
            // $this->notificationService->sendDepositNotification(
            //     $deposit->load('user', 'cryptocurrency'), 
            //     'rejected', 
            //     $request->admin_notes
            // );

            // Log admin action
            Log::channel('admin')->info('Deposit rejected', [
                'admin_id' => Auth::guard('admin')->id(),
                'admin_email' => Auth::guard('admin')->user()->email,
                'deposit_id' => $deposit->id,
                'user_id' => $deposit->user_id,
                'user_email' => $deposit->user->email,
                'cryptocurrency' => $deposit->cryptocurrency->symbol,
                'amount' => $deposit->amount,
                'admin_notes' => $request->admin_notes,
            ]);

            return redirect()->route('admin.deposits.show', $deposit)
                ->with('success', 'Deposit rejected successfully and user notified');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error rejecting deposit', [
                'admin_id' => Auth::guard('admin')->id(),
                'deposit_id' => $deposit->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Error rejecting deposit: ' . $e->getMessage())
                ->withInput();
        }
    }


    /**
     * Download payment proof
     */
    public function downloadPaymentProof(Deposit $deposit)
    {
        if (!$deposit->hasPaymentProof()) {
            return redirect()->back()->with('error', 'No payment proof available');
        }

        $filePath = storage_path('app/public/' . $deposit->payment_proof_path);
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Payment proof file not found');
        }

        return response()->download($filePath, $deposit->payment_proof_filename ?? 'payment-proof');
    }

    /**
     * Delete payment proof
     */
    public function deletePaymentProof(Deposit $deposit)
    {
        try {
            $deposit->deletePaymentProof();

            // Log admin action
            Log::channel('admin')->info('Payment proof deleted', [
                'admin_id' => Auth::guard('admin')->id(),
                'admin_email' => Auth::guard('admin')->user()->email,
                'deposit_id' => $deposit->id,
                'user_id' => $deposit->user_id,
            ]);

            return redirect()->back()->with('success', 'Payment proof deleted successfully');

        } catch (\Exception $e) {
            Log::error('Error deleting payment proof', [
                'admin_id' => Auth::guard('admin')->id(),
                'deposit_id' => $deposit->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Error deleting payment proof: ' . $e->getMessage());
        }
    }
}