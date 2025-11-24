<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\Cryptocurrency;
use App\Mail\WithdrawalOtpMail;
use App\Models\WithdrawalAddress;
use App\Mail\DepositSubmittedMail;
use Illuminate\Support\Facades\DB;
use App\Services\BlockchainService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\WithdrawalSubmittedMail;

class WalletController extends Controller
{
    private $blockchainService;

    public function __construct(BlockchainService $blockchainService)
    {
        $this->middleware('auth');
        $this->blockchainService = $blockchainService;
    }

    public function index()
    {
        $user = Auth::user();
        
        // Ensure user has wallets for all active cryptocurrencies
        if ($user->wallets()->count() === 0) {
            $user->createDefaultWallets();
        }
        
        // Get wallets with cryptocurrency data and ensure they exist
        $wallets = $user->wallets()->with('cryptocurrency')->get();
        
        // Ensure all active cryptocurrencies have wallets
        $cryptocurrencies = Cryptocurrency::active()->get();
        foreach ($cryptocurrencies as $crypto) {
            if (!$user->getWallet($crypto->id)) {
                $user->wallets()->create([
                    'cryptocurrency_id' => $crypto->id,
                    'balance' => 0,
                    'locked_balance' => 0,
                ]);
            }
        }
        
        // Refresh wallets after ensuring they all exist
        $wallets = $user->wallets()->with('cryptocurrency')->get();
        
        // Calculate total portfolio value in USDT
        $totalValue = $user->getTotalPortfolioValueUsdt();
        
        // Get recent transactions
        $recentTransactions = $user->transactions()
            ->with('cryptocurrency')
            ->latest()
            ->take(5)
            ->get();
        
        return view('user.wallet.index', compact('wallets', 'cryptocurrencies', 'totalValue', 'recentTransactions'));
    }

    public function showDeposit($cryptoId = null)
    {
        $user = Auth::user();
        
        $cryptocurrencies = Cryptocurrency::active()->get();

            // If no cryptocurrency ID provided, show selection page
        if (!$cryptoId) {
            return view('user.wallet.deposit-select', compact('cryptocurrencies'));
        }

        $crypto = Cryptocurrency::findOrFail($cryptoId);

        $paymentMethods = PaymentMethod::where('cryptocurrency_id', $cryptoId)->get();

        $deposits = Deposit::where('user_id', $user->id)
            ->where('cryptocurrency_id', $cryptoId)
            ->latest()
            ->get();

        return view('user.wallet.deposit', [
            'cryptocurrency' => $crypto,
            'paymentMethods' => $paymentMethods,
            'deposits' => $deposits
        ]);
    }

    public function submitDeposit(Request $request, $cryptoId)
{
    $user = Auth::user();

    $request->validate([
        'amount' => 'required|numeric|min:0.000001',
        'method_id' => 'required|exists:payment_methods,id',
        'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
    ]);

    $proofPath = null;

    if ($request->hasFile('payment_proof')) {
        $file = $request->file('payment_proof');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/deposits'), $filename);
        $proofPath = "uploads/deposits/$filename";
    }

    try {
        // Create deposit record
        $deposit = Deposit::create([
            'user_id' => $user->id,
            'cryptocurrency_id' => $cryptoId,
            'payment_method_id' => $request->method_id,
            'amount' => $request->amount,
            'status' => 'pending',
            'payment_proof_path' => $proofPath
        ]);

        // ✅ Send email notification to user
        try {
            Mail::to($user->email)->send(new DepositSubmittedMail($deposit));
        } catch (\Exception $mailError) {
            \Log::error('Deposit submission email failed', [
                'deposit_id' => $deposit->id,
                'user_id' => $user->id,
                'error' => $mailError->getMessage(),
            ]);
        }

        Log::info('Deposit submitted', [
            'deposit_id' => $deposit->id,
            'user_id' => $user->id,
        ]);

        return back()->with('success', 'Deposit submitted and awaiting approval');

    } catch (\Exception $e) {
        Log::error('Deposit submission failed', [
            'user_id' => $user->id,
            'crypto_id' => $cryptoId,
            'error' => $e->getMessage(),
        ]);

        return back()->with('error', 'Something went wrong. Please try again.')->withInput();
    }
}


