<?php
// app/Http/Controllers/Admin/WebSocketStockController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WebSocketStockController extends Controller
{
    /**
     * Render the real-time dashboard
     */
    public function realtimeDashboard()
    {
        $stocks = Stock::where('is_active', true)
            ->select('id', 'symbol', 'name', 'current_price', 'sector', 'exchange')
            ->get();
            
        return view('admin.stocks.realtime', compact('stocks'));
    }
    
    /**
     * Get initial data for WebSocket connection
     */
    public function getInitialData()
    {
        $stocks = Stock::where('is_active', true)
            ->withCount(['stocktransactions', 'portfolios'])
            ->get();
            
        $stocks->each(function ($stock) {
            $stock->change = $stock->getChangeAttribute();
            $stock->change_percentage = $stock->getChangePercentageAttribute();
        });
            
        return response()->json([
            'stocks' => $stocks,
            'last_updated' => now()->toIso8601String(),
            'total_stocks' => $stocks->count(),
            'total_volume' => $stocks->sum('volume'),
            'total_market_cap' => $stocks->sum('market_cap'),
        ]);
    }
}