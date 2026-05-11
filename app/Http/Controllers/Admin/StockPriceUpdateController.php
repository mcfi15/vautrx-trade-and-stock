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
            
            if (!$priceData || !isset($priceData['current_price']) || $priceData['current_price'] <= 0) {
                throw new \Exception('Invalid data from API');
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

            $stock->update([
                'current_price' => $priceData['current_price'],
                'high_price' => $priceData['high_price'] ?? $priceData['current_price'],
                'low_price' => $priceData['low_price'] ?? $priceData['current_price'],
                'volume' => $priceData['volume'] ?? $stock->volume,
                'last_updated' => now(),
                'previous_close' => $oldPrice,
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
                    'source' => 'API_REAL_DATA'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Stock Error ({$stock->symbol}): " . $e->getMessage());
            return $this->fallbackToMockData($stock);
        }
    }
    
    /**
     * Get the path to CA certificate bundle
     * Works on most Linux servers (Ubuntu, CentOS, AWS, etc.)
     */
    private function getCABundlePath()
    {
        // Common CA bundle paths on various Linux distributions
        $caPaths = [
            '/etc/ssl/certs/ca-certificates.crt',    // Debian/Ubuntu
            '/etc/pki/tls/certs/ca-bundle.crt',      // RHEL/CentOS 6
            '/etc/pki/ca-trust/extracted/pem/tls-ca-bundle.pem', // CentOS 7+
            '/etc/ssl/ca-bundle.pem',                 // Some RHEL
            '/usr/local/share/certs/ca-root-nss.crt', // FreeBSD
        ];
        
        foreach ($caPaths as $path) {
            if (file_exists($path) && is_readable($path)) {
                Log::info('Using CA bundle: ' . $path);
                return $path;
            }
        }
        
        // Fallback: Download a fresh bundle to storage if none found
        $fallbackPath = storage_path('certs/cacert.pem');
        if (!file_exists($fallbackPath)) {
            $this->downloadCACertBundle($fallbackPath);
        }
        
        return file_exists($fallbackPath) ? $fallbackPath : null;
    }
    
    /**
     * Download a fresh CA certificate bundle
     */
    private function downloadCACertBundle($path)
    {
        try {
            $directory = dirname($path);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            
            $cacertUrl = 'https://curl.se/ca/cacert.pem';
            $cacertContent = file_get_contents($cacertUrl);
            
            if ($cacertContent) {
                file_put_contents($path, $cacertContent);
                Log::info('Downloaded CA bundle to: ' . $path);
            }
        } catch (\Exception $e) {
            Log::error('Failed to download CA bundle: ' . $e->getMessage());
        }
    }
    
    /**
     * Fetch REAL stock data from Alpha Vantage API
     * PRODUCTION SAFE - uses proper SSL verification
     */
    private function fetchRealTimeStockData($stock)
    {
        $apiKey = "JL8NB7AX0PP1OKVF";
        $caBundlePath = $this->getCABundlePath();
        
        if (!$caBundlePath) {
            Log::error('No CA bundle found for SSL verification');
            return null;
        }
        
        try {
            $url = "https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol={$stock->symbol}&apikey={$apiKey}";
            
            Log::info('Fetching real data for: ' . $stock->symbol);
            
            // PRODUCTION SAFE: Uses proper CA bundle for SSL verification
            $response = Http::timeout(30)->withOptions([
                'verify' => $caBundlePath,  // Use system CA bundle instead of disabling verification
            ])->get($url);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['Global Quote']) && !empty($data['Global Quote'])) {
                    $quote = $data['Global Quote'];
                    $currentPrice = isset($quote['05. price']) ? (float) $quote['05. price'] : 0;
                    
                    if ($currentPrice > 0) {
                        Log::info("✅ REAL data for {$stock->symbol}: \${$currentPrice}");
                        
                        return [
                            'current_price' => $currentPrice,
                            'opening_price' => isset($quote['02. open']) ? (float) $quote['02. open'] : $currentPrice,
                            'high_price' => isset($quote['03. high']) ? (float) $quote['03. high'] : $currentPrice,
                            'low_price' => isset($quote['04. low']) ? (float) $quote['04. low'] : $currentPrice,
                            'volume' => isset($quote['06. volume']) ? (int) $quote['06. volume'] : 0,
                        ];
                    }
                }
                
                if (isset($data['Note']) && str_contains($data['Note'], 'API rate limit')) {
                    Log::warning("Rate limit reached for {$stock->symbol}");
                }
            }
            
            return null;
            
        } catch (\Exception $e) {
            Log::error("API Error for {$stock->symbol}: " . $e->getMessage());
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
        $basePrice = $stock->current_price > 0 ? $stock->current_price : 100;
        $changePercent = (mt_rand(-300, 300) / 100);
        $newPrice = max(0.01, $basePrice * (1 + $changePercent / 100));
        $highPrice = max($basePrice, $newPrice) + abs($changePercent / 2);
        $lowPrice = min($basePrice, $newPrice) - abs($changePercent / 2);
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
            $response = $this->updateStockPrice($request, $stock);
            $results[] = $response->getData();
            sleep(12);
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