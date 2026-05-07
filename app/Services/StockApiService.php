<?php

// app/Services/StockApiService.php
namespace App\Services;

use App\Models\Stock;
use App\Events\StockPriceUpdated;
use Illuminate\Support\Facades\Http;

class StockApiService
{
    public function updateStockPrices()
    {
        $stocks = Stock::where('is_active', true)->get();

        foreach ($stocks as $stock) {
            // Example using Alpha Vantage (Note: Free tier has rate limits)
            $response = Http::get("https://query1.finance.yahoo.com/v8/finance/chart/{$stock->symbol}");

if ($response->successful()) {
    $result = $response->json();

    $quote = $result['chart']['result'][0]['meta'] ?? null;

    if ($quote && isset($quote['regularMarketPrice'])) {
        $stock->update([
            'current_price' => $quote['regularMarketPrice'],
            'opening_price' => $quote['regularMarketOpen'] ?? 0,
            'high_price'    => $quote['regularMarketDayHigh'] ?? 0,
            'low_price'     => $quote['regularMarketDayLow'] ?? 0,
            'volume'        => $quote['regularMarketVolume'] ?? 0,
            'last_updated'  => now(),
        ]);

        broadcast(new StockPriceUpdated($stock));
    }
}
        }
    }
}