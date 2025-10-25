@extends('admin.layouts.app')

@section('title', 'Cryptocurrency Details - ' . $cryptocurrency->name)

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-coins text-yellow-500"></i>
            {{ $cryptocurrency->name }} ({{ $cryptocurrency->symbol }})
        </h1>
        <div class="mt-3 sm:mt-0 flex gap-2">
            <a href="{{ route('admin.cryptocurrencies.edit', $cryptocurrency->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('admin.cryptocurrencies.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Basic Info -->
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-blue-500"></i> Basic Information
            </h2>
            <div class="flex flex-col md:flex-row gap-4 items-center">
                @if($cryptocurrency->logo_url)
                    <img src="{{ $cryptocurrency->logo_url }}" alt="{{ $cryptocurrency->symbol }}" class="w-24 h-24 object-contain rounded">
                @else
                    <div class="w-24 h-24 bg-gray-100 flex items-center justify-center rounded">
                        <i class="fas fa-coins text-4xl text-gray-400"></i>
                    </div>
                @endif
                <div class="flex-1">
                    <dl class="divide-y divide-gray-200 text-sm">
                        <div class="py-1 flex justify-between">
                            <dt class="text-gray-500">Symbol:</dt>
                            <dd class="font-semibold">{{ $cryptocurrency->symbol }}</dd>
                        </div>
                        <div class="py-1 flex justify-between">
                            <dt class="text-gray-500">Name:</dt>
                            <dd>{{ $cryptocurrency->name }}</dd>
                        </div>
                        <div class="py-1 flex justify-between">
                            <dt class="text-gray-500">CoinGecko ID:</dt>
                            <dd><code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $cryptocurrency->coingecko_id }}</code></dd>
                        </div>
                        @if($cryptocurrency->binance_symbol)
                        <div class="py-1 flex justify-between">
                            <dt class="text-gray-500">Binance Symbol:</dt>
                            <dd><code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $cryptocurrency->binance_symbol }}</code></dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        <!-- Blockchain Info -->
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <i class="fas fa-network-wired text-indigo-500"></i> Blockchain Information
            </h2>
            <dl class="divide-y divide-gray-200 text-sm">
                <div class="py-1 flex justify-between">
                    <dt class="text-gray-500">Blockchain:</dt>
                    <dd><span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-700 font-semibold">{{ strtoupper($cryptocurrency->blockchain) }}</span></dd>
                </div>
                <div class="py-1 flex justify-between">
                    <dt class="text-gray-500">Contract Address:</dt>
                    <dd>
                        @if($cryptocurrency->contract_address)
                            <code class="bg-gray-100 px-2 py-1 rounded text-xs break-all">{{ $cryptocurrency->contract_address }}</code>
                        @else
                            <span class="text-gray-400 italic">Native Token</span>
                        @endif
                    </dd>
                </div>
                <div class="py-1 flex justify-between">
                    <dt class="text-gray-500">Decimals:</dt>
                    <dd>{{ $cryptocurrency->decimals }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Market Data and Status -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <!-- Market Data -->
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-line text-green-500"></i> Market Data
            </h2>
            <div class="text-center mb-4">
                <h3 class="text-2xl font-bold text-green-600">${{ number_format($cryptocurrency->current_price, 8) }}</h3>
                <p class="text-gray-500 text-sm">Current Price</p>
            </div>
            <dl class="divide-y divide-gray-200 text-sm">
                <div class="py-1 flex justify-between">
                    <dt class="text-gray-500">24h Change:</dt>
                    <dd>
                        @if($cryptocurrency->price_change_24h >= 0)
                            <span class="text-green-600 font-semibold">
                                <i class="fas fa-arrow-up"></i> {{ number_format($cryptocurrency->price_change_24h, 2) }}%
                            </span>
                        @else
                            <span class="text-red-600 font-semibold">
                                <i class="fas fa-arrow-down"></i> {{ number_format($cryptocurrency->price_change_24h, 2) }}%
                            </span>
                        @endif
                    </dd>
                </div>
                <div class="py-1 flex justify-between">
                    <dt class="text-gray-500">24h Volume:</dt>
                    <dd>${{ number_format($cryptocurrency->volume_24h, 0) }}</dd>
                </div>
                <div class="py-1 flex justify-between">
                    <dt class="text-gray-500">Market Cap:</dt>
                    <dd>${{ number_format($cryptocurrency->market_cap, 0) }}</dd>
                </div>
                <div class="py-1 flex justify-between">
                    <dt class="text-gray-500">Last Updated:</dt>
                    <dd>
                        @if($cryptocurrency->price_updated_at)
                            {{ $cryptocurrency->price_updated_at->format('Y-m-d H:i:s') }}
                            <br>
                            <span class="text-xs text-gray-400">({{ $cryptocurrency->price_updated_at->diffForHumans() }})</span>
                        @else
                            <span class="text-gray-400">Never</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Status -->
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <i class="fas fa-toggle-on text-amber-500"></i> Status & Settings
            </h2>
            <dl class="divide-y divide-gray-200 text-sm">
                <div class="py-1 flex justify-between">
                    <dt class="text-gray-500">Active Status:</dt>
                    <dd>
                        @if($cryptocurrency->is_active)
                            <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded font-semibold"><i class="fas fa-check-circle"></i> Active</span>
                        @else
                            <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded font-semibold"><i class="fas fa-times-circle"></i> Inactive</span>
                        @endif
                    </dd>
                </div>
                <div class="py-1 flex justify-between">
                    <dt class="text-gray-500">Tradable:</dt>
                    <dd>
                        @if($cryptocurrency->is_tradable)
                            <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded font-semibold"><i class="fas fa-check"></i> Yes</span>
                        @else
                            <span class="px-2 py-1 text-xs bg-gray-200 text-gray-600 rounded font-semibold"><i class="fas fa-times"></i> No</span>
                        @endif
                    </dd>
                </div>
                <div class="py-1 flex justify-between">
                    <dt class="text-gray-500">Real-time Tracking:</dt>
                    <dd>
                        @if($cryptocurrency->enable_realtime)
                            <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded font-semibold"><i class="fas fa-broadcast-tower"></i> Enabled</span>
                        @else
                            <span class="px-2 py-1 text-xs bg-gray-200 text-gray-600 rounded font-semibold"><i class="fas fa-pause"></i> Disabled</span>
                        @endif
                    </dd>
                </div>
                <div class="py-1 flex justify-between">
                    <dt class="text-gray-500">Created At:</dt>
                    <dd>{{ $cryptocurrency->created_at->format('Y-m-d H:i:s') }}</dd>
                </div>
                <div class="py-1 flex justify-between">
                    <dt class="text-gray-500">Updated At:</dt>
                    <dd>{{ $cryptocurrency->updated_at->format('Y-m-d H:i:s') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Platform Statistics -->
    <div class="mt-6 bg-white shadow rounded-xl p-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <i class="fas fa-chart-bar text-purple-500"></i> Platform Statistics
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-blue-50 border-l-4 border-blue-400 rounded-lg p-4 text-center">
                <p class="text-xs text-blue-700 uppercase font-semibold">Total Wallets</p>
                <p class="text-lg font-bold text-gray-800">{{ $cryptocurrency->wallets()->count() }}</p>
            </div>
            <div class="bg-green-50 border-l-4 border-green-400 rounded-lg p-4 text-center">
                <p class="text-xs text-green-700 uppercase font-semibold">Active Orders</p>
                <p class="text-lg font-bold text-gray-800">
                    {{ $cryptocurrency->tradingPairsBase()->withCount(['orders' => fn($q) => $q->whereIn('status', ['pending', 'partial'])])->get()->sum('orders_count') }}
                </p>
            </div>
            <div class="bg-sky-50 border-l-4 border-sky-400 rounded-lg p-4 text-center">
                <p class="text-xs text-sky-700 uppercase font-semibold">Total Trades</p>
                <p class="text-lg font-bold text-gray-800">
                    {{ $cryptocurrency->tradingPairsBase()->withCount('trades')->get()->sum('trades_count') }}
                </p>
            </div>
            <div class="bg-amber-50 border-l-4 border-amber-400 rounded-lg p-4 text-center">
                <p class="text-xs text-amber-700 uppercase font-semibold">Trading Pairs</p>
                <p class="text-lg font-bold text-gray-800">
                    {{ $cryptocurrency->tradingPairsBase()->count() + $cryptocurrency->tradingPairsQuote()->count() }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
