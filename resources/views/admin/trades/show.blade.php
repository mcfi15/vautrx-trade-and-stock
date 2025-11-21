@extends('admin.layouts.app')

@section('title', 'Trade Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div class="flex items-center mb-4 sm:mb-0">
            <a href="{{ route('admin.trades.index') }}" class="mr-3 text-gray-400 hover:text-gray-600 transition duration-150">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Trade Details</h1>
                <p class="text-sm text-gray-600 mt-1">Trade #{{ $trade->trade_number }}</p>
            </div>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.trades.edit', $trade) }}" 
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                <i class="fas fa-edit mr-2"></i>Edit Trade
            </a>
            <a href="{{ route('admin.trades.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                <i class="fas fa-list mr-2"></i>All Trades
            </a>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-exchange-alt text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Trade Information</h2>
                        <p class="text-sm text-gray-600">Complete details of trade #{{ $trade->trade_number }}</p>
                    </div>
                </div>
                <div class="bg-white px-3 py-1 rounded-full border border-gray-200">
                    <span class="text-sm font-medium text-gray-700">{{ $trade->created_at->format('M j, Y g:i A') }}</span>
                </div>
            </div>
        </div>

        <!-- Trade Details Grid -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Trading Pair & Order -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider border-b pb-2">Trade Basics</h3>
                    
                    <div>
                        <label class="text-xs font-medium text-gray-400 uppercase">Trading Pair</label>
                        <div class="flex items-center mt-1">
                            <i class="fas fa-exchange-alt text-blue-500 mr-2 text-sm"></i>
                            <span class="text-lg font-semibold text-gray-900">{{ $trade->tradingPair->symbol ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-400 uppercase">Order ID</label>
                        <p class="text-sm font-mono text-gray-700 bg-gray-50 px-3 py-2 rounded-lg mt-1 border">
                            {{ $trade->order_id }}
                        </p>
                    </div>
                </div>

                <!-- Participants -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider border-b pb-2">Participants</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-gray-400 uppercase">Buyer</label>
                            <div class="flex items-center mt-1">
                                <i class="fas fa-user-circle text-green-500 mr-2"></i>
                                <span class="text-sm font-medium text-gray-900">{{ $trade->buyer->name ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-400 uppercase">Seller</label>
                            <div class="flex items-center mt-1">
                                <i class="fas fa-user-circle text-red-500 mr-2"></i>
                                <span class="text-sm font-medium text-gray-900">{{ $trade->seller->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Details -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider border-b pb-2">Financials</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-gray-400 uppercase">Price</label>
                            <p class="text-lg font-mono font-semibold text-gray-900 mt-1">
                                {{ number_format($trade->price, 8) }}
                            </p>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-400 uppercase">Quantity</label>
                            <p class="text-lg font-mono font-semibold text-gray-900 mt-1">
                                {{ number_format($trade->quantity, 8) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Amount -->
                <div class="md:col-span-2 lg:col-span-3">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-dollar-sign text-green-600"></i>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-green-600 uppercase">Total Amount</label>
                                    <p class="text-2xl font-mono font-bold text-green-800">
                                        {{ number_format($trade->total_amount, 8) }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-sm text-green-600">Final Settlement</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fee Structure -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider border-b pb-2">Fees</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center bg-gray-50 px-3 py-2 rounded-lg border">
                            <span class="text-sm font-medium text-gray-700">Buyer Fee</span>
                            <span class="text-sm font-mono font-semibold text-red-600">
                                {{ number_format($trade->buyer_fee, 8) }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center bg-gray-50 px-3 py-2 rounded-lg border">
                            <span class="text-sm font-medium text-gray-700">Seller Fee</span>
                            <span class="text-sm font-mono font-semibold text-red-600">
                                {{ number_format($trade->seller_fee, 8) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Blockchain Information -->
                <div class="space-y-4 md:col-span-2">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider border-b pb-2">Blockchain</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-gray-400 uppercase">Transaction Hash</label>
                            @if($trade->blockchain_tx_hash)
                                <div class="flex items-center mt-1">
                                    <i class="fas fa-link text-blue-500 mr-2 text-sm"></i>
                                    <code class="text-xs font-mono text-gray-700 bg-gray-100 px-2 py-1 rounded break-all">
                                        {{ $trade->blockchain_tx_hash }}
                                    </code>
                                </div>
                            @else
                                <p class="text-sm text-gray-500 italic mt-1">-</p>
                            @endif
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-400 uppercase">Status</label>
                            @if($trade->blockchain_status)
                                @php
                                    $statusColors = [
                                        'confirmed' => 'bg-green-100 text-green-800 border-green-200',
                                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'failed' => 'bg-red-100 text-red-800 border-red-200',
                                        'default' => 'bg-gray-100 text-gray-800 border-gray-200'
                                    ];
                                    $color = $statusColors[strtolower($trade->blockchain_status)] ?? $statusColors['default'];
                                @endphp
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $color }}">
                                        <i class="fas fa-circle mr-1 text-xs"></i>
                                        {{ $trade->blockchain_status }}
                                    </span>
                                </div>
                            @else
                                <p class="text-sm text-gray-500 italic mt-1">-</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row justify-between items-center mt-6 pt-6 border-t border-gray-200">
        <a href="{{ route('admin.trades.index') }}" 
           class="text-blue-600 hover:text-blue-800 font-medium transition duration-150 flex items-center mb-4 sm:mb-0">
            <i class="fas fa-arrow-left mr-2"></i>Back to All Trades
        </a>
        
        <div class="flex space-x-3">
            <a href="{{ route('admin.trades.edit', $trade) }}" 
               class="bg-white border border-yellow-500 text-yellow-600 hover:bg-yellow-50 px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                <i class="fas fa-edit mr-2"></i>Edit Trade
            </a>
            
            <form action="{{ route('admin.trades.destroy', $trade) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-white border border-red-500 text-red-600 hover:bg-red-50 px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center"
                        onclick="return confirm('Are you sure you want to delete this trade? This action cannot be undone.')">
                    <i class="fas fa-trash mr-2"></i>Delete Trade
                </button>
            </form>
        </div>
    </div>
</div>
@endsection