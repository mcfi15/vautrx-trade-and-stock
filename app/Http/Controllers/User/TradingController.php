<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use App\Models\Trade;
use App\Models\Wallet;
use App\Models\TradingPair;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\TradingEngineService;

class TradingController extends Controller
{


    public function show($pairId)
{
    try {
        // Load trading pair with currencies
        $tradingPair = TradingPair::with(['baseCurrency', 'quoteCurrency'])
            ->findOrFail($pairId);

        $currentPrice = $tradingPair->getCurrentPrice() ?? 0;


        // Get markets data for the sidebar
        $markets = [
            'USDT' => TradingPair::with(['baseCurrency', 'quoteCurrency'])
                ->whereHas('quoteCurrency', fn($q) => $q->where('symbol', 'USDT'))
                ->active()
                ->get(),

            'BTC' => TradingPair::with(['baseCurrency', 'quoteCurrency'])
                ->whereHas('quoteCurrency', fn($q) => $q->where('symbol', 'BTC'))
                ->active()
                ->get(),

            'ETH' => TradingPair::with(['baseCurrency', 'quoteCurrency'])
                ->whereHas('quoteCurrency', fn($q) => $q->where('symbol', 'ETH'))
                ->active()
                ->get(),

            'EUR' => TradingPair::with(['baseCurrency', 'quoteCurrency'])
                ->whereHas('quoteCurrency', fn($q) => $q->where('symbol', 'EUR'))
                ->active()
                ->get(),
        ];

        // Load all active trading pairs for dropdown
        $tradingPairs = TradingPair::with(['baseCurrency', 'quoteCurrency'])
            ->where('is_active', true)
            ->orderBy('symbol')
            ->get();

        // Get the current user (can be null)
        $user = Auth::user();

        // Get wallets with proper error handling
        $baseWallet = $this->getUserWallet($user, $tradingPair->base_currency_id);
        $quoteWallet = $this->getUserWallet($user, $tradingPair->quote_currency_id);

        // Null-safe user orders
        $userOrders = collect();
        $closedOrders = collect();
        
        if ($user) {
            try {
                $userOrders = $user->orders()
                    ->where('trading_pair_id', $pairId)
                    ->whereIn('status', ['pending', 'partial'])
                    ->latest()
                    ->get();

                $closedOrders = $user->orders()
                    ->where('trading_pair_id', $pairId)
                    ->whereIn('status', ['completed', 'cancelled', 'failed'])
                    ->latest()
                    ->take(20)
                    ->get();
            } catch (\Exception $e) {
                Log::error("Orders fetch failed: ".$e->getMessage(), [
                    'user_id' => $user->id,
                    'pair_id' => $pairId
                ]);
            }
        }

        // Public order book
        $buyOrders = Order::where('trading_pair_id', $pairId)
            ->where('side', 'buy')
            ->where('type', 'limit')
            ->whereIn('status', ['pending', 'partial'])
            ->orderBy('price', 'desc')
            ->take(20)
            ->get();

        $sellOrders = Order::where('trading_pair_id', $pairId)
            ->where('side', 'sell')
            ->where('type', 'limit')
            ->whereIn('status', ['pending', 'partial'])
            ->orderBy('price', 'asc')
            ->take(20)
            ->get();

        // Recent trades
        $recentTrades = $tradingPair->trades()
            ->with(['buyer', 'seller'])
            ->latest()
            ->take(50)
            ->get();

        return view('trading.spot', compact(
            'tradingPair',
            'tradingPairs',
            'baseWallet',
            'quoteWallet',
            'buyOrders',
            'sellOrders',
            'recentTrades',
            'markets',
            'userOrders',
            'closedOrders',
            'currentPrice'  // pass currentPrice to Blade
        ));

    } catch (\Exception $e) {
        Log::error('Trading page error: '.$e->getMessage(), [
            'pair_id' => $pairId,
            'user_id' => Auth::id(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect('trade/spot')
            ->with('error', 'Trading pair not found or temporarily unavailable.');
    }
}


    public function placeOrder(Request $request)
    {
        $request->validate([
            'trading_pair_id' => 'required|exists:trading_pairs,id',
            'type' => 'required|in:limit,market,stop_limit',
            'side' => 'required|in:buy,sell',
            'price' => 'nullable|required_if:type,limit|numeric|min:0.00000001',
            'stop_price' => 'nullable|required_if:type,stop_limit|numeric|min:0.00000001',
            'quantity' => 'required|numeric|min:0.00000001',
        ]);

        return DB::transaction(function () use ($request) {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }

            $tradingPair = TradingPair::findOrFail($request->trading_pair_id);
            
            // Calculate total amount and fees
            if ($request->type === 'market') {
                $currentPrice = $tradingPair->getCurrentPrice();
                $totalAmount = $request->quantity * $currentPrice;
                $price = $currentPrice;
                $stopPrice = null;
            } else if ($request->type === 'stop_limit') {
                $totalAmount = $request->quantity * $request->price;
                $price = $request->price;
                $stopPrice = $request->stop_price;
            } else { // limit order
                $totalAmount = $request->quantity * $request->price;
                $price = $request->price;
                $stopPrice = null;
            }

            $fee = $totalAmount * $tradingPair->trading_fee;

            // Check balances and lock funds
            if ($request->side === 'buy') {
                $quoteWallet = $user->getOrCreateWallet($tradingPair->quote_currency_id);
                $requiredAmount = $totalAmount + $fee;
                
                if ($quoteWallet->available_balance < $requiredAmount) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient '.$tradingPair->quoteCurrency->symbol.' balance. Available: '.$quoteWallet->available_balance.', Required: '.$requiredAmount
                    ], 400);
                }

                // Lock balance for buy order
                if (!$quoteWallet->lockBalance($requiredAmount)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to lock balance for order'
                    ], 400);
                }
            } else {
                $baseWallet = $user->getOrCreateWallet($tradingPair->base_currency_id);
                
                if ($baseWallet->available_balance < $request->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient '.$tradingPair->baseCurrency->symbol.' balance. Available: '.$baseWallet->available_balance.', Required: '.$request->quantity
                    ], 400);
                }

                // Lock balance for sell order
                if (!$baseWallet->lockBalance($request->quantity)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to lock balance for order'
                    ], 400);
                }
            }

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'trading_pair_id' => $tradingPair->id,
                'type' => $request->type,
                'side' => $request->side,
                'price' => $price,
                'stop_price' => $stopPrice,
                'quantity' => $request->quantity,
                'remaining_quantity' => $request->quantity,
                'filled_quantity' => 0,
                'total_amount' => $totalAmount,
                'fee' => $fee,
                'status' => 'pending',
            ]);

            Log::info('Order created:', [
                'order_id' => $order->id,
                'user_id' => $user->id,
                'side' => $request->side,
                'quantity' => $request->quantity,
                'total_amount' => $totalAmount
            ]);

            // Process market orders immediately
            if ($request->type === 'market') {
                $this->processMarketOrder($order);
            }

            return response()->json([
                'success' => true,
                'order' => $order,
                'message' => 'Order placed successfully'
            ]);
        });
    }

    private function processMarketOrder($order)
{
    try {
        // Update order status
        $order->update([
            'status' => 'completed',
            'filled_quantity' => $order->quantity,
            'remaining_quantity' => 0,
            'executed_at' => now()
        ]);

        // For market orders, we need to handle buyer/seller logic differently
        // Since it's a market order against the system, we don't have a specific seller
        
        if ($order->side === 'buy') {
            // For BUY market orders: user is buying from the market (system)
            $buyer_id = $order->user_id;
            $seller_id = null; // Market order from system
            $buyer_fee = $order->fee;
            $seller_fee = 0;
        } else {
            // For SELL market orders: user is selling to the market (system)
            $buyer_id = null; // Market order to system
            $seller_id = $order->user_id;
            $buyer_fee = 0;
            $seller_fee = $order->fee;
        }

        // Create trade record with proper null handling
        Trade::create([
            'order_id' => $order->id,
            'trading_pair_id' => $order->trading_pair_id,
            'buyer_id' => $buyer_id,
            'seller_id' => $seller_id,
            'price' => $order->price,
            'quantity' => $order->quantity,
            'total_amount' => $order->total_amount,
            'buyer_fee' => $buyer_fee,
            'seller_fee' => $seller_fee,
        ]);

        Log::info('Market order processed:', [
            'order_id' => $order->id,
            'side' => $order->side,
            'quantity' => $order->quantity,
            'buyer_id' => $buyer_id,
            'seller_id' => $seller_id
        ]);

        // Update wallets
        $this->settleTrade($order);

    } catch (\Exception $e) {
        Log::error('Market order processing failed: '.$e->getMessage(), [
            'order_id' => $order->id,
            'trace' => $e->getTraceAsString()
        ]);
        throw $e;
    }
}

    private function settleTrade($order)
    {
        $tradingPair = $order->tradingPair;
        $baseWallet = $order->user->getOrCreateWallet($tradingPair->base_currency_id);
        $quoteWallet = $order->user->getOrCreateWallet($tradingPair->quote_currency_id);

        Log::info('Before settleTrade:', [
            'side' => $order->side,
            'base_wallet_balance' => $baseWallet->balance,
            'base_wallet_locked' => $baseWallet->locked_balance,
            'quote_wallet_balance' => $quoteWallet->balance,
            'quote_wallet_locked' => $quoteWallet->locked_balance,
            'quantity' => $order->quantity,
            'total_amount' => $order->total_amount,
            'fee' => $order->fee
        ]);

        if ($order->side === 'buy') {
            // FOR BUY ORDERS:
            // 1. ADD the purchased coins to base wallet
            $baseWallet->addBalance($order->quantity);
            
            // 2. Subtract the total cost from quote wallet
            $totalCost = $order->total_amount + $order->fee;
            $quoteWallet->subtractBalance($totalCost);
            
            // 3. Unlock the locked balance
            $quoteWallet->unlockBalance($totalCost);
            
            Log::info('Buy order settled:', [
                'added_to_base' => $order->quantity,
                'subtracted_from_quote' => $totalCost,
                'new_base_balance' => $baseWallet->balance,
                'new_quote_balance' => $quoteWallet->balance
            ]);

        } else {
            // FOR SELL ORDERS:
            // 1. Subtract the sold coins from base wallet
            $baseWallet->subtractBalance($order->quantity);
            
            // 2. ADD the sale proceeds to quote wallet
            $netProceeds = $order->total_amount - $order->fee;
            $quoteWallet->addBalance($netProceeds);
            
            // 3. Unlock the locked balance
            $baseWallet->unlockBalance($order->quantity);
            
            Log::info('Sell order settled:', [
                'subtracted_from_base' => $order->quantity,
                'added_to_quote' => $netProceeds,
                'new_base_balance' => $baseWallet->balance,
                'new_quote_balance' => $quoteWallet->balance
            ]);
        }

        // Create transaction records
        $this->createTransactionRecords($order, $baseWallet, $quoteWallet);
    }

    private function createTransactionRecords($order, $baseWallet, $quoteWallet)
    {
        $tradingPair = $order->tradingPair;
        
        if ($order->side === 'buy') {
            // Record the BTC purchase
            Transaction::create([
                'user_id' => $order->user_id,
                'cryptocurrency_id' => $tradingPair->base_currency_id,
                'type' => 'trade_buy',
                'amount' => $order->quantity,
                'fee' => $order->fee,
                'balance_before' => $baseWallet->balance - $order->quantity,
                'balance_after' => $baseWallet->balance,
                'description' => 'Buy '.$order->quantity.' '.$tradingPair->baseCurrency->symbol,
            ]);

            // Record the USDT spending
            Transaction::create([
                'user_id' => $order->user_id,
                'cryptocurrency_id' => $tradingPair->quote_currency_id,
                'type' => 'trade_sell',
                'amount' => -($order->total_amount + $order->fee),
                'fee' => 0,
                'balance_before' => $quoteWallet->balance + ($order->total_amount + $order->fee),
                'balance_after' => $quoteWallet->balance,
                'description' => 'Spent for buying '.$order->quantity.' '.$tradingPair->baseCurrency->symbol,
            ]);
        } else {
            // Record the BTC sale
            Transaction::create([
                'user_id' => $order->user_id,
                'cryptocurrency_id' => $tradingPair->base_currency_id,
                'type' => 'trade_sell',
                'amount' => -$order->quantity,
                'fee' => $order->fee,
                'balance_before' => $baseWallet->balance + $order->quantity,
                'balance_after' => $baseWallet->balance,
                'description' => 'Sell '.$order->quantity.' '.$tradingPair->baseCurrency->symbol,
            ]);

            // Record the USDT receipt
            Transaction::create([
                'user_id' => $order->user_id,
                'cryptocurrency_id' => $tradingPair->quote_currency_id,
                'type' => 'trade_buy',
                'amount' => $order->total_amount - $order->fee,
                'fee' => 0,
                'balance_before' => $quoteWallet->balance - ($order->total_amount - $order->fee),
                'balance_after' => $quoteWallet->balance,
                'description' => 'Received from selling '.$order->quantity.' '.$tradingPair->baseCurrency->symbol,
            ]);
        }
    }

    public function cancelOrder($orderId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        $order = Order::where('user_id', $user->id)
            ->where('id', $orderId)
            ->firstOrFail();

        return DB::transaction(function () use ($order) {
            if ($order->cancel()) {
                // Unlock the locked balance
                $tradingPair = $order->tradingPair;
                if ($order->side === 'buy') {
                    $wallet = $order->user->getOrCreateWallet($tradingPair->quote_currency_id);
                    $wallet->unlockBalance($order->total_amount + $order->fee);
                } else {
                    $wallet = $order->user->getOrCreateWallet($tradingPair->base_currency_id);
                    $wallet->unlockBalance($order->remaining_quantity);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Order cancelled successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Unable to cancel order'
            ], 400);
        });
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

}
