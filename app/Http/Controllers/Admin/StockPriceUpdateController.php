<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\StockPriceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StockPriceUpdateController extends Controller
{
    /**
     * Update single stock price with REAL data from Yahoo Finance
     */
    public function updateStockPrice(Request $request, Stock $stock)
    {
        // DEBUG: Log the request
        Log::info('=== UPDATE STOCK PRICE CALLED ===');
        Log::info('Stock ID: ' . $stock->id);
        Log::info('Stock Symbol: ' . $stock->symbol);
        Log::info('Stock Name: ' . $stock->name);
        
        try {
            // Direct call to Yahoo Finance - NO fallbacks, NO complexity
            $priceData = $this->getYahooStockPrice($stock->symbol);
            
            if (!$priceData || $priceData['current_price'] <= 0) {
                throw new \Exception('No valid price data returned');
            }
            
            Log::info('Got price data: ' . json_encode($priceData));
            
            // Save to price history
            StockPriceHistory::updateOrCreate(
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
            
            // Update the Stock table
            $stock->update([
                'current_price' => $priceData['current_price'],
                'high_price' => $priceData['high_price'],
                'low_price' => $priceData['low_price'],
                'volume' => $priceData['volume'],
                'last_updated' => now(),
                'previous_close' => $oldPrice,
            ]);
            
            $change = (float) $stock->current_price - $oldPrice;
            $changePercentage = $oldPrice > 0 ? ($change / $oldPrice) * 100 : 0;
            
            Log::info('Update successful for ' . $stock->symbol);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'symbol' => $stock->symbol,
                    'old_price' => $oldPrice,
                    'new_price' => (float) $stock->current_price,
                    'change' => round($change, 2),
                    'change_percentage' => round($changePercentage, 2),
                    'last_updated' => now()->toIso8601String(),
                    'source' => 'YAHOO_LIVE'
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Update failed for ' . $stock->symbol . ': ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Return error with details
            return response()->json([
                'success' => false,
                'message' => 'Error updating ' . $stock->symbol . ': ' . $e->getMessage(),
                'debug_info' => [
                    'symbol' => $stock->symbol,
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }
    
    /**
     * Get stock price from Yahoo Finance - SIMPLE AND DIRECT
     */
    private function getYahooStockPrice($symbol)
    {
        try {
            // Clean the symbol
            $symbol = trim(strtoupper($symbol));
            
            // Yahoo Finance API URL
            $url = "https://query1.finance.yahoo.com/v8/finance/chart/{$symbol}";
            
            Log::info('Calling Yahoo URL: ' . $url);
            
            // Make the request
            $response = Http::timeout(10)->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Accept' => 'application/json',
            ])->get($url);
            
            Log::info('Response status: ' . $response->status());
            
            if (!$response->successful()) {
                throw new \Exception('HTTP ' . $response->status());
            }
            
            $data = $response->json();
            Log::info('Response data keys: ' . json_encode(array_keys($data)));
            
            // Extract the price data
            if (isset($data['chart']['result'][0]['meta'])) {
                $meta = $data['chart']['result'][0]['meta'];
                
                $currentPrice = $meta['regularMarketPrice'] ?? null;
                $openPrice = $meta['regularMarketOpen'] ?? null;
                $highPrice = $meta['regularMarketDayHigh'] ?? null;
                $lowPrice = $meta['regularMarketDayLow'] ?? null;
                $volume = $meta['regularMarketVolume'] ?? null;
                $previousClose = $meta['previousClose'] ?? null;
                
                Log::info("Raw data - Price: {$currentPrice}, Open: {$openPrice}, High: {$highPrice}, Low: {$lowPrice}, Volume: {$volume}");
                
                if ($currentPrice && $currentPrice > 0) {
                    return [
                        'current_price' => (float) $currentPrice,
                        'opening_price' => (float) ($openPrice ?? $previousClose ?? $currentPrice),
                        'high_price' => (float) ($highPrice ?? $currentPrice),
                        'low_price' => (float) ($lowPrice ?? $currentPrice),
                        'volume' => (int) ($volume ?? 0),
                    ];
                }
            }
            
            // Try alternative endpoint if the first one fails
            return $this->getYahooStockPriceAlt($symbol);
            
        } catch (\Exception $e) {
            Log::error('Yahoo Finance error: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Alternative Yahoo Finance endpoint
     */
    private function getYahooStockPriceAlt($symbol)
    {
        try {
            $url = "https://query2.finance.yahoo.com/v10/finance/quoteSummary/{$symbol}?modules=price";
            
            $response = Http::timeout(10)->withHeaders([
                'User-Agent' => 'Mozilla/5.0',
            ])->get($url);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['quoteSummary']['result'][0]['price'])) {
                    $priceData = $data['quoteSummary']['result'][0]['price'];
                    
                    $currentPrice = $priceData['regularMarketPrice']['raw'] ?? null;
                    $openPrice = $priceData['regularMarketOpen']['raw'] ?? null;
                    $highPrice = $priceData['regularMarketDayHigh']['raw'] ?? null;
                    $lowPrice = $priceData['regularMarketDayLow']['raw'] ?? null;
                    $volume = $priceData['regularMarketVolume']['raw'] ?? null;
                    
                    if ($currentPrice && $currentPrice > 0) {
                        return [
                            'current_price' => (float) $currentPrice,
                            'opening_price' => (float) ($openPrice ?? $currentPrice),
                            'high_price' => (float) ($highPrice ?? $currentPrice),
                            'low_price' => (float) ($lowPrice ?? $currentPrice),
                            'volume' => (int) ($volume ?? 0),
                        ];
                    }
                }
            }
            
            throw new \Exception('No data from alternative endpoint');
            
        } catch (\Exception $e) {
            Log::error('Yahoo Finance Alt error: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * TEST METHOD - Direct API test
     */
    public function testDirectApi($symbol = 'AAPL')
    {
        try {
            $result = $this->getYahooStockPrice($symbol);
            
            return response()->json([
                'success' => true,
                'symbol' => $symbol,
                'data' => $result,
                'message' => 'API is working!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'symbol' => $symbol,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
    
    /**
     * Sync a specific stock by symbol (for AJAX calls)
     */
    public function syncStockBySymbol(Request $request)
    {
        $symbol = $request->input('symbol');
        $stock = Stock::where('symbol', $symbol)->first();
        
        if (!$stock) {
            return response()->json([
                'success' => false,
                'message' => "Stock {$symbol} not found"
            ], 404);
        }
        
        return $this->updateStockPrice($request, $stock);
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
    
    /**
     * Bulk update all stocks
     */
    public function bulkUpdatePrices(Request $request)
    {
        $stocks = Stock::where('is_active', true)->get();
        $results = [];
        
        foreach ($stocks as $stock) {
            try {
                $response = $this->updateStockPrice($request, $stock);
                $results[] = $response->getData();
            } catch (\Exception $e) {
                $results[] = [
                    'success' => false,
                    'symbol' => $stock->symbol,
                    'error' => $e->getMessage()
                ];
            }
            sleep(1);
        }
        
        return response()->json($results);
    }
}