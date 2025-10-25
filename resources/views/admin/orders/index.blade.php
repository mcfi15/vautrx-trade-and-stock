@extends('admin.layouts.app')

@section('title', 'Orders Management')

@section('content')
<div class="mb-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
            <i class="fas fa-list"></i> Orders Management
        </h1>
        <div class="flex items-center space-x-2">
            <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm font-semibold">
                Total: {{ $orders->total() }}
            </span>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white shadow rounded-lg p-4 mb-6">
    <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Search -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Search User</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Email or name..." 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <!-- Status Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">All</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Partial</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <!-- Side Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Side</label>
            <select name="side" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">All</option>
                <option value="buy" {{ request('side') === 'buy' ? 'selected' : '' }}>Buy</option>
                <option value="sell" {{ request('side') === 'sell' ? 'selected' : '' }}>Sell</option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="flex items-end space-x-2">
            <button type="submit" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                <i class="fas fa-search"></i> Filter
            </button>
            <a href="{{ route('admin.orders.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="hidden lg:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pair</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Side</th>
                    <th class="hidden md:table-cell px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="hidden md:table-cell px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="hidden sm:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-4 text-sm font-mono text-gray-900">#{{ $order->id }}</td>
                        <td class="hidden lg:table-cell px-4 py-4 text-sm text-gray-900">{{ $order->user->name }}</td>
                        <td class="px-4 py-4 text-sm font-medium text-gray-900">
                            {{ $order->tradingPair->baseCurrency->symbol }}/{{ $order->tradingPair->quoteCurrency->symbol }}
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                {{ $order->side === 'buy' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ strtoupper($order->side) }}
                            </span>
                        </td>
                        <td class="hidden md:table-cell px-4 py-4 text-sm text-right font-mono">{{ number_format($order->amount, 4) }}</td>
                        <td class="hidden md:table-cell px-4 py-4 text-sm text-right font-mono">${{ number_format($order->price, 2) }}</td>
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                @if($order->status === 'completed') bg-green-100 text-green-800
                                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'partial') bg-blue-100 text-blue-800
                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="hidden sm:table-cell px-4 py-4 text-sm text-gray-500">
                            {{ $order->created_at->format('M d, H:i') }}
                        </td>
                        <td class="px-4 py-4 text-right text-sm font-medium space-x-2">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                <i class="fas fa-eye"></i><span class="hidden xl:inline ml-1">View</span>
                            </a>
                            @if(in_array($order->status, ['pending', 'partial']))
                                <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Cancel this order?')">
                                        <i class="fas fa-times"></i><span class="hidden xl:inline ml-1">Cancel</span>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-list text-4xl mb-2"></i>
                            <p>No orders found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection