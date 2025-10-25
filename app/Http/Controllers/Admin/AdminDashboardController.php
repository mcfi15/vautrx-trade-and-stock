<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cryptocurrency;
use App\Models\TradingPair;
use App\Models\Order;
use App\Models\Trade;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_cryptocurrencies' => Cryptocurrency::count(),
            'active_pairs' => TradingPair::where('is_active', true)->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_trades' => Trade::count(),
            'pending_withdrawals' => Withdrawal::where('status', 'pending')->count(),
            'total_volume_24h' => Trade::where('created_at', '>=', now()->subDay())->sum('total_amount'),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentTrades = Trade::with(['buyer', 'seller', 'tradingPair'])->latest()->take(10)->get();
        $pendingWithdrawals = Withdrawal::with(['user', 'cryptocurrency'])
            ->where('status', 'pending')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentTrades', 'pendingWithdrawals'));
    }

    public function users()
    {
        $users = User::withCount(['orders', 'trades'])
            ->latest()
            ->paginate(20);
        
        return view('admin.users.index', compact('users'));
    }

    public function toggleUserStatus($userId)
    {
        $user = User::findOrFail($userId);
        $user->is_active = !$user->is_active;
        $user->save();

        return redirect()->back()->with('success', 'User status updated');
    }
}
