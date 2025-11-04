<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Cryptocurrency;
use Illuminate\Support\Facades\Http;

class CoinGeckoService
{
    private $endpoint = 'https://api.coingecko.com/api/v3/coins/markets';

    public function updateAllPrices()
    {
        $coins = Cryptocurrency::where('is_active', 1)->pluck('coingecko_id')->toArray();

        if (empty($coins)) {
            return false;
        }

        $response = Http::withoutVerifying()->get('https://api.coingecko.com/api/v3/simple/price', [

            'ids' => implode(',', $coins),
            'vs_currencies' => 'usd',
            'include_24hr_change' => 'true',
            'include_market_cap' => 'true',
            'include_24hr_vol' => 'true'
        ]);

        $data = $response->json();

        foreach ($data as $id => $price) {
            Cryptocurrency::where('coingecko_id', $id)->update([
                'current_price' => $price['usd'] ?? 0,
                'price_change_24h' => $price['usd_24h_change'] ?? 0,
                'market_cap' => $price['usd_market_cap'] ?? 0,
                'volume_24h' => $price['usd_24h_vol'] ?? 0,
                'price_updated_at' => Carbon::now(),
            ]);
        }

        return true;
    }
}
