<?php

namespace App\Http\Controllers\User;

use App\Models\Stock;
use App\Models\Wallet;
use App\Mail\StockSold;
use App\Models\Portfolio;
use App\Mail\StockPurchased;
use Illuminate\Http\Request;
use App\Models\Cryptocurrency;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Mail\AdminTradeNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class StockTradingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->trading_enabled) {
            return redirect()->route('user.dashboard')
                        ->with('error', 'Trading is not enabled for your account.');
        }

        $stocks = Stock::where('is_active', true)
                    ->orderBy('symbol')
                    ->get();
                    
        $user_portfolios = $user->portfolios()->with('stock')->get();

        // Selected stock
        $selectedStockId = $request->get('stock');
        $selectedStock = $selectedStockId ? Stock::find($selectedStockId) : null;

        // ✅ Get user's USDT balance
        $usdtWallet = $user->wallets()
            ->whereHas('cryptocurrency', function ($q) {
                $q->where('symbol', 'USDT');
            })
            ->first();
        $usdtBalance = $usdtWallet ? $usdtWallet->balance : 0;

        // ✅ Portfolio calculations
        $totalPortfolioValue = $user_portfolios->sum(function ($p) {
            return $p->stock->current_price * $p->quantity;
        });

        $totalInvestment = $user_portfolios->sum(function ($p) {
            return $p->average_price * $p->quantity;
        });

        $profitLoss = $totalPortfolioValue - $totalInvestment;

        return view('user.trading.index', compact(
            'stocks',
            'user_portfolios',
            'selectedStock',
            'usdtBalance',
            'totalPortfolioValue',
            'totalInvestment',
            'profitLoss'
        ));
    }


    public function buy(Request $request)
    {
        $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0.01',
        ]);

        $user = Auth::user();
        $stock = Stock::findOrFail($request->stock_id);

        if (!$user->trading_enabled) {
            return back()->with('error', 'Trading is not enabled for your account.');
        }

        // Get USDT wallet (replace this with your actual USDT ID)
        $usdt = Cryptocurrency::where('symbol', 'USDT')->firstOrFail();
        $wallet = $this->getUserWallet($user, $usdt->id);

        $total_amount = $request->quantity * $request->price;
        $commission = $total_amount * 0.001; // 0.1% commission
        $net_amount = $total_amount + $commission;

        // ✅ Check USDT wallet balance
        if ($wallet->available_balance < $net_amount) {
            return back()->with('error', 'Insufficient USDT balance.');
        }

        DB::transaction(function () use ($user, $stock, $wallet, $request, $total_amount, $commission, $net_amount) {
            // Lock wallet row for update
            $wallet->lockForUpdate();

            // Recheck inside transaction
            if ($wallet->available_balance < $net_amount) {
                throw new \Exception('Insufficient USDT balance.');
            }

            // Create transaction
            $transaction = StockTransaction::create([
                'user_id' => $user->id,
                'stock_id' => $stock->id,
                'type' => StockTransaction::TYPE_BUY,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'total_amount' => $total_amount,
                'commission' => $commission,
                'net_amount' => $net_amount,
                'status' => StockTransaction::STATUS_EXECUTED,
                'executed_at' => now(),
            ]);

            // ✅ Deduct from wallet
            $wallet->decrement('balance', $net_amount);
            $wallet->decrement('balance', $net_amount);

            // Update portfolio
            $portfolio = Portfolio::firstOrNew([
                'user_id' => $user->id,
                'stock_id' => $stock->id,
            ]);

            if ($portfolio->exists) {
                $new_quantity = $portfolio->quantity + $request->quantity;
                $new_total_invested = $portfolio->total_invested + $total_amount;
                $new_average_price = $new_total_invested / $new_quantity;

                $portfolio->update([
                    'quantity' => $new_quantity,
                    'average_price' => $new_average_price,
                    'total_invested' => $new_total_invested,
                ]);
            } else {
                $portfolio->fill([
                    'quantity' => $request->quantity,
                    'average_price' => $request->price,
                    'total_invested' => $total_amount,
                ])->save();
            }

            // Send email notifications
            Mail::to($user)->send(new StockPurchased($transaction));

            // Admin alert
            if ($total_amount > 10000) {
                $adminEmail = config('mail.admin_email', 'admin@tradingplatform.com');
                Mail::to($adminEmail)->send(new AdminTradeNotification($transaction));
            }
        });

        return back()->with('success', 'Buy order executed successfully using USDT.');
    }

    public function sell(Request $request)
    {
        $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0.01',
        ]);

        $user = Auth::user();
        $stock = Stock::findOrFail($request->stock_id);

        if (!$user->trading_enabled) {
            return back()->with('error', 'Trading is not enabled for your account.');
        }

        $portfolio = Portfolio::where('user_id', $user->id)
            ->where('stock_id', $stock->id)
            ->first();

        if (!$portfolio || $portfolio->quantity < $request->quantity) {
            return back()->with('error', 'Insufficient shares to sell.');
        }

        // ✅ Get USDT wallet
        $usdt = Cryptocurrency::where('symbol', 'USDT')->firstOrFail();
        $wallet = $this->getUserWallet($user, $usdt->id);

        $total_amount = $request->quantity * $request->price;
        $commission = $total_amount * 0.001;
        $net_amount = $total_amount - $commission;

        DB::transaction(function () use ($user, $stock, $wallet, $portfolio, $request, $total_amount, $commission, $net_amount) {
            $wallet->lockForUpdate();

            // Calculate profit/loss
            $cost_per_share = $portfolio->total_invested / $portfolio->quantity;
            $sold_cost = $cost_per_share * $request->quantity;
            $profit_loss = $total_amount - $sold_cost;

            // Create transaction
            $transaction = StockTransaction::create([
                'user_id' => $user->id,
                'stock_id' => $stock->id,
                'type' => StockTransaction::TYPE_SELL,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'total_amount' => $total_amount,
                'commission' => $commission,
                'net_amount' => $net_amount,
                'status' => StockTransaction::STATUS_EXECUTED,
                'executed_at' => now(),
            ]);

            // ✅ Credit wallet
            $wallet->increment('balance', $net_amount);
            $wallet->increment('balance', $net_amount);

            // Update portfolio
            if ($portfolio->quantity == $request->quantity) {
                $portfolio->delete();
            } else {
                $remaining_quantity = $portfolio->quantity - $request->quantity;
                $sold_investment = ($portfolio->total_invested / $portfolio->quantity) * $request->quantity;

                $portfolio->update([
                    'quantity' => $remaining_quantity,
                    'total_invested' => $portfolio->total_invested - $sold_investment,
                ]);
            }

            Mail::to($user)->send(new StockSold($transaction, $profit_loss));

            if ($total_amount > 10000) {
                $adminEmail = config('mail.admin_email', 'admin@tradingplatform.com');
                Mail::to($adminEmail)->send(new AdminTradeNotification($transaction));
            }
        });

        return back()->with('success', 'Sell order executed successfully. Proceeds credited to USDT wallet.');
    }

    private function getUserWallet($user, $cryptocurrencyId)
    {
        if (!$user) {
            return new Wallet([
                'user_id' => null,
                'cryptocurrency_id' => $cryptocurrencyId,
                'balance' => 0,
                'locked_balance' => 0,
                'available_balance' => 0
            ]);
        }

        try {
            return $user->getOrCreateWallet($cryptocurrencyId);
        } catch (\Exception $e) {
            Log::error('Wallet retrieval failed: '.$e->getMessage(), [
                'user_id' => $user->id,
                'cryptocurrency_id' => $cryptocurrencyId
            ]);

            return new Wallet([
                'user_id' => $user->id,
                'cryptocurrency_id' => $cryptocurrencyId,
                'balance' => 0,
                'locked_balance' => 0,
                'available_balance' => 0
            ]);
        }
    }


    // public function buy(Request $request)
    // {
    //     $request->validate([
    //         'stock_id' => 'required|exists:stocks,id',
    //         'quantity' => 'required|integer|min:1',
    //         'price' => 'required|numeric|min:0.01',
    //     ]);

    //     $user = Auth::user();
    //     $stock = Stock::findOrFail($request->stock_id);
        
    //     if (!$user->trading_enabled) {
    //         return back()->with('error', 'Trading is not enabled for your account.');
    //     }

    //     $total_amount = $request->quantity * $request->price;
    //     $commission = $total_amount * 0.001; // 0.1% commission
    //     $net_amount = $total_amount + $commission;

    //     if ($user->balance < $net_amount) {
    //         return back()->with('error', 'Insufficient balance.');
    //     }

    //     DB::transaction(function () use ($user, $stock, $request, $total_amount, $commission, $net_amount) {
    //         // Create transaction
    //         $transaction = StockTransaction::create([
    //             'user_id' => $user->id,
    //             'stock_id' => $stock->id,
    //             'type' => StockTransaction::TYPE_BUY,
    //             'quantity' => $request->quantity,
    //             'price' => $request->price,
    //             'total_amount' => $total_amount,
    //             'commission' => $commission,
    //             'net_amount' => $net_amount,
    //             'status' => StockTransaction::STATUS_EXECUTED,
    //             'executed_at' => now(),
    //         ]);

    //         // Update user balance
    //         $user->decrement('balance', $net_amount);

    //         // Update portfolio
    //         $portfolio = Portfolio::firstOrNew([
    //             'user_id' => $user->id,
    //             'stock_id' => $stock->id,
    //         ]);

    //         if ($portfolio->exists) {
    //             $new_quantity = $portfolio->quantity + $request->quantity;
    //             $new_total_invested = $portfolio->total_invested + $total_amount;
    //             $new_average_price = $new_total_invested / $new_quantity;

    //             $portfolio->update([
    //                 'quantity' => $new_quantity,
    //                 'average_price' => $new_average_price,
    //                 'total_invested' => $new_total_invested,
    //             ]);
    //         } else {
    //             $portfolio->fill([
    //                 'quantity' => $request->quantity,
    //                 'average_price' => $request->price,
    //                 'total_invested' => $total_amount,
    //             ])->save();
    //         }
            
    //         // Send email notifications
    //         Mail::to($user)->send(new StockPurchased($transaction));
            
    //         // Send admin notification for large trades (>$10,000)
    //         if ($total_amount > 10000) {
    //             $adminEmail = config('mail.admin_email', 'admin@tradingplatform.com');
    //             Mail::to($adminEmail)->send(new AdminTradeNotification($transaction));
    //         }
    //     });

    //     return back()->with('success', 'Buy order executed successfully.');
    // }

    // public function sell(Request $request)
    // {
    //     $request->validate([
    //         'stock_id' => 'required|exists:stocks,id',
    //         'quantity' => 'required|integer|min:1',
    //         'price' => 'required|numeric|min:0.01',
    //     ]);

    //     $user = Auth::user();
    //     $stock = Stock::findOrFail($request->stock_id);
        
    //     if (!$user->trading_enabled) {
    //         return back()->with('error', 'Trading is not enabled for your account.');
    //     }

    //     $portfolio = Portfolio::where('user_id', $user->id)
    //                          ->where('stock_id', $stock->id)
    //                          ->first();

    //     if (!$portfolio || $portfolio->quantity < $request->quantity) {
    //         return back()->with('error', 'Insufficient shares to sell.');
    //     }

    //     $total_amount = $request->quantity * $request->price;
    //     $commission = $total_amount * 0.001; // 0.1% commission
    //     $net_amount = $total_amount - $commission;

    //     DB::transaction(function () use ($user, $stock, $portfolio, $request, $total_amount, $commission, $net_amount) {
    //         // Calculate profit/loss
    //         $cost_per_share = $portfolio->total_invested / $portfolio->quantity;
    //         $sold_cost = $cost_per_share * $request->quantity;
    //         $profit_loss = $total_amount - $sold_cost;
            
    //         // Create transaction
    //         $transaction = StockTransaction::create([
    //             'user_id' => $user->id,
    //             'stock_id' => $stock->id,
    //             'type' => StockTransaction::TYPE_SELL,
    //             'quantity' => $request->quantity,
    //             'price' => $request->price,
    //             'total_amount' => $total_amount,
    //             'commission' => $commission,
    //             'net_amount' => $net_amount,
    //             'status' => StockTransaction::STATUS_EXECUTED,
    //             'executed_at' => now(),
    //         ]);

    //         // Update user balance
    //         $user->increment('balance', $net_amount);

    //         // Update portfolio
    //         if ($portfolio->quantity == $request->quantity) {
    //             $portfolio->delete();
    //         } else {
    //             $remaining_quantity = $portfolio->quantity - $request->quantity;
    //             $sold_investment = ($portfolio->total_invested / $portfolio->quantity) * $request->quantity;
                
    //             $portfolio->update([
    //                 'quantity' => $remaining_quantity,
    //                 'total_invested' => $portfolio->total_invested - $sold_investment,
    //             ]);
    //         }
            
    //         // Send email notifications
    //         Mail::to($user)->send(new StockSold($transaction, $profit_loss));
            
    //         // Send admin notification for large trades (>$10,000)
    //         if ($total_amount > 10000) {
    //             $adminEmail = config('mail.admin_email', 'admin@tradingplatform.com');
    //             Mail::to($adminEmail)->send(new AdminTradeNotification($transaction));
    //         }
    //     });

    //     return back()->with('success', 'Sell order executed successfully.');
    // }
}