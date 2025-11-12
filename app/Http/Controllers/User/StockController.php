<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Transaction;
use App\Models\Watchlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Stock::where('is_active', true);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('symbol', 'like', "%{$search}%");
            });
        }

        if ($request->has('sector')) {
            $query->where('sector', $request->get('sector'));
        }

        // For mobile, get more stocks and don't paginate (infinite scroll feel)
        // if ($this->isMobile($request)) {
        //     $stocks = $query->orderBy('symbol')->take(100)->get();
        // } else {
        $stocks = $query->paginate(9);
        // }
        
        $sectors = Stock::distinct()->pluck('sector')->filter();
        $user_watchlist = Auth::user()->watchlists()->pluck('stock_id')->toArray();

        // // Return different views based on device type
        // if ($this->isMobile($request)) {
        //     return view('user.stocks.mobile-index', compact('stocks', 'sectors', 'user_watchlist'));
        // }

        return view('user.stocks.index', compact('stocks', 'sectors', 'user_watchlist'));
    }

    /**
     * Detect if the request is from a mobile device
     */
    // private function isMobile(Request $request)
    // {
    //     $userAgent = $request->header('User-Agent');
        
    //     // Check for mobile user agents
    //     $mobileKeywords = ['Mobile', 'Android', 'iPhone', 'iPad', 'Windows Phone', 'BlackBerry'];
        
    //     foreach ($mobileKeywords as $keyword) {
    //         if (stripos($userAgent, $keyword) !== false) {
    //             return true;
    //         }
    //     }
        
    //     // Also check screen width if available (from JavaScript)
    //     if ($request->has('mobile') && $request->get('mobile') === '1') {
    //         return true;
    //     }
        
    //     return false;
    // }

    public function show(Stock $stock)
    {
        $is_in_watchlist = Auth::user()->watchlists()
            ->where('stock_id', $stock->id)
            ->exists();
            
        $userPortfolio = Auth::user()->portfolios()
            ->where('stock_id', $stock->id)
            ->first();

        // Get recent transactions for this stock by the current user
        $recentTransactions = Auth::user()->stocktransactions()
            ->where('stock_id', $stock->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('user.stocks.show', compact('stock', 'is_in_watchlist', 'userPortfolio', 'recentTransactions'));
    }

    /**
     * Get chart data for a stock
     */
    public function chartData(Stock $stock, Request $request)
    {
        $period = $request->get('period', 30);
        
        // Try to get real historical data first
        $chartData = $this->getRealChartData($stock, $period);
        
        // If no real data available, generate sample data
        if (empty($chartData['labels'])) {
            $chartData = $this->generateSampleChartData($stock, $period);
        }
        
        return response()->json($chartData);
    }

    /**
     * Get real chart data from the database
     */
    private function getRealChartData(Stock $stock, $period = 30)
    {
        $startDate = now()->subDays($period);
        
        $priceHistory = $stock->priceHistory()
            ->where('date', '>=', $startDate)
            ->orderBy('date')
            ->get();
            
        if ($priceHistory->isEmpty()) {
            return ['labels' => [], 'prices' => [], 'volumes' => []];
        }
        
        $labels = [];
        $prices = [];
        $volumes = [];
        
        foreach ($priceHistory as $history) {
            $labels[] = $history->date->format('Y-m-d');
            $prices[] = (float) $history->close_price;
            $volumes[] = $history->volume;
        }
        
        return [
            'labels' => $labels,
            'prices' => $prices,
            'volumes' => $volumes,
            'current_price' => $stock->current_price,
            'symbol' => $stock->symbol,
            'company_name' => $stock->company_name,
            'data_source' => 'real'
        ];
    }

    /**
     * Generate sample chart data for demonstration
     * In production, replace this with actual historical data from your database
     */
    private function generateSampleChartData(Stock $stock, $period = 30)
    {
        $dates = [];
        $prices = [];
        $volumes = [];
        
        $currentPrice = $stock->current_price;
        $baseDate = now()->subDays($period);
        
        // Generate historical data for the specified period
        for ($i = $period; $i >= 0; $i--) {
            $date = $baseDate->copy()->addDays($period - $i);
            
            // Skip weekends for more realistic stock data
            if ($date->isWeekend()) {
                continue;
            }
            
            $dates[] = $date->format('Y-m-d');
            
            // Generate realistic price fluctuation (±3% daily change for longer periods)
            $maxChange = $period > 90 ? 3 : 5; // Smaller daily changes for longer periods
            $change = (rand(-$maxChange * 100, $maxChange * 100) / 100); // -maxChange% to +maxChange%
            $dailyPrice = $currentPrice * (1 + ($change / 100));
            
            // Ensure price doesn't go below $1
            $dailyPrice = max($dailyPrice, 1);
            
            $prices[] = round($dailyPrice, 2);
            $volumes[] = rand(100000, 5000000); // Random volume between 100K-5M
            
            $currentPrice = $dailyPrice; // Use this as base for next day
        }
        
        // Reverse arrays to show chronological order
        $dates = array_reverse($dates);
        $prices = array_reverse($prices);
        $volumes = array_reverse($volumes);
        
        return [
            'labels' => $dates,
            'prices' => $prices,
            'volumes' => $volumes,
            'current_price' => $stock->current_price,
            'symbol' => $stock->symbol,
            'company_name' => $stock->company_name,
            'data_source' => 'sample'
        ];
    }

    public function addToWatchlist(Request $request, Stock $stock)
    {
        $user = Auth::user();
        
        $request->validate([
            'target_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $watchlist = Watchlist::firstOrNew([
            'user_id' => $user->id,
            'stock_id' => $stock->id,
        ]);

        $watchlist->target_price = $request->target_price;
        $watchlist->notes = $request->notes;
        $watchlist->save();

        return back()->with('success', 'Stock added to watchlist successfully.');
    }

    public function removeFromWatchlist(Stock $stock)
    {
        Auth::user()->watchlists()->where('stock_id', $stock->id)->delete();
        
        return back()->with('success', 'Stock removed from watchlist successfully.');
    }

    /**
     * Force mobile view for testing
     */
    // public function mobileIndex(Request $request)
    // {
    //     $query = Stock::where('is_active', true);

    //     if ($request->has('search')) {
    //         $search = $request->get('search');
    //         $query->where(function($q) use ($search) {
    //             $q->where('name', 'like', "%{$search}%")
    //               ->orWhere('symbol', 'like', "%{$search}%");
    //         });
    //     }

    //     if ($request->has('sector')) {
    //         $query->where('sector', $request->get('sector'));
    //     }

    //     $stocks = $query->orderBy('symbol')->take(100)->get();
    //     $sectors = Stock::distinct()->pluck('sector')->filter();
    //     $user_watchlist = Auth::user()->watchlists()->pluck('stock_id')->toArray();

    //     return view('user.stocks.mobile-index', compact('stocks', 'sectors', 'user_watchlist'));
    // }
}