<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trade;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    // List all trades
    public function index()
    {
        $trades = Trade::with(['buyer', 'seller', 'tradingPair'])->latest()->paginate(20);
        return view('admin.trades.index', compact('trades'));
    }

    // Show single trade details
    public function show(Trade $trade)
    {
        $trade->load(['buyer', 'seller', 'tradingPair', 'order']);
        return view('admin.trades.show', compact('trade'));
    }

    // Optional: Delete trade
    public function destroy(Trade $trade)
    {
        $trade->delete();
        return redirect()->route('admin.trades.index')
            ->with('success', 'Trade deleted successfully.');
    }

    // Optional: Manual create/edit if needed
    public function create()
    {
        return view('admin.trades.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'trading_pair_id' => 'required|exists:trading_pairs,id',
            'buyer_id' => 'required|exists:users,id',
            'seller_id' => 'required|exists:users,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
        ]);

        Trade::create([
            'order_id' => $request->order_id,
            'trading_pair_id' => $request->trading_pair_id,
            'buyer_id' => $request->buyer_id,
            'seller_id' => $request->seller_id,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'total_amount' => bcmul($request->price, $request->quantity, 8),
        ]);

        return redirect()->route('admin.trades.index')->with('success', 'Trade created successfully.');
    }

    public function edit(Trade $trade)
    {
        return view('admin.trades.edit', compact('trade'));
    }

    public function update(Request $request, Trade $trade)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
        ]);

        $trade->update([
            'price' => $request->price,
            'quantity' => $request->quantity,
            'total_amount' => bcmul($request->price, $request->quantity, 8),
        ]);

        return redirect()->route('admin.trades.index')->with('success', 'Trade updated successfully.');
    }
}
