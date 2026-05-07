<?php
// app/Http/Controllers/Admin/StockPriceUpdateController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\StockPriceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StockPriceUpdateController extends Controller
{
    /**
     * Update single stock price (works with mock data for testing)
     */
    // app/Http/Controllers/Admin/StockPriceUpdateController.php

    public function updateStockPrice(Request $request, Stock $stock)
    {
        try {
            // 1. Generate the data (Use your existing logic)
            $priceData = $this->generateRealisticPrice($stock);

            // 2. Ensure your model can save this
            // Check if StockPriceHistory model has $fillable set!
            \App\Models\StockPriceHistory::updateOrCreate(
                ['stock_id' => $stock->id, 'date' => now()->toDateString()],
                [
                    'open_price' => $priceData['opening_price'],
                    'high_price' => $priceData['high_price'],
                    'low_price' => $priceData['low_price'],
                    'close_price' => $priceData['current_price'],
                    'volume' => $priceData['volume'],
                    'adjusted_close' => $priceData['current_price'],
                ]
            );

            $oldPrice = (float) $stock->current_price;

            // 3. Update the Stock table
            $stock->update([
                'current_price' => $priceData['current_price'],
                'high_price' => $priceData['high_price'],
                'low_price' => $priceData['low_price'],
                'volume' => $priceData['volume'],
                'last_updated' => now(),
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'symbol' => $stock->symbol,
                    'old_price' => $oldPrice,
                    'new_price' => (float) $stock->current_price,
                    'change' => (float) $stock->current_price - $oldPrice,
                    'change_percentage' => $oldPrice > 0 ? (((float) $stock->current_price - $oldPrice) / $oldPrice) * 100 : 0,
                    'last_updated' => now()->toIso8601String(),
                ]
            ]);

        } catch (\Exception $e) {
            // This is where you find out WHAT is actually wrong
            \Illuminate\Support\Facades\Log::error("Stock Error ({$stock->symbol}): " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage() // Change this so your JS shows the REAL error
            ], 500);
        }
    }

    /**
     * Generate realistic price fluctuations
     */
    private function generateRealisticPrice($stock)
    {
        // Start with current price or default
        $basePrice = $stock->current_price > 0 ? $stock->current_price : 100;

        // Random change between -3% and +3%
        $changePercent = (mt_rand(-300, 300) / 100);
        $newPrice = max(0.01, $basePrice * (1 + $changePercent / 100));

        // Calculate day high/low
        $highPrice = max($basePrice, $newPrice) + abs($changePercent / 2);
        $lowPrice = min($basePrice, $newPrice) - abs($changePercent / 2);

        // Random volume between 100k and 10M
        $volume = mt_rand(100000, 10000000);

        return [
            'current_price' => round($newPrice, 2),
            'opening_price' => round($basePrice, 2),
            'high_price' => round($highPrice, 2),
            'low_price' => round($lowPrice, 2),
            'volume' => $volume,
        ];
    }

    /**
     * Bulk update all stocks
     */
    public function bulkUpdatePrices(Request $request)
    {
        $stocks = Stock::where('is_active', true)->get();
        $results = [];

        foreach ($stocks as $stock) {
            $result = $this->updateStockPrice($request, $stock);
            $results[] = $result->getData();
            sleep(1); // Small delay
        }

        return response()->json($results);
    }

    /**
     * Get live data for dashboard
     */
    public function getLiveData(Request $request)
    {
        $stocks = Stock::where('is_active', true)
            ->select('id', 'symbol', 'name', 'current_price', 'high_price', 'low_price', 'volume', 'market_cap', 'sector', 'last_updated')
            ->get();

        $stocks->each(function ($stock) {
            $stock->change = $stock->getChangeAttribute();
            $stock->change_percentage = $stock->getChangePercentageAttribute();
        });

        return response()->json([
            'stocks' => $stocks,
            'timestamp' => now()->toIso8601String(),
            'update_interval' => 30
        ]);
    }
}