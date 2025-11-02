<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CoinGeckoService
{
    private $baseUrl;
    private $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.coingecko.base_url', 'https://api.coingecko.com/api/v3');
        $this->apiKey = config('services.coingecko.api_key');
    }

    /**
     * Get current price for a cryptocurrency
     */
    public function getPrice($coingeckoId, $vsCurrency = 'usd')
    {
        try {
            $cacheKey = "coingecko_price_{$coingeckoId}_{$vsCurrency}";
            
            return Cache::remember($cacheKey, 60, function () use ($coingeckoId, $vsCurrency) {
                $response = Http::get("{$this->baseUrl}/simple/price", [
                    'ids' => $coingeckoId,
                    'vs_currencies' => $vsCurrency,
                    'include_24hr_change' => true,
                    'include_market_cap' => true,
                    'include_24hr_vol' => true,
                ]);

                if ($response->successful()) {
                    return $response->json()[$coingeckoId] ?? null;
                }

                Log::error('CoinGecko API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return null;
            });
        } catch (\Exception $e) {
            Log::error('CoinGecko Service Error', [
                'message' => $e->getMessage(),
                'coin' => $coingeckoId
            ]);
            return null;
        }
    }

    /**
     * Get detailed market data for multiple cryptocurrencies
     */
    public function getMarketData($coingeckoIds, $vsCurrency = 'usd')
    {
        try {
            $ids = is_array($coingeckoIds) ? implode(',', $coingeckoIds) : $coingeckoIds;
            
            $response = Http::get("{$this->baseUrl}/coins/markets", [
                'vs_currency' => $vsCurrency,
                'ids' => $ids,
                'order' => 'market_cap_desc',
                'sparkline' => false,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return [];
        } catch (\Exception $e) {
            Log::error('CoinGecko Market Data Error', ['message' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Search for cryptocurrency by name or symbol
     */
    public function searchCoin($query)
    {
        try {
            $response = Http::get("{$this->baseUrl}/search", [
                'query' => $query
            ]);

            if ($response->successful()) {
                return $response->json()['coins'] ?? [];
            }

            return [];
        } catch (\Exception $e) {
            Log::error('CoinGecko Search Error', ['message' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Get coin details
     */
    public function getCoinDetails($coingeckoId)
    {
        try {
            $cacheKey = "coingecko_details_{$coingeckoId}";
            
            return Cache::remember($cacheKey, 3600, function () use ($coingeckoId) {
                $response = Http::get("{$this->baseUrl}/coins/{$coingeckoId}", [
                    'localization' => false,
                    'tickers' => false,
                    'community_data' => false,
                    'developer_data' => false,
                ]);

                if ($response->successful()) {
                    return $response->json();
                }

                return null;
            });
        } catch (\Exception $e) {
            Log::error('CoinGecko Details Error', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get historical price data
     */
    public function getHistoricalData($coingeckoId, $days = 7, $vsCurrency = 'usd')
    {
        try {
            $response = Http::get("{$this->baseUrl}/coins/{$coingeckoId}/market_chart", [
                'vs_currency' => $vsCurrency,
                'days' => $days,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('CoinGecko Historical Data Error', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get list of supported coins
     */
    public function getSupportedCoins()
    {
        try {
            $cacheKey = 'coingecko_supported_coins';
            
            return Cache::remember($cacheKey, 86400, function () {
                $response = Http::get("{$this->baseUrl}/coins/list");

                if ($response->successful()) {
                    return $response->json();
                }

                return [];
            });
        } catch (\Exception $e) {
            Log::error('CoinGecko Supported Coins Error', ['message' => $e->getMessage()]);
            return [];
        }
    }
}
