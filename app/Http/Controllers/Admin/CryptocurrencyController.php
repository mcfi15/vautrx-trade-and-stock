<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cryptocurrency;
use App\Services\CoinGeckoService;
use App\Services\BinanceService;
use Illuminate\Http\Request;

class CryptocurrencyController extends Controller
{
    public function index()
    {
        $cryptocurrencies = Cryptocurrency::paginate(10);
        return view('admin.cryptocurrencies.index', compact('cryptocurrencies'));
    }

    public function toggleRealtime(Request $request, $id)
    {
        $crypto = Cryptocurrency::findOrFail($id);
        $crypto->enable_realtime = $request->enable_realtime;
        $crypto->save();

        return response()->json(['success' => true, 'message' => 'Real-time tracking updated']);
    }

    public function toggleStatus(Request $request, $id)
    {
        $crypto = Cryptocurrency::findOrFail($id);
        $crypto->is_active = $request->is_active;
        $crypto->save();

        return response()->json(['success' => true, 'message' => 'Status updated']);
    }

    public function syncFromBinance(BinanceService $binance)
    {
        $markets = $binance->getTopMarkets();

        foreach ($markets as $m) {
            $symbol = str_replace('USDT', '', $m['symbol']);
            Cryptocurrency::updateOrCreate(
                ['symbol' => $symbol],
                [
                    'name' => $symbol,
                    'binance_symbol' => $m['symbol'],
                    'current_price' => $m['lastPrice'],
                    'volume_24h' => $m['quoteVolume'],
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'Synced from Binance']);
    }


    public function fetchPrices()
    {
        $cryptos = Cryptocurrency::where('is_active', 1)
            ->select(
                'id',
                'symbol',
                'name',
                'logo_url',
                'current_price',
                'price_change_24h',
                'market_cap',
                'volume_24h',
                'price_updated_at'
            )
            ->orderBy('market_cap', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $cryptos
        ]);
    }

    public function serviceStatus()
    {
        return response()->json([
            'success' => true,
            'service' => 'Crypto Price Engine',
            'running' => true,
            'connections' => 0, // you can replace with WebSocket sessions count later
            'tracked' => Cryptocurrency::where('enable_realtime', 1)->count(),
            'total' => Cryptocurrency::count(),
            'last_check' => now()->toDateTimeString()
        ]);
    }

    public function syncPrices()
{
    $result = app(CoinGeckoService::class)->updateAllPrices();

    return response()->json([
        'success' => $result,
        'message' => $result ? 'Prices updated' : 'No coins found or update failed'
    ]);
}

//     public function syncPrices()
// {
//     try {
//         // Run CoinGecko price update
//         app(CoinGeckoService::class)->updateAllPrices();

//         return response()->json([
//             'success' => true,
//             'message' => 'Prices updated successfully'
//         ], 200);

//     } catch (\Exception $e) {

//         return response()->json([
//             'success' => false,
//             'message' => 'Failed to update prices',
//             'error' => $e->getMessage()
//         ], 500);
//     }
// }


    public function apiList()
{
    return response()->json([
        'success' => true,
        'data' => Cryptocurrency::orderBy('id')->get()
    ]);
}

public function apiStatus()
{
    return response()->json([
        'success' => true,
        'data' => [
            'service_running' => true,
            'live_connections' => 1,
            'tracked_cryptocurrencies' => Cryptocurrency::where('enable_realtime', 1)->count(),
            'total_cryptocurrencies' => Cryptocurrency::count(),
        ]
    ]);
}

public function batchUpdatePrices()
{
    // Run your CoinGecko service here
    app(CoinGeckoService::class)->updateAllPrices();

    return response()->json([
        'success' => true,
        'message' => 'Prices synced successfully'
    ]);
}


/**
 * Receive live price update from external WebSocket script.
 * Expects JSON payload: { api_key: 'SECRET', symbol: 'BTC', price: 44000.12, volume: 12345, timestamp: 165... }
 */
public function webhookUpdatePrice(Request $request)
{
    // Simple API key protection — set APP_WS_API_KEY in .env (choose a strong secret)
    $expected = config('app.ws_api_key') ?? env('WS_API_KEY', null);
    $apiKey = $request->input('api_key') ?? $request->header('X-API-KEY');

    if (!$expected || $apiKey !== $expected) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $symbol = strtoupper($request->input('symbol'));
    $price = $request->input('price');
    $volume = $request->input('volume') ?? null;
    $ts = $request->input('timestamp') ? \Carbon\Carbon::createFromTimestampMs($request->input('timestamp')) : now();

    // If you store binance_symbol like "BTCUSDT" you may want to match by that instead.
    $crypto = Cryptocurrency::where('symbol', $symbol)->first();

    if (!$crypto) {
        // try matching by binance_symbol (e.g. BTCUSDT)
        $crypto = Cryptocurrency::where('binance_symbol', $symbol.'USDT')->first();
    }

    if (!$crypto) {
        return response()->json(['success' => false, 'message' => 'Crypto not found'], 404);
    }

    $crypto->update([
        'current_price' => $price ?? $crypto->current_price,
        'volume_24h' => $volume ?? $crypto->volume_24h,
        'price_updated_at' => $ts,
    ]);

    return response()->json(['success' => true, 'message' => 'Price updated']);
}


    

}

