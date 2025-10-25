@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">
        <i class="fas fa-crown text-yellow-500"></i> Admin Dashboard
    </h1>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Users</p>
                    <p class="text-2xl font-bold">{{ $stats['total_users'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-coins text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Cryptocurrencies</p>
                    <p class="text-2xl font-bold">{{ $stats['total_cryptocurrencies'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-exchange-alt text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Trading Pairs</p>
                    <p class="text-2xl font-bold">{{ $stats['active_pairs'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">24h Volume</p>
                    <p class="text-2xl font-bold">${{ number_format($stats['total_volume_24h'], 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Users -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Recent Users</h2>
                <a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:text-indigo-800">View All</a>
            </div>
            <div class="space-y-3">
                @foreach($recentUsers as $user)
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-medium">{{ $user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Pending Withdrawals -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Pending Withdrawals ({{ $stats['pending_withdrawals'] }})</h2>
            <div class="space-y-3">
                @forelse($pendingWithdrawals as $withdrawal)
                <div class="flex justify-between items-center p-2 bg-yellow-50 rounded">
                    <div>
                        <p class="font-medium">{{ $withdrawal->user->name }}</p>
                        <p class="text-sm text-gray-500">
                            {{ rtrim(rtrim(number_format($withdrawal->amount, 8), '0'), '.') }} {{ $withdrawal->cryptocurrency->symbol }}
                        </p>
                    </div>
                    <button class="text-indigo-600 hover:text-indigo-800 text-sm">Review</button>
                </div>
                @empty
                <p class="text-gray-500 text-center">No pending withdrawals</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Quick Actions</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.cryptocurrencies.create') }}" class="p-4 border-2 border-dashed border-gray-300 rounded-lg text-center hover:border-indigo-500 hover:bg-indigo-50 transition">
                <i class="fas fa-plus-circle text-3xl text-indigo-600 mb-2"></i>
                <p class="text-sm font-medium">Add Cryptocurrency</p>
            </a>
            <a href="{{ route('admin.trading-pairs.create') }}" class="p-4 border-2 border-dashed border-gray-300 rounded-lg text-center hover:border-green-500 hover:bg-green-50 transition">
                <i class="fas fa-link text-3xl text-green-600 mb-2"></i>
                <p class="text-sm font-medium">Add Trading Pair</p>
            </a>
            <form action="{{ route('admin.cryptocurrencies.update-prices') }}" method="POST" class="contents">
                @csrf
                <button type="submit" class="p-4 border-2 border-dashed border-gray-300 rounded-lg text-center hover:border-blue-500 hover:bg-blue-50 transition">
                    <i class="fas fa-sync-alt text-3xl text-blue-600 mb-2"></i>
                    <p class="text-sm font-medium">Update Prices</p>
                </button>
            </form>
            <a href="{{ route('admin.users.index') }}" class="p-4 border-2 border-dashed border-gray-300 rounded-lg text-center hover:border-purple-500 hover:bg-purple-50 transition">
                <i class="fas fa-users-cog text-3xl text-purple-600 mb-2"></i>
                <p class="text-sm font-medium">Manage Users</p>
            </a>
        </div>
    </div>
</div>
@endsection
