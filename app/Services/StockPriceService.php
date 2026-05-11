<?php
// app/Services/StockPriceService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Stock;

class StockPriceService
{
    protected $apiKey;
    protected $provider;
    
    public function __construct()
    {
        $this->provider = config('stockapi.provider', 'alphavantage');
        $this->apiKey = config('stockapi.api_key');
    }
    
    public function getRealTimePrice(Stock $stock): array
    {
        // Check cache first (respect API rate limits)
        $cacheKey = "stock_price_{$stock->symbol}";
        
        return Cache::remember($cacheKey, now()->addSeconds(15), function () use ($stock) {
            return $this->fetchFromProvider($stock);
        });
    }
    
    protected function fetchFromProvider(Stock $stock): array
    {
        switch ($this->provider) {
            case 'alphavantage':
                return $this->fetchFromAlphaVantage($stock);
            case 'yahoo':
                return $this->fetchFromYahoo($stock);
            default:
                return $this->getMockData($stock);
        }
    }
    
    protected function fetchFromAlphaVantage(Stock $stock): array
    {
        $response = Http::get('https://www.alphavantage.co/query', [
            'function' => 'GLOBAL_QUOTE',
            'symbol' => $stock->symbol,
            'apikey' => $this->apiKey,
        ]);
        
        $data = $response->json();
        
        if (isset($data['Global Quote'])) {
            $quote = $data['Global Quote'];
            return [
                'current_price' => (float) ($quote['05. price'] ?? 0),
                'opening_price' => (float) ($quote['02. open'] ?? 0),
                'high_price' => (float) ($quote['03. high'] ?? 0),
                'low_price' => (float) ($quote['04. low'] ?? 0),
                'volume' => (int) ($quote['06. volume'] ?? 0),
            ];
        }
        
        throw new \Exception('Invalid API response');
    }
}