    // public function showDeposit($cryptoId = null)
    // {
    //     $user = Auth::user();
    //     $cryptocurrencies = Cryptocurrency::active()->get();
        
    //     // If no cryptocurrency ID provided, show selection page
    //     if (!$cryptoId) {
    //         return view('user.wallet.deposit-select', compact('cryptocurrencies'));
    //     }
        
    //     // If cryptocurrency ID provided, show specific deposit page
    //     $cryptocurrency = Cryptocurrency::findOrFail($cryptoId);
    //     $wallet = $user->getOrCreateWallet($cryptoId);
        
    //     // Generate deposit address if not exists
    //     if (!$wallet->address) {
    //         try {
    //             $addressData = $this->blockchainService->generateDepositAddress($cryptocurrency->symbol);
    //             if ($addressData && isset($addressData['address'])) {
    //                 $wallet->address = $addressData['address'];
    //                 $wallet->save();
    //             }
    //         } catch (\Exception $e) {
    //             // Log error but continue - wallet will work without address
    //             \Log::error('Failed to generate deposit address', [
    //                 'user_id' => $user->id,
    //                 'crypto_id' => $cryptoId,
    //                 'error' => $e->getMessage()
    //             ]);
    //         }
    //     }
        
    //     // Get deposit history
    //     $deposits = $user->deposits()
    //         ->where('cryptocurrency_id', $cryptoId)
    //         ->latest()
    //         ->take(10)
    //         ->get();
        
    //     return view('user.wallet.deposit', compact('cryptocurrency', 'wallet', 'deposits', 'cryptocurrencies'));
    // }

    // public function submitDeposit(Request $request, $cryptoId)
    // {
    //     $user = Auth::user();
    //     $cryptocurrency = Cryptocurrency::findOrFail($cryptoId);
    //     $wallet = $user->getOrCreateWallet($cryptoId);

    //     // ✅ Validate input
    //     $request->validate([
    //         'amount' => ['required', 'numeric', 'min:0.0001'],
    //         'payment_proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
    //     ]);

    //     try {
    //         $proofPath = null;
    //         $proofFilename = null;

    //         // ✅ Handle file upload (your required format)
    //         if ($request->hasFile('payment_proof')) {
    //             $file = $request->file('payment_proof');
    //             $originalName = $file->getClientOriginalName(); // ✅ Store original filename
    //             $ext = $file->getClientOriginalExtension();
    //             $filename = time() . '.' . $ext;

    //             // ✅ Move file to public/uploads/deposits
    //             $file->move(public_path('uploads/deposits'), $filename);

    //             $proofPath = "uploads/deposits/$filename";
    //             $proofFilename = $originalName;
    //         }

    //         // ✅ Create pending deposit
    //         $deposit = Deposit::create([
    //             'user_id' => $user->id,
    //             'cryptocurrency_id' => $cryptocurrency->id,
    //             'amount' => $request->amount,
    //             'status' => 'pending',
    //             'payment_proof_path' => $proofPath,
    //             'payment_proof_filename' => $proofFilename,
    //         ]);

    //         // ✅ Send confirmation email to user
    //         try {
    //             Mail::to($user->email)->send(new \App\Mail\DepositSubmittedMail($deposit));
    //         } catch (\Exception $mailException) {
    //             \Log::error('Deposit email sending failed', [
    //                 'user_id' => $user->id,
    //                 'deposit_id' => $deposit->id,
    //                 'error' => $mailException->getMessage(),
    //             ]);
    //         }

    //         // ✅ Log success
    //         \Log::info('New deposit submitted', [
    //             'user_id' => $user->id,
    //             'crypto_id' => $cryptocurrency->id,
    //             'amount' => $request->amount,
    //             'deposit_id' => $deposit->id,
    //         ]);

    //         return redirect()
    //             ->route('wallet.deposit', $cryptoId)
    //             ->with('success', 'Deposit submitted successfully and awaiting approval.');

