<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cryptocurrency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CryptoPriceController extends Controller
{
    /**
     * Get real-time prices for all active cryptocurrencies
     */
    public function index()
    {
        $cryptos = Cryptocurrency::where('is_active', true)
            ->where('enable_realtime', true)
            ->select(['id', 'symbol', 'name', 'current_price', 'price_change_24h', 'volume_24h', 'high_24h', 'low_24h', 'price_updated_at'])
            ->get();

        // Enhance with cached real-time data if available
        $cryptos->transform(function ($crypto) {
            $cached = Cache::get("crypto_price_{$crypto->id}");
            
            if ($cached) {
                $crypto->current_price = $cached['price'];
                $crypto->price_change_24h = $cached['change'];
                $crypto->volume_24h = $cached['volume'];
                $crypto->price_updated_at = $cached['updated_at'];
                $crypto->is_live = true;
            } else {
                $crypto->is_live = false;
            }

            return $crypto;
        });

        return response()->json([
            'success' => true,
            'data' => $cryptos,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Get price for a specific cryptocurrency
     */
    public function show($id)
    {
        $crypto = Cryptocurrency::findOrFail($id);
        
        // Check cache for latest price
        $cached = Cache::get("crypto_price_{$crypto->id}");
        
        if ($cached) {
            $crypto->current_price = $cached['price'];
            $crypto->price_change_24h = $cached['change'];
            $crypto->volume_24h = $cached['volume'];
            $crypto->is_live = true;
        } else {
            $crypto->is_live = false;
        }

        return response()->json([
            'success' => true,
            'data' => $crypto,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Get WebSocket connection status
     */
    public function status()
    {
        $totalCryptos = Cryptocurrency::where('is_active', true)->count();
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

        return response()->json([
            'success' => true,
            'data' => [
                'total_cryptocurrencies' => $totalCryptos,
                'tracked_cryptocurrencies' => $trackedCryptos,
                'live_connections' => $liveConnections,
                'service_running' => $liveConnections > 0,
                'last_check' => now()->toIso8601String(),
            ]
        ]);
    }
}
