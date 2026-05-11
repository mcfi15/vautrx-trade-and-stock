<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\StockPriceHistory;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class StockUpdateService
{
    public function updateStockData($symbol)
    {
        // 1. Fetch Real-Time Data from Yahoo Finance (No API Key Required)
        // This endpoint returns accurate, live market data.
        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
        ])->get("https://query1.finance.yahoo.com/v8/finance/chart/{$symbol}?interval=1d&range=1d");

        if ($response->failed() || !isset($response['chart']['result'][0])) {
            return false;
        }

        $data = $response['chart']['result'][0]['meta'];
        $indicators = $response['chart']['result'][0]['indicators']['quote'][0];

        // 2. Find or Create the Stock in your DB
        $stock = Stock::where('symbol', $symbol)->first();

        if (!$stock) return false;

        // 3. Update the Stock Model
        $stock->update([
            'current_price'  => $data['regularMarketPrice'],
            'previous_close' => $data['previousClose'],
            'opening_price'  => $indicators['open'][0] ?? $data['regularMarketPrice'],
            'high_price'     => $data['regularMarketDayHigh'],
            'low_price'      => $data['regularMarketDayLow'],
            'day_high'       => $data['regularMarketDayHigh'],
            'day_low'        => $data['regularMarketDayLow'],
            'volume'         => $indicators['volume'][0] ?? 0,
            'last_updated'   => Carbon::now(),
            'last_sync_at'   => Carbon::now(),
        ]);

        // 4. Save to Price History (for charts/tracking)
        StockPriceHistory::updateOrCreate(
            [
                'stock_id' => $stock->id,
                'date'     => Carbon::today(),
            ],
            [
                'open_price'     => $indicators['open'][0] ?? $data['regularMarketPrice'],
                'high_price'     => $data['regularMarketDayHigh'],
                'low_price'      => $data['regularMarketDayLow'],
                'close_price'    => $data['regularMarketPrice'],
                'volume'         => $indicators['volume'][0] ?? 0,
                'adjusted_close' => $data['regularMarketPrice'],
            ]
        );

        return $stock;
    }
}