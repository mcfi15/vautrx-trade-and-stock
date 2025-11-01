<?php

namespace App\Http\Controllers;

use App\Models\TradingPair;
use App\Models\Order;
use App\Services\TradingEngineService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TradingController extends Controller
{
    private $tradingEngine;

    public function __construct(TradingEngineService $tradingEngine)
    {
        // $this->middleware('auth')->except(['show', 'index']);
        // $this->middleware('auth');
        $this->tradingEngine = $tradingEngine;
    }

public function show($pairId)
{
    try {

        // Load trading pair with currencies
        $tradingPair = TradingPair::with(['baseCurrency', 'quoteCurrency'])
            ->findOrFail($pairId);

        // Load all active trading pairs for dropdown
        $tradingPairs = TradingPair::with(['baseCurrency', 'quoteCurrency'])
            ->where('is_active', true)
            ->orderBy('symbol')
            ->get();

        // Get the current user (can be null)
        $user = Auth::user();

        // Null-safe helper to get wallets
        $baseWallet  = $this->getWallet($user, $tradingPair->base_currency_id);
        $quoteWallet = $this->getWallet($user, $tradingPair->quote_currency_id);

        // Null-safe user orders
        $userOrders = collect();
        if ($user) {
            try {
                $userOrders = $user->orders()
                    ->where('trading_pair_id', $pairId)
                    ->whereIn('status', ['pending', 'partial'])
                    ->latest()
                    ->get();
            } catch (\Exception $e) {
                \Log::error("Orders fetch failed: ".$e->getMessage(), [
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

        // Return view with all data
        return view('trading.show', compact(
            'tradingPair',
            'tradingPairs',
            'baseWallet',
            'quoteWallet',
            'buyOrders',
            'sellOrders',
            'recentTrades',
            'userOrders'
        ));

    } catch (\Exception $e) {
        \Log::error('Trading page error: '.$e->getMessage(), [
            'pair_id' => $pairId,
            'user_id' => Auth::id(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->route('trading.index')
            ->with('error', 'Trading pair not found or temporarily unavailable.');
    }
}

/**
 * Null-safe helper to get or create wallet
 */
protected function getWallet($user, $currencyId)
{
    if ($user && method_exists($user, 'getOrCreateWallet')) {
        try {
            return $user->getOrCreateWallet($currencyId);
        } catch (\Exception $e) {
            \Log::warning("Wallet creation failed: ".$e->getMessage(), [
                'user_id' => $user->id ?? null,
                'currency_id' => $currencyId
            ]);
        }
    }
    return null;
}





    public function placeOrder(Request $request)
    {
        $request->validate([
            'trading_pair_id' => 'required|exists:trading_pairs,id',
            'type' => 'required|in:market,limit,stop_loss,stop_limit',
            'side' => 'required|in:buy,sell',
            'quantity' => 'required|numeric|min:0.00000001',
            'price' => 'nullable|numeric|min:0.00000001',
            'stop_price' => 'nullable|numeric|min:0.00000001',
        ]);

        try {
            $order = $this->tradingEngine->placeOrder(
                Auth::id(),
                $request->trading_pair_id,
                $request->type,
                $request->side,
                $request->quantity,
                $request->price,
                $request->stop_price
            );

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',
                'order' => $order,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function cancelOrder($orderId)
    {
        try {
            $order = $this->tradingEngine->cancelOrder($orderId, Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully',
                'order' => $order,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function orderHistory()
    {
        $orders = Auth::user()->orders()
            ->with(['tradingPair.baseCurrency', 'tradingPair.quoteCurrency'])
            ->latest()
            ->paginate(20);
        
        return view('trading.history', compact('orders'));
    }

    public function tradeHistory()
    {
        $user = Auth::user();
        
        $trades = \App\Models\Trade::where('buyer_id', $user->id)
            ->orWhere('seller_id', $user->id)
            ->with(['tradingPair.baseCurrency', 'tradingPair.quoteCurrency'])
            ->latest()
            ->paginate(20);
        
        return view('trading.trades', compact('trades'));
    }

    
}
