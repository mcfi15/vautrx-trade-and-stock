<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
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
        $this->middleware('auth');
        $this->tradingEngine = $tradingEngine;
    }

    public function show($pairId)
    {
        $tradingPair = TradingPair::with(['baseCurrency', 'quoteCurrency'])
            ->findOrFail($pairId);
        
        $user = Auth::user();
        
        // Get user wallets for this pair
        $baseWallet = $user->getOrCreateWallet($tradingPair->base_currency_id);
        $quoteWallet = $user->getOrCreateWallet($tradingPair->quote_currency_id);
        
        // Get order book
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
        
        // Get recent trades
        $recentTrades = $tradingPair->trades()
            ->with(['buyer', 'seller'])
            ->latest()
            ->take(50)
            ->get();
        
        // Get user's active orders for this pair
        $userOrders = $user->orders()
            ->where('trading_pair_id', $pairId)
            ->whereIn('status', ['pending', 'partial'])
            ->latest()
            ->get();
        
        return view('trading.show', compact(
            'tradingPair',
            'baseWallet',
            'quoteWallet',
            'buyOrders',
            'sellOrders',
            'recentTrades',
            'userOrders'
        ));
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