    //     } catch (\Exception $e) {
    //         \Log::error('Deposit submission failed', [
    //             'user_id' => $user->id,
    //             'crypto_id' => $cryptoId,
    //             'error' => $e->getMessage(),
    //         ]);

    //         return back()
    //             ->with('error', 'Something went wrong. Please try again.')
    //             ->withInput();
    //     }
    // }





    // public function showWithdraw($cryptoId = null)
    // { 
        
    //     $user = Auth::user();
    //      // KYC REQUIRED BEFORE WITHDRAWAL
    //     if ($user->kyc_status !== 'approved') {
    //         return redirect('kyc')
    //             ->with('error', 'You must complete KYC verification before withdrawing.');
    //     }

    //     $cryptocurrencies = Cryptocurrency::active()->get();
        
    //     // If no cryptocurrency ID provided, show selection page
    //     if (!$cryptoId) {
    //         return view('user.wallet.withdraw-select', compact('cryptocurrencies'));
    //     }
        
    //     // If cryptocurrency ID provided, show specific withdrawal page
    //     $cryptocurrency = Cryptocurrency::findOrFail($cryptoId);
    //     $wallet = $user->getOrCreateWallet($cryptoId);
        
    //     // Get withdrawal history
    //     $withdrawals = $user->withdrawals()
    //         ->where('cryptocurrency_id', $cryptoId)
    //         ->latest()
    //         ->take(10)
    //         ->get();
        
    //     return view('user.wallet.withdraw', compact('cryptocurrency', 'wallet', 'withdrawals', 'cryptocurrencies'));
    // }

    // public function processWithdrawal(Request $request)
    // {
    //     $request->validate([
    //         'cryptocurrency_id' => 'required|exists:cryptocurrencies,id',
    //         'address' => 'required|string',
    //         'amount' => 'required|numeric|min:0.00000001',
    //     ]);

    //     try {
    //         DB::beginTransaction();

    //         $user = Auth::user();
    //         $cryptocurrency = Cryptocurrency::findOrFail($request->cryptocurrency_id);
    //         $wallet = $user->getWallet($request->cryptocurrency_id);

    //         if (!$wallet || $wallet->availableBalance < $request->amount) {
    //             throw new \Exception('Insufficient balance');
    //         }

    //         // Validate blockchain wallet address
    //         if (!$this->blockchainService->isValidAddress($request->address)) {
    //             throw new \Exception('Invalid withdrawal address');
    //         }

    //         // Withdrawal fee (0.1% or minimum 0.001)
    //         $fee = max(0.001, bcmul($request->amount, '0.001', 18));
    //         $netAmount = bcsub($request->amount, $fee, 18);

    //         // Lock balance
    //         $wallet->lockBalance($request->amount);

    //         // Create withdrawal request
    //         $withdrawal = Withdrawal::create([
    //             'user_id' => $user->id,
    //             'cryptocurrency_id' => $request->cryptocurrency_id,
    //             'withdrawal_address' => $request->address,
    //             'amount' => $request->amount,
    //             'fee' => $fee,
    //             'net_amount' => $netAmount,
    //             'status' => 'pending',
    //         ]);

    //         // Create transaction record
    //         $transaction = Transaction::create([
    //             'user_id' => $user->id,
    //             'cryptocurrency_id' => $request->cryptocurrency_id,
    //             'type' => 'withdrawal',
    //             'amount' => $request->amount,
    //             'fee' => $fee,
    //             'balance_before' => $wallet->balance,
    //             'balance_after' => bcsub($wallet->balance, $request->amount, 18),
    //             'to_address' => $request->address,
    //             'status' => 'pending',
    //             'description' => "Withdrawal to {$request->address}",
    //         ]);

    //         $withdrawal->transaction_id = $transaction->id;
    //         $withdrawal->save();

    //         DB::commit();

    //         // ✅ Send withdrawal email notification
    //         try {
    //             Mail::to($user->email)->send(new WithdrawalSubmittedMail($withdrawal)
    //             );
    //         } catch (\Exception $mailError) {
    //             \Log::error('Withdrawal email sending failed', [
    //                 'withdrawal_id' => $withdrawal->id,
    //                 'user_id' => $user->id,
    //                 'error' => $mailError->getMessage(),
    //             ]);
    //         }

