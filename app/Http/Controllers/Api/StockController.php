<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Get current price for a specific stock
     */
    public function getCurrentPrice(Stock $stock)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'symbol' => $stock->symbol,
                'name' => $stock->name,
                'current_price' => $stock->current_price,
                'change' => $stock->change,
                'change_percentage' => $stock->change_percentage,
                'volume' => $stock->volume,
                'last_updated' => $stock->last_updated,
            ]
        ]);
    }

    /**
     * Get prices for multiple stocks
     */
    public function getPrices(Request $request)
    {
        $symbols = $request->get('symbols', []);
        
        if (empty($symbols)) {
            return response()->json([
                'success' => false,
                'message' => 'No symbols provided'
            ], 400);
        }

        $stocks = Stock::whereIn('symbol', $symbols)
                      ->where('is_active', true)
                      ->get()
                      ->keyBy('symbol');

        $data = [];
        foreach ($symbols as $symbol) {
            $stock = $stocks->get($symbol);
            $data[$symbol] = $stock ? [
                'symbol' => $stock->symbol,
                'name' => $stock->name,
                'current_price' => $stock->current_price,
                'change' => $stock->change,
                'change_percentage' => $stock->change_percentage,
                'volume' => $stock->volume,
                'last_updated' => $stock->last_updated,
            ] : null;
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Get market overview data
     */
    public function getMarketOverview()
    {
        $topGainers = Stock::where('is_active', true)
            ->whereNotNull('closing_price')
            ->where('closing_price', '>', 0)
            ->selectRaw('*, (current_price - closing_price) / closing_price * 100 as change_percentage')
            ->orderByDesc('change_percentage')
            ->limit(5)
            ->get();

        $topLosers = Stock::where('is_active', true)
            ->whereNotNull('closing_price')
            ->where('closing_price', '>', 0)
            ->selectRaw('*, (current_price - closing_price) / closing_price * 100 as change_percentage')
            ->orderBy('change_percentage')
            ->limit(5)
            ->get();

        $mostActive = Stock::where('is_active', true)
            ->orderByDesc('volume')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'top_gainers' => $topGainers,
                'top_losers' => $topLosers,
                'most_active' => $mostActive,
                'total_stocks' => Stock::where('is_active', true)->count(),
                'last_updated' => now(),
            ]
        ]);
    }
}