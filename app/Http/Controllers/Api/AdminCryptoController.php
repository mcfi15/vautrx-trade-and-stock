<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cryptocurrency;
use App\Services\CoinGeckoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AdminCryptoController extends Controller
{
    private $coinGeckoService;

    public function __construct(CoinGeckoService $coinGeckoService)
    {
        // Note: Admin authentication is handled by admin routes (web middleware)
        // API endpoints are only called from the admin panel which is already protected
        $this->coinGeckoService = $coinGeckoService;
    }

    /**
     * Get all cryptocurrencies for admin panel with real-time status
     */
    public function index()
    {
        try {
            $cryptos = Cryptocurrency::orderBy('market_cap', 'desc')->get();

            $enhancedCryptos = $cryptos->map(function ($crypto) {
                // Check for cached real-time data
                $cached = Cache::get("crypto_price_{$crypto->id}");
                
                return [
                    'id' => $crypto->id,
                    'symbol' => $crypto->symbol,
                    'name' => $crypto->name,
                    'current_price' => $crypto->current_price,
                    'price_change_24h' => $crypto->price_change_24h,
                    'volume_24h' => $crypto->volume_24h,
                    'market_cap' => $crypto->market_cap,
                    'high_24h' => $crypto->high_24h,
                    'low_24h' => $crypto->low_24h,
                    'enable_realtime' => $crypto->enable_realtime,
                    'is_active' => $crypto->is_active,
                    'is_tradable' => $crypto->is_tradable,
                    'price_updated_at' => $crypto->price_updated_at,
                    'logo_url' => $crypto->logo_url,
                    'is_live' => $cached ? true : false,
                    'cached_price' => $cached ? $cached['price'] : null,
                    'last_cache_update' => $cached ? $cached['updated_at'] : null,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $enhancedCryptos,
                'timestamp' => now()->toIso8601String(),
            ]);

        } catch (\Exception $e) {
            Log::error('Admin Crypto Index Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cryptocurrency data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Force update prices for a specific cryptocurrency
     */
    public function updatePrice($id)
    {
        try {
            $crypto = Cryptocurrency::findOrFail($id);
            
            // Get fresh data from CoinGecko
            $priceData = $this->coinGeckoService->getPrice($crypto->coingecko_id);
            
            if ($priceData) {
                $crypto->update([
                    'current_price' => $priceData['usd'] ?? $crypto->current_price,
                    'price_change_24h' => $priceData['usd_24h_change'] ?? $crypto->price_change_24h,
                    'market_cap' => $priceData['usd_market_cap'] ?? $crypto->market_cap,
                    'volume_24h' => $priceData['usd_24h_vol'] ?? $crypto->volume_24h,
                    'price_updated_at' => now(),
                ]);

                // Update cache for real-time display
                Cache::put("crypto_price_{$crypto->id}", [
                    'price' => $priceData['usd'],
                    'change' => $priceData['usd_24h_change'],
                    'volume' => $priceData['usd_24h_vol'],
                    'updated_at' => now(),
                ], 300); // Cache for 5 minutes

                return response()->json([
                    'success' => true,
                    'message' => "Updated {$crypto->symbol} successfully",
                    'data' => [
                        'current_price' => $crypto->current_price,
                        'price_change_24h' => $crypto->price_change_24h,
                        'volume_24h' => $crypto->volume_24h,
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch price data from CoinGecko',
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Update Price Error', [
                'crypto_id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update price: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Batch update all cryptocurrency prices
     */
    public function batchUpdate()
    {
        try {
            $cryptos = Cryptocurrency::where('is_active', true)->get();
            $updated = 0;
            $failed = 0;
            $errors = [];

            foreach ($cryptos as $crypto) {
                try {
                    $priceData = $this->coinGeckoService->getPrice($crypto->coingecko_id);
                    
                    if ($priceData) {
                        $crypto->update([
                            'current_price' => $priceData['usd'] ?? $crypto->current_price,
                            'price_change_24h' => $priceData['usd_24h_change'] ?? $crypto->price_change_24h,
                            'market_cap' => $priceData['usd_market_cap'] ?? $crypto->market_cap,
                            'volume_24h' => $priceData['usd_24h_vol'] ?? $crypto->volume_24h,
                            'price_updated_at' => now(),
                        ]);

                        // Update cache
                        Cache::put("crypto_price_{$crypto->id}", [
                            'price' => $priceData['usd'],
                            'change' => $priceData['usd_24h_change'],
                            'volume' => $priceData['usd_24h_vol'],
                            'updated_at' => now(),
                        ], 300);

                        $updated++;
                    } else {
                        $failed++;
                        $errors[] = "No price data for {$crypto->symbol}";
                    }
                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = "Failed to update {$crypto->symbol}: " . $e->getMessage();
                    Log::warning("Failed to update {$crypto->symbol}", ['error' => $e->getMessage()]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Batch update completed: {$updated} updated, {$failed} failed",
                'data' => [
                    'updated' => $updated,
                    'failed' => $failed,
                    'total' => $cryptos->count(),
                    'errors' => $errors,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Batch Update Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Batch update failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get service status for admin panel
     */
    public function status()
    {
        try {
            $totalCryptos = Cryptocurrency::count();
            $activeCryptos = Cryptocurrency::where('is_active', true)->count();
            $trackedCryptos = Cryptocurrency::where('is_active', true)
                ->where('enable_realtime', true)
                ->count();
            
            $liveConnections = 0;
            $cryptos = Cryptocurrency::where('enable_realtime', true)->get();
            
            foreach ($cryptos as $crypto) {
                if (Cache::has("crypto_price_{$crypto->id}")) {
                    $liveConnections++;
                }
            }

            // Check if price update service is running
            $lastUpdate = Cache::get('last_price_update');
            $serviceRunning = $lastUpdate && $lastUpdate > now()->subMinutes(10);

            return response()->json([
                'success' => true,
                'data' => [
                    'total_cryptocurrencies' => $totalCryptos,
                    'active_cryptocurrencies' => $activeCryptos,
                    'tracked_cryptocurrencies' => $trackedCryptos,
                    'live_connections' => $liveConnections,
                    'service_running' => $serviceRunning,
                    'last_update' => $lastUpdate ? $lastUpdate->toIso8601String() : null,
                    'timestamp' => now()->toIso8601String(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Service Status Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get service status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}