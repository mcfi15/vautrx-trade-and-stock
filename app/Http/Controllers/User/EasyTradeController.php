<?php

namespace App\Http\Controllers\User;

use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Cryptocurrency;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EasyTradeController extends Controller
{
    /**
     * Display the Easy Trade page.
     */
    public function index()
{
    $cryptos = Cryptocurrency::where('is_active', true)->get();
    $user = Auth::user();

    $balances = [];
    if ($user) {
        foreach ($cryptos as $crypto) {
            $balances[$crypto->symbol] = $user->getWallet($crypto->id)?->available_balance ?? 0;
        }
    }

    return view('easytrade.index', compact('cryptos', 'balances', 'user'));
}


    /**
     * Handle Buy or Sell trade action.
     */
public function doTrade(Request $request)
{
    if (!auth()->check()) {
        return response()->json([
            'status' => 0,
            'info' => 'Please login before trading.'
        ]);
    }

    $request->validate([
        'coin' => 'required|string',
        'amount' => 'required|numeric|min:0.0001',
        'type' => 'required|in:1,2' // 1 = buy, 2 = sell
    ]);

    $user = auth()->user();

    // Get selected crypto
    $crypto = Cryptocurrency::where('symbol', strtoupper($request->coin))->first();
    if (!$crypto) {
        return response()->json(['status' => 0, 'info' => 'Crypto not found.']);
    }

    // Always fetch USDT wallet for buy/sell
    $usdt = Cryptocurrency::where('symbol', 'USDT')->first();
    if (!$usdt) {
        return response()->json(['status' => 0, 'info' => 'USDT not found in system.']);
    }

    $cryptoWallet = $user->getOrCreateWallet($crypto->id);
    $usdtWallet   = $user->getOrCreateWallet($usdt->id);

    $amount = $request->amount;
    $price  = $crypto->current_price;

    if ($request->type == 1) {
        // ---------------------- BUY ----------------------
        $totalCost = $amount; // amount = USDT spent
        $cryptoToAdd = $amount / $price;

        if ($usdtWallet->balance < $totalCost) {
            return response()->json([
                'status' => 0,
                'info' => "Insufficient USDT balance."
            ]);
        }

        // Deduct USDT
        $usdtWallet->balance -= $totalCost;
        $usdtWallet->save();

        // Add crypto
        $cryptoWallet->balance += $cryptoToAdd;
        $cryptoWallet->save();

        return response()->json([
            'status' => 1,
            'info' => "Successfully bought {$cryptoToAdd} {$crypto->symbol}."
        ]);

    } else {
        // ---------------------- SELL ----------------------
        if ($cryptoWallet->balance < $amount) {
            return response()->json([
                'status' => 0,
                'info' => "Insufficient {$crypto->symbol} balance."
            ]);
        }

        $usdtToAdd = $amount * $price;

        // Deduct crypto
        $cryptoWallet->balance -= $amount;
        $cryptoWallet->save();

        // Add USDT
        $usdtWallet->balance += $usdtToAdd;
        $usdtWallet->save();

        return response()->json([
            'status' => 1,
            'info' => "Successfully sold {$amount} {$crypto->symbol} for {$usdtToAdd} USDT."
        ]);
    }
}



}
