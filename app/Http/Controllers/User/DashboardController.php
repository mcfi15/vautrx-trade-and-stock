<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use App\Models\TradingPair;
use Illuminate\Http\Request;
use App\Models\Cryptocurrency;
use App\Services\CoinGeckoService;
use App\Services\BlockchainService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private $coinGeckoService;

    public function __construct(CoinGeckoService $coinGeckoService)
    {
        $this->middleware('auth');
        $this->coinGeckoService = $coinGeckoService;
    }

    public function index()
    {
        $user = Auth::user();
        
        // Get user wallets with crypto data
        $wallets = $user->wallets()->with('cryptocurrency')->get();
        
        
        // Calculate total portfolio value
        $totalValue = $user->getTotalPortfolioValue();
        
        // Get recent orders
        $recentOrders = $user->orders()
            ->with(['tradingPair.baseCurrency', 'tradingPair.quoteCurrency'])
            ->latest()
            ->take(10)
            ->get();
        
        // Get recent transactions
        $recentTransactions = $user->transactions()
            ->with('cryptocurrency')
            ->latest()
            ->take(10)
            ->get();
        
        // Get active cryptocurrencies
        $cryptocurrencies = Cryptocurrency::active()->tradable()->get();
        
        return view('user.dashboard', compact(
            'user',
            'wallets',
            'totalValue',
            'recentOrders',
            'recentTransactions',
            'cryptocurrencies'
        ));
    }

    public function markets()
    {
        $tradingPairs = TradingPair::with(['baseCurrency', 'quoteCurrency'])
            ->active()
            ->get();
        
        return view('markets', compact('tradingPairs'));
    }
}
