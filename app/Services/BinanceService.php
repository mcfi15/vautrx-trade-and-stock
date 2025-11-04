<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BinanceService
{
    public function getTopMarkets($limit = 50)
    {
        $response = Http::get('https://api.binance.com/api/v3/ticker/24hr');

        return collect($response->json())
            ->sortByDesc('quoteVolume')
            ->take($limit)
            ->values()
            ->toArray();
    }
}
