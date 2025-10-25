@extends('admin.layouts.app')

@section('title', 'Order Details #' . $order->id)

@section('content')
<div class="mb-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
            <i class="fas fa-file-alt"></i> Order #{{ $order->id }}
        </h1>
        <a href="{{ route('admin.orders.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Order Summary -->
    <div class="lg:col-span-1">
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-info-circle"></i> Order Summary
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label class="text-sm text-gray-500">Order ID</label>
                    <div class="font-mono font-medium">#{{ $order->id }}</div>
                </div>

                <div>
                    <label class="text-sm text-gray-500">Trading Pair</label>
                    <div class="font-medium">{{ $order->tradingPair->baseCurrency->symbol }}/{{ $order->tradingPair->quoteCurrency->symbol }}</div>
                </div>

                <div>
                    <label class="text-sm text-gray-500">Side</label>
                    <div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                            {{ $order->side === 'buy' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ strtoupper($order->side) }}
                        </span>
                    </div>
                </div>

                <div>
                    <label class="text-sm text-gray-500">Type</label>
                    <div class="font-medium uppercase">{{ $order->type }}</div>
                </div>

                <div>
                    <label class="text-sm text-gray-500">Status</label>
                    <div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                            @if($order->status === 'completed') bg-green-100 text-green-800
                            @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'partial') bg-blue-100 text-blue-800
                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>

                <div>
                    <label class="text-sm text-gray-500">Amount</label>
                    <div class="font-mono font-medium">{{ number_format($order->amount, 8) }} {{ $order->tradingPair->baseCurrency->symbol }}</div>
                </div>

                <div>
                    <label class="text-sm text-gray-500">Filled Amount</label>
                    <div class="font-mono font-medium">{{ number_format($order->filled_amount, 8) }} {{ $order->tradingPair->baseCurrency->symbol }}</div>
                </div>

                <div>
                    <label class="text-sm text-gray-500">Price</label>
                    <div class="font-mono font-medium">${{ number_format($order->price, 2) }}</div>
                </div>

                <div>
                    <label class="text-sm text-gray-500">Total Value</label>
                    <div class="font-mono font-medium text-lg">${{ number_format($order->amount * $order->price, 2) }}</div>
                </div>

                <div>
                    <label class="text-sm text-gray-500">Created At</label>
                    <div class="text-sm">{{ $order->created_at->format('M d, Y H:i:s') }}</div>
                </div>
            </div>

            @if(in_array($order->status, ['pending', 'partial']))
                <hr class="my-6">
                <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition" onclick="return confirm('Are you sure you want to cancel this order?')">
                        <i class="fas fa-times"></i> Cancel Order
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- User & Trades Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- User Information -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-user"></i> User Information
            </h3>
            <div class="flex items-center">
                @if($order->user->avatar)
                    <img src="{{ $order->user->avatar }}" alt="{{ $order->user->name }}" class="h-12 w-12 rounded-full mr-4">
                @else
                    <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center mr-4">
                        <span class="text-indigo-600 font-semibold text-lg">{{ substr($order->user->name, 0, 1) }}</span>
                    </div>
                @endif
                <div>
                    <div class="font-medium text-gray-900">{{ $order->user->name }}</div>
                    <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                    <a href="{{ route('admin.users.show', $order->user->id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                        <i class="fas fa-external-link-alt"></i> View User Profile
                    </a>
                </div>
            </div>
        </div>

        <!-- Trades -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-exchange-alt"></i> Trades ({{ $order->trades->count() }})
                </h3>
            </div>
            @if($order->trades->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trade ID</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="hidden sm:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->trades as $trade)
                                <tr>
                                    <td class="px-4 py-3 text-sm font-mono">#{{ $trade->id }}</td>
                                    <td class="px-4 py-3 text-sm font-mono text-right">{{ number_format($trade->amount, 8) }}</td>
                                    <td class="px-4 py-3 text-sm font-mono text-right">${{ number_format($trade->price, 2) }}</td>
                                    <td class="px-4 py-3 text-sm font-mono text-right font-semibold">${{ number_format($trade->amount * $trade->price, 2) }}</td>
                                    <td class="hidden sm:table-cell px-4 py-3 text-sm text-gray-500">{{ $trade->created_at->format('M d, H:i:s') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-sm font-semibold text-right">Total Executed:</td>
                                <td class="px-4 py-3 text-sm font-mono font-bold text-right">${{ number_format($order->trades->sum(function($trade) { return $trade->amount * $trade->price; }), 2) }}</td>
                                <td class="hidden sm:table-cell"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-exchange-alt text-4xl mb-2"></i>
                    <p>No trades executed yet</p>
                </div>
            @endif
        </div>

        <!-- Order Progress -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-chart-line"></i> Order Progress
            </h3>
            <div class="mb-2">
                <div class="flex justify-between text-sm mb-1">
                    <span>Filled: {{ number_format(($order->filled_amount / $order->amount) * 100, 2) }}%</span>
                    <span class="font-mono">{{ number_format($order->filled_amount, 4) }} / {{ number_format($order->amount, 4) }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="bg-indigo-600 h-4 rounded-full transition-all" style="width: {{ ($order->filled_amount / $order->amount) * 100 }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection