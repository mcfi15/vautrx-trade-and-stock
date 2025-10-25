<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TradingPair;
use App\Models\Cryptocurrency;
use Illuminate\Http\Request;

class TradingPairController extends Controller
{
    public function __construct()
    {
        // Middleware applied at route level (auth:admin)
    }

    public function index()
    {
        $tradingPairs = TradingPair::with(['baseCurrency', 'quoteCurrency'])
            ->latest()
            ->paginate(20);
        
        return view('admin.trading-pairs.index', compact('tradingPairs'));
    }

    public function create()
    {
        $cryptocurrencies = Cryptocurrency::active()->get();
        return view('admin.trading-pairs.create', compact('cryptocurrencies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'base_currency_id' => 'required|exists:cryptocurrencies,id',
            'quote_currency_id' => 'required|exists:cryptocurrencies,id|different:base_currency_id',
            'min_trade_amount' => 'required|numeric|min:0',
            'max_trade_amount' => 'required|numeric|gt:min_trade_amount',
            'trading_fee' => 'required|numeric|min:0|max:1',
        ]);

        $baseCurrency = Cryptocurrency::find($request->base_currency_id);
        $quoteCurrency = Cryptocurrency::find($request->quote_currency_id);

        $symbol = $baseCurrency->symbol . '/' . $quoteCurrency->symbol;

        TradingPair::create([
            'base_currency_id' => $request->base_currency_id,
            'quote_currency_id' => $request->quote_currency_id,
            'symbol' => $symbol,
            'min_trade_amount' => $request->min_trade_amount,
            'max_trade_amount' => $request->max_trade_amount,
            'price_precision' => 0.00000001,
            'quantity_precision' => 0.00000001,
            'trading_fee' => $request->trading_fee,
            'is_active' => true,
        ]);

        return redirect()->route('admin.trading-pairs.index')
            ->with('success', 'Trading pair created successfully');
    }

    public function edit($id)
    {
        $tradingPair = TradingPair::with(['baseCurrency', 'quoteCurrency'])->findOrFail($id);
        $cryptocurrencies = Cryptocurrency::active()->get();
        
        return view('admin.trading-pairs.edit', compact('tradingPair', 'cryptocurrencies'));
    }

    public function update(Request $request, $id)
    {
        $tradingPair = TradingPair::findOrFail($id);

        $request->validate([
            'min_trade_amount' => 'required|numeric|min:0',
            'max_trade_amount' => 'required|numeric|gt:min_trade_amount',
            'trading_fee' => 'required|numeric|min:0|max:1',
            'is_active' => 'boolean',
        ]);

        $tradingPair->update($request->only([
            'min_trade_amount',
            'max_trade_amount',
            'trading_fee',
            'is_active',
        ]));

        return redirect()->route('admin.trading-pairs.index')
            ->with('success', 'Trading pair updated successfully');
    }

    public function destroy($id)
    {
        $tradingPair = TradingPair::findOrFail($id);
        $tradingPair->delete();

        return redirect()->route('admin.trading-pairs.index')
            ->with('success', 'Trading pair deleted successfully');
    }

    public function toggleStatus($id)
    {
        $tradingPair = TradingPair::findOrFail($id);
        $tradingPair->is_active = !$tradingPair->is_active;
        $tradingPair->save();

        return redirect()->back()->with('success', 'Trading pair status updated');
    }
}
