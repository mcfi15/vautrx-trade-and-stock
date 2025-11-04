<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cryptocurrency;
use App\Services\CoinGeckoService;

class CryptoApiController extends Controller
{
    public function list()
    {
        return response()->json([
            'success' => true,
            'data' => Cryptocurrency::all()
        ]);
    }

    public function batchUpdate(CoinGeckoService $service)
    {
        $cryptos = Cryptocurrency::pluck('coingecko_id')->toArray();
        $prices = $service->getPrices($cryptos);

        foreach (Cryptocurrency::all() as $crypto) {
            if (!isset($prices[$crypto->coingecko_id])) continue;
            $crypto->current_price = $prices[$crypto->coingecko_id]['usd'];
            $crypto->price_updated_at = now();
            $crypto->save();
        }
        
        return response()->json(['success' => true, 'message' => 'Batch sync done']);
    }

    public function status()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'service_running' => true,
                'tracked_cryptocurrencies' => Cryptocurrency::where('enable_realtime', 1)->count(),
                'total_cryptocurrencies' => Cryptocurrency::count(),
                'live_connections' => rand(1, 5), // Mock
            ]
        ]);
    }
}

