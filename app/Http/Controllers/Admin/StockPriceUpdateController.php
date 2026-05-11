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
     * Update single stock price with REAL data from Alpha Vantage API
     */
    public function updateStockPrice(Request $request, Stock $stock)
    {
        try {
            // Fetch REAL stock data from Alpha Vantage
            $priceData = $this->fetchRealTimeStockData($stock);
            
            // Log what we got for debugging
            Log::info('Price data for ' . $stock->symbol, ['data' => $priceData]);
            
            // If API fails or returns invalid data, use mock as fallback
            if (!$priceData || !isset($priceData['current_price']) || $priceData['current_price'] <= 0) {
                throw new \Exception('Invalid data from API, using mock data fallback');
            }

            // Save to price history
            StockPriceHistory::updateOrCreate(
                ['stock_id' => $stock->id, 'date' => now()->toDateString()],
                [
                    'open_price' => $priceData['opening_price'] ?? $stock->current_price,
                    'high_price' => $priceData['high_price'] ?? $priceData['current_price'],
                    'low_price' => $priceData['low_price'] ?? $priceData['current_price'],
                    'close_price' => $priceData['current_price'],
                    'volume' => $priceData['volume'] ?? $stock->volume,
                    'adjusted_close' => $priceData['current_price'],
                ]
            );

            $oldPrice = (float) $stock->current_price;

            // Update the Stock table
            $stock->update([
                'current_price' => $priceData['current_price'],
                'high_price' => $priceData['high_price'] ?? $priceData['current_price'],
                'low_price' => $priceData['low_price'] ?? $priceData['current_price'],
                'volume' => $priceData['volume'] ?? $stock->volume,
                'last_updated' => now(),
                'previous_close' => $oldPrice,
                'closing_price' => $priceData['closing_price'] ?? $priceData['current_price'],
            ]);

            // Calculate change and percentage
            $change = (float) $stock->current_price - $oldPrice;
            $changePercentage = $oldPrice > 0 ? ($change / $oldPrice) * 100 : 0;

            return response()->json([
                'success' => true,
                'data' => [
                    'symbol' => $stock->symbol,
                    'old_price' => $oldPrice,
                    'new_price' => (float) $stock->current_price,
                    'change' => round($change, 2),
                    'change_percentage' => round($changePercentage, 2),
                    'last_updated' => now()->toIso8601String(),
                    'source' => 'API_REAL_DATA' // This confirms real data is being used
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Stock Error ({$stock->symbol}): " . $e->getMessage());
            
            // FALLBACK: Use mock data when API fails
            return $this->fallbackToMockData($stock);
        }
    }
    
    /**
     * Fetch REAL stock data from Alpha Vantage API
     */
    private function fetchRealTimeStockData($stock)
    {
        $apiKey = "JL8NB7AX0PP1OKVF";
        
        try {
            $url = "https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol={$stock->symbol}&apikey={$apiKey}";
            
            Log::info('Fetching real data for: ' . $stock->symbol);
            
            // IMPORTANT: SSL verification disabled for development
            // For production, fix your SSL certificate instead of using 'verify' => false
            $response = Http::timeout(30)->withOptions([
                'verify' => false,  // Required for your current SSL setup
            ])->get($url);
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Check if we got valid data
                if (isset($data['Global Quote']) && !empty($data['Global Quote'])) {
                    $quote = $data['Global Quote'];
                    
                    // Extract values from Alpha Vantage response
                    $currentPrice = isset($quote['05. price']) ? (float) $quote['05. price'] : 0;
                    $openPrice = isset($quote['02. open']) ? (float) $quote['02. open'] : 0;
                    $highPrice = isset($quote['03. high']) ? (float) $quote['03. high'] : 0;
                    $lowPrice = isset($quote['04. low']) ? (float) $quote['04. low'] : 0;
                    $volume = isset($quote['06. volume']) ? (int) $quote['06. volume'] : 0;
                    $previousClose = isset($quote['08. previous close']) ? (float) $quote['08. previous close'] : 0;
                    
                    // Validate we got real data
                    if ($currentPrice > 0) {
                        Log::info("✅ SUCCESS! Fetched REAL data for {$stock->symbol}: \${$currentPrice}");
                        
                        return [
                            'current_price' => $currentPrice,
                            'opening_price' => $openPrice,
                            'high_price' => $highPrice,
                            'low_price' => $lowPrice,
                            'volume' => $volume,
                            'closing_price' => $previousClose,
                        ];
                    }
                }
                
                // Check for API error messages
                if (isset($data['Error Message'])) {
                    Log::error("API Error: " . $data['Error Message']);
                }
                
                // Check for API rate limit message
                if (isset($data['Note']) && str_contains($data['Note'], 'API rate limit')) {
                    Log::warning("Rate limit reached for {$stock->symbol}");
                }
            }
            
            Log::warning("Failed to fetch real data for {$stock->symbol}, using mock data");
            return null;
            
        } catch (\Exception $e) {
            Log::error("API connection error for {$stock->symbol}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Fallback to mock data when API fails
     */
    private function fallbackToMockData($stock)
    {
        Log::info("🔧 Using MOCK data for {$stock->symbol}");
        
        $mockData = $this->generateRealisticPrice($stock);
        
        // Save mock data to history
        StockPriceHistory::updateOrCreate(
            ['stock_id' => $stock->id, 'date' => now()->toDateString()],
            [
                'open_price' => $mockData['opening_price'],
                'high_price' => $mockData['high_price'],
                'low_price' => $mockData['low_price'],
                'close_price' => $mockData['current_price'],
                'volume' => $mockData['volume'],
                'adjusted_close' => $mockData['current_price'],
            ]
        );
        
        $oldPrice = (float) $stock->current_price;
        
        $stock->update([
            'current_price' => $mockData['current_price'],
            'high_price' => $mockData['high_price'],
            'low_price' => $mockData['low_price'],
            'volume' => $mockData['volume'],
            'last_updated' => now(),
        ]);
        
        $change = (float) $stock->current_price - $oldPrice;
        $changePercentage = $oldPrice > 0 ? ($change / $oldPrice) * 100 : 0;
        
        return response()->json([
            'success' => true,
            'data' => [
                'symbol' => $stock->symbol,
                'old_price' => $oldPrice,
                'new_price' => (float) $stock->current_price,
                'change' => round($change, 2),
                'change_percentage' => round($changePercentage, 2),
                'last_updated' => now()->toIso8601String(),
                'source' => 'MOCK_DATA_FALLBACK'
            ]
        ]);
    }

    /**
     * Generate realistic price fluctuations (Mock/Fallback data)
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
        $successCount = 0;
        $failCount = 0;
        $realDataCount = 0;
        $mockDataCount = 0;

        foreach ($stocks as $stock) {
            $response = $this->updateStockPrice($request, $stock);
            $resultData = $response->getData();
            
            $results[] = $resultData;
            
            if ($resultData->success) {
                $successCount++;
                if (isset($resultData->data->source)) {
                    if ($resultData->data->source === 'API_REAL_DATA') {
                        $realDataCount++;
                    } else {
                        $mockDataCount++;
                    }
                }
            } else {
                $failCount++;
            }
            
            // Sleep to respect Alpha Vantage rate limits (5 calls per minute on free tier)
            // 60 seconds / 5 calls = 12 seconds between calls
            sleep(12);
        }

        return response()->json([
            'total' => $stocks->count(),
            'success' => $successCount,
            'failed' => $failCount,
            'real_data_updates' => $realDataCount,
            'mock_data_updates' => $mockDataCount,
            'results' => $results
        ]);
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