    //         return redirect()->back()->with('success', 'Withdrawal request submitted successfully');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->back()->with('error', $e->getMessage());
    //     }
    // }


//     public function showWithdrawForm()
// {
//     $user = Auth::user();

//     // Require KYC
//     if ($user->kyc_status !== 'approved') {
//         return redirect('kyc')->with('error', 'Please complete KYC before withdrawal.');
//     }

//     // Get all active cryptos
//     $cryptocurrencies = Cryptocurrency::active()->orderBy('symbol')->get();

//     // If no crypto exists (very rare)
//     if ($cryptocurrencies->isEmpty()) {
//         return back()->with('error', 'No cryptocurrency available.');
//     }

//     // Always load the FIRST crypto as default withdrawal option
//     $cryptocurrency = $cryptocurrencies->first();
//     $cryptoId = $cryptocurrency->id;

//     // Get or create user's wallet for that crypto
//     $wallet = $user->getOrCreateWallet($cryptoId);

//     // Saved withdrawal addresses
//     $addresses = WithdrawalAddress::where('user_id', $user->id)
//         ->where('cryptocurrency_id', $cryptoId)
//         ->orderByDesc('is_verified')
//         ->get();

//     // Last 10 withdrawals of that crypto
//     $withdrawals = $user->withdrawals()
//         ->where('cryptocurrency_id', $cryptoId)
//         ->latest()
//         ->take(10)
//         ->get();

//     return view('user.wallet.withdraw', compact(
//         'cryptocurrency',
//         'wallet',
//         'addresses',
//         'withdrawals',
//         'cryptocurrencies'
//     ));
// }



    public function showWithdrawForm(Request $request)
{
    $user = Auth::user();

         // KYC REQUIRED BEFORE WITHDRAWAL
        if ($user->kyc_status !== 'approved') {
            return redirect('kyc')
                ->with('error', 'You must complete KYC verification before withdrawing.');
        }
    
    // Get all cryptocurrencies for the dropdown
    $cryptocurrencies = Cryptocurrency::all();
    
    // Get selected coin from query parameter or default
    $selectedCoinId = $request->query('coin', $cryptocurrencies->first()->id ?? null);
    $selectedCrypto = Cryptocurrency::find($selectedCoinId);
    
    // Get user's wallet balance for selected coin
    $walletBalance = 0;
    if ($selectedCrypto) {
        $wallet = $user->getWallet($selectedCrypto->id);
        $walletBalance = $wallet ? ($wallet->balance - ($wallet->locked_balance ?? 0)) : 0;
    }
    
    // Get saved addresses for selected coin
    $addresses = WithdrawalAddress::where('user_id', $user->id)
        ->where('cryptocurrency_id', $selectedCoinId)
        ->get();
    
    // Get available networks for selected coin (you might need to adjust this based on your data structure)
    $networks = $selectedCrypto ? ['ERC20', 'BEP20', 'Mainnet'] : []; // Example networks
    
    return view('user.wallet.withdraw', compact(
        'cryptocurrencies',
        'selectedCrypto',
        'walletBalance',
        'addresses',
        'networks'
    ));
}


