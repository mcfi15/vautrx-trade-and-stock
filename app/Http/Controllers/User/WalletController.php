<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cryptocurrency;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Transaction;
use App\Services\BlockchainService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        
        // If cryptocurrency ID provided, show specific deposit page
        $cryptocurrency = Cryptocurrency::findOrFail($cryptoId);
        $wallet = $user->getOrCreateWallet($cryptoId);
        
        // Generate deposit address if not exists
        if (!$wallet->address) {
            try {
                $addressData = $this->blockchainService->generateDepositAddress($cryptocurrency->symbol);
                if ($addressData && isset($addressData['address'])) {
                    $wallet->address = $addressData['address'];
                    $wallet->save();
                }
            } catch (\Exception $e) {
                // Log error but continue - wallet will work without address
                \Log::error('Failed to generate deposit address', [
                    'user_id' => $user->id,
                    'crypto_id' => $cryptoId,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        // Get deposit history
        $deposits = $user->deposits()
            ->where('cryptocurrency_id', $cryptoId)
            ->latest()
            ->take(10)
            ->get();
        
        return view('user.wallet.deposit', compact('cryptocurrency', 'wallet', 'deposits', 'cryptocurrencies'));
    }

    public function showWithdraw($cryptoId = null)
    {
        $user = Auth::user();
        $cryptocurrencies = Cryptocurrency::active()->get();
        
        // If no cryptocurrency ID provided, show selection page
        if (!$cryptoId) {
            return view('user.wallet.withdraw-select', compact('cryptocurrencies'));
        }
        
        // If cryptocurrency ID provided, show specific withdrawal page
        $cryptocurrency = Cryptocurrency::findOrFail($cryptoId);
        $wallet = $user->getOrCreateWallet($cryptoId);
        
        // Get withdrawal history
        $withdrawals = $user->withdrawals()
            ->where('cryptocurrency_id', $cryptoId)
            ->latest()
            ->take(10)
            ->get();
        
        return view('user.wallet.withdraw', compact('cryptocurrency', 'wallet', 'withdrawals', 'cryptocurrencies'));
    }

    public function processWithdrawal(Request $request)
    {
        $request->validate([
            'cryptocurrency_id' => 'required|exists:cryptocurrencies,id',
            'address' => 'required|string',
            'amount' => 'required|numeric|min:0.00000001',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $cryptocurrency = Cryptocurrency::findOrFail($request->cryptocurrency_id);
            $wallet = $user->getWallet($request->cryptocurrency_id);

            if (!$wallet || $wallet->availableBalance < $request->amount) {
                throw new \Exception('Insufficient balance');
            }

            // Validate address
            if (!$this->blockchainService->isValidAddress($request->address)) {
                throw new \Exception('Invalid withdrawal address');
            }

            // Calculate fee (example: 0.001 or 0.1%)
            $fee = max(0.001, bcmul($request->amount, '0.001', 18));
            $netAmount = bcsub($request->amount, $fee, 18);

            // Lock balance
            $wallet->lockBalance($request->amount);

            // Create withdrawal request
            $withdrawal = Withdrawal::create([
                'user_id' => $user->id,
                'cryptocurrency_id' => $request->cryptocurrency_id,
                'withdrawal_address' => $request->address,
                'amount' => $request->amount,
                'fee' => $fee,
                'net_amount' => $netAmount,
                'status' => 'pending',
            ]);

            // Create transaction record
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'cryptocurrency_id' => $request->cryptocurrency_id,
                'type' => 'withdrawal',
                'amount' => $request->amount,
                'fee' => $fee,
                'balance_before' => $wallet->balance,
                'balance_after' => bcsub($wallet->balance, $request->amount, 18),
                'to_address' => $request->address,
                'status' => 'pending',
                'description' => "Withdrawal to {$request->address}",
            ]);

            $withdrawal->transaction_id = $transaction->id;
            $withdrawal->save();

            DB::commit();

            return redirect()->back()->with('success', 'Withdrawal request submitted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
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
}
