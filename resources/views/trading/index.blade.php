@extends('layouts.app')

@section('title', 'Trading - Select Trading Pair')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Trading</h1>
        <p class="text-gray-600 mt-2">Select a trading pair to start trading</p>
    </div>

    @if($tradingPairs->isEmpty())
        <!-- No Trading Pairs Available -->
        <div class="text-center py-16">
            <div class="max-w-md mx-auto">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No Trading Pairs Available</h3>
                <p class="mt-2 text-sm text-gray-500">
                    There are currently no active trading pairs available. Please check back later or contact support.
                </p>
                <div class="mt-6">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Trading Pairs Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($tradingPairs as $pair)
                <div class="bg-white rounded-lg shadow hover:shadow-md transition-shadow duration-200 overflow-hidden">
                    <div class="p-6">
                        <!-- Trading Pair Header -->
                        <div class="flex items-center mb-4">
                            @if($pair->baseCurrency->logo_url)
                                <img src="{{ $pair->baseCurrency->logo_url }}" class="h-8 w-8 rounded-full mr-3" alt="{{ $pair->baseCurrency->symbol }}">
                            @endif
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $pair->symbol }}</h3>
                                <p class="text-sm text-gray-500">{{ $pair->baseCurrency->name }} / {{ $pair->quoteCurrency->name }}</p>
                            </div>
                        </div>

                        <!-- Price Information -->
                        <div class="mb-4">
                            <div class="text-2xl font-bold text-gray-900">
                                ${{ number_format($pair->getCurrentPrice(), 2) }}
                            </div>
                            <div class="text-sm {{ $pair->baseCurrency->price_change_24h >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($pair->baseCurrency->price_change_24h, 2) }}% (24h)
                            </div>
                        </div>

                        <!-- Trading Information -->
                        <div class="text-xs text-gray-500 mb-4 space-y-1">
                            <div class="flex justify-between">
                                <span>24h Volume:</span>
                                <span>{{ number_format($pair->get24hVolume(), 2) }} {{ $pair->quoteCurrency->symbol }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Min Trade:</span>
                                <span>{{ $pair->min_trade_amount }} {{ $pair->baseCurrency->symbol }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Max Trade:</span>
                                <span>{{ $pair->max_trade_amount }} {{ $pair->baseCurrency->symbol }}</span>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <a href="{{ route('trading.show', $pair->id) }}" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors duration-200">
                            Trade {{ $pair->baseCurrency->symbol }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Additional Info -->
        <div class="mt-12 bg-blue-50 rounded-lg p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Trading Information</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>All trading pairs are subject to market conditions. Please ensure you understand the risks before trading. You must verify your email to place orders and make withdrawals.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh prices every 30 seconds
    setInterval(function() {
        window.location.reload();
    }, 30000);
});
</script>
@endsection