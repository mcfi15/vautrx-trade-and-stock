<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Stock;
use App\Models\StockPriceHistory;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class StockPriceTicker extends Component
{
    public function fetchLatestPrices()
{
    $stocks = Stock::where('is_active', true)->get();

    foreach ($stocks as $stock) {
        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36'
        ])->get("https://query1.finance.yahoo.com/v8/finance/chart/{$stock->symbol}?interval=1m&range=1d");

        if ($response->successful()) {
            $data = $response->json();
            $price = $data['chart']['result'][0]['meta']['regularMarketPrice'] ?? null;

            if ($price) {
                $stock->current_price = $price;
                $stock->last_sync_at = now(); // Change this field to see the timestamp update
                $stock->save();
            }
        }
    }
}

    public function render()
    {
        return view('livewire.stock-price-ticker', [
            'stocks' => Stock::all()
        ]);
    }
}