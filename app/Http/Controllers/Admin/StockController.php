<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Stock;
use App\Models\StockPriceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class StockController extends Controller
{
    public function index()
    {
        // $stocks = Stock::all();
        return view('admin.stocks.index');
    }

    public function update($symbol)
    {
        // 1. Fetch Real-Time Data from Yahoo Finance
        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0'
        ])->get("https://query1.finance.yahoo.com/v8/finance/chart/{$symbol}?interval=1d&range=1d");

        if ($response->failed() || !isset($response['chart']['result'][0])) {
            return back()->with('error', 'Could not fetch data for ' . $symbol);
        }

        $result = $response['chart']['result'][0];
        $meta = $result['meta'];
        $quote = $result['indicators']['quote'][0];

        // 2. Find the Stock in your DB
        $stock = Stock::where('symbol', $symbol)->firstOrFail();

        // 3. Update the Stock Model with live data
        $stock->update([
            'current_price'  => $meta['regularMarketPrice'],
            'previous_close' => $meta['previousClose'] ?? $stock->closing_price,
            'opening_price'  => $quote['open'][0] ?? $meta['regularMarketPrice'],
            'high_price'     => $meta['regularMarketDayHigh'],
            'low_price'      => $meta['regularMarketDayLow'],
            'day_high'       => $meta['regularMarketDayHigh'],
            'day_low'        => $meta['regularMarketDayLow'],
            'volume'         => $quote['volume'][0] ?? 0,
            'last_updated'   => Carbon::now(),
            'last_sync_at'   => Carbon::now(),
        ]);

        // 4. Update/Create Today's Price History
        StockPriceHistory::updateOrCreate(
            [
                'stock_id' => $stock->id,
                'date'     => Carbon::today(),
            ],
            [
                'open_price'     => $quote['open'][0] ?? $meta['regularMarketPrice'],
                'high_price'     => $meta['regularMarketDayHigh'],
                'low_price'      => $meta['regularMarketDayLow'],
                'close_price'    => $meta['regularMarketPrice'],
                'volume'         => $quote['volume'][0] ?? 0,
                'adjusted_close' => $meta['regularMarketPrice'],
            ]
        );

        return back()->with('success', "Updated {$symbol} successfully!");
    }
}