    // AJAX: send OTP
    public function sendOtp(Request $request)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);

        $otp = random_int(100000, 999999);
        $user->fund_password_otp = (string)$otp;
        $user->fund_password_otp_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        try {
            Mail::to($user->email)->send(new WithdrawalOtpMail($otp));
        } catch (\Exception $e) {
            \Log::error('OTP email send error: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to send OTP'], 500);
        }

        return response()->json(['success' => true, 'message' => 'OTP sent']);
    }

    // Add address (modal)
    public function addAddress(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'cryptocurrency_id' => 'required|exists:cryptocurrencies,id',
            'address' => 'required|string',
            'label' => 'nullable|string|max:255',
            'network' => 'nullable|string|max:100',
            'fund_password' => 'required|string'
        ]);

        // Check fund password
        if (!$user->fund_password || !Hash::check($request->fund_password, $user->fund_password)) {
            return response()->json(['success' => false, 'message' => 'Invalid fund password'], 422);
        }

        $created = WithdrawalAddress::create([
            'user_id' => $user->id,
            'cryptocurrency_id' => $request->cryptocurrency_id,
            'network' => $request->network,
            'address' => $request->address,
            'dest_tag' => $request->dest_tag ?? null,
            'label' => $request->label ?? null,
            'is_verified' => false,
        ]);

        return response()->json(['success' => true, 'data' => $created]);
    }

    // Process withdrawal
    public function processWithdrawal(Request $request)
    {
        $user = Auth::user();

        if (!$user->canWithdraw()) {
            return redirect()->back()->with('error', 'Withdrawals are currently ' . $user->withdrawal_permission_label . ' for your account.');
        }


        $request->validate([
            'cryptocurrency_id' => 'required|exists:cryptocurrencies,id',
            'address' => 'required|string',
            'amount' => 'required|numeric|min:0.00000001',
            'fund_password' => 'required|string',
            'otp' => 'required|string',
        ]);

        // Check fund password
        if (!$user->fund_password || !Hash::check($request->fund_password, $user->fund_password)) {
            return redirect()->back()->withErrors(['fund_password' => 'Invalid fund password']);
        }

        // OTP check
        if (!$user->fund_password_otp || $user->fund_password_otp !== $request->otp ||
            !$user->fund_password_otp_expires_at || $user->fund_password_otp_expires_at->isPast()) {
            return redirect()->back()->withErrors(['otp' => 'Invalid or expired OTP']);
        }

        DB::beginTransaction();
        try {
            $cryptocurrency = Cryptocurrency::findOrFail($request->cryptocurrency_id);
            $wallet = $user->getWallet($cryptocurrency->id);

            if (!$wallet || ($wallet->balance - ($wallet->locked_balance ?? 0)) < $request->amount) {
                throw new \Exception('Insufficient balance');
            }

            // simplistic fee: 0.1% or minimum 0.001 (adjust as needed)
            $fee = max(0.001, round($request->amount * 0.001, 8));
            $netAmount = round($request->amount - $fee, 8);

            // lock balance / subtract (depends on your Wallet model API)
            // Here we assume wallet->balance and wallet->locked_balance numeric attributes.
            $wallet->balance = bcsub($wallet->balance, $request->amount, 18);
            $wallet->locked_balance = bcadd($wallet->locked_balance ?? 0, $request->amount, 18);
            $wallet->save();

            $withdrawal = Withdrawal::create([
                'user_id' => $user->id,
                'cryptocurrency_id' => $cryptocurrency->id,
                'withdrawal_address' => $request->address,
                'amount' => $request->amount,
                'fee' => $fee,
                'net_amount' => $netAmount,
                'status' => 'pending',
            ]);

            // clear OTP
            $user->update(['fund_password_otp' => null, 'fund_password_otp_expires_at' => null]);

            DB::commit();

            // send email
            try {
                Mail::to($user->email)->send(new WithdrawalSubmittedMail($withdrawal));
            } catch (\Exception $e) {
                \Log::error('Withdrawal email sending failed: '.$e->getMessage());
            }

            return redirect()->back()->with('success','Withdrawal submitted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Withdrawal processing error: '.$e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function transactions()
    {
        $transactions = Auth::user()->transactions()
            ->with('cryptocurrency')
            ->latest()
            ->paginate(20);
        
        return view('user.wallet.transactions', compact('transactions'));
    }

    public function transactionDetail($id)
    {
        $transaction = Transaction::with('cryptocurrency')->findOrFail($id);

        return response()->json([
            'type' => ucfirst($transaction->type),
            'amount' => number_format($transaction->amount, 8),
            'fee' => number_format($transaction->fee ?? 0, 8),
            'status' => ucfirst($transaction->status),
            'crypto' => $transaction->cryptocurrency->symbol ?? 'N/A',
            'crypto_name' => $transaction->cryptocurrency->name ?? 'Unknown',
            'date' => $transaction->created_at->format('M j, Y g:i A'),
            'tx_hash' => $transaction->tx_hash ?? null,
        ]);
    }

}
