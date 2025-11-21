@extends('admin.layouts.app')

@section('title', 'Trades')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4 sm:mb-0">All Trades</h1>
        <a href="{{ route('admin.trades.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
            <i class="fas fa-plus mr-2"></i>Add New Trade
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Table Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Responsive Table Container -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Trade #</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pair</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Buyer</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Seller</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($trades as $trade)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">{{ $trade->trade_number }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-exchange-alt mr-1 text-xs"></i>
                                {{ $trade->tradingPair->symbol ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i class="fas fa-user-circle text-green-500 mr-2"></i>
                                <span class="text-sm text-gray-700">{{ $trade->buyer->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i class="fas fa-user-circle text-red-500 mr-2"></i>
                                <span class="text-sm text-gray-700">{{ $trade->seller->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-mono text-gray-900">{{ number_format($trade->price, 8) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-mono text-gray-900">{{ number_format($trade->quantity, 8) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-mono font-semibold text-gray-900">{{ number_format($trade->total_amount, 8) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <!-- View Button -->
                                <a href="{{ route('admin.trades.show', $trade) }}" 
                                   class="text-blue-600 hover:text-blue-900 transition duration-150"
                                   title="View Trade">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <!-- Edit Button -->
                                <a href="{{ route('admin.trades.edit', $trade) }}" 
                                   class="text-yellow-600 hover:text-yellow-900 transition duration-150"
                                   title="Edit Trade">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <!-- Delete Form -->
                                <form action="{{ route('admin.trades.destroy', $trade) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 transition duration-150"
                                            onclick="return confirm('Are you sure you want to delete this trade?')"
                                            title="Delete Trade">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <i class="fas fa-exchange-alt text-4xl mb-3 text-gray-300"></i>
                                <p class="text-lg font-medium mb-2">No trades found</p>
                                <p class="text-sm">There are no trades in the system yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($trades->hasPages())
    <div class="mt-6 flex justify-center">
        <div class="bg-white px-4 py-3 rounded-lg border border-gray-200">
            {{ $trades->links() }}
        </div>
    </div>
    @endif
</div>

<!-- Custom Styles for Pagination -->
<style>
.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
}

.pagination li {
    margin: 0 2px;
}

.pagination li a,
.pagination li span {
    display: inline-block;
    padding: 8px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    text-decoration: none;
    color: #374151;
    font-size: 14px;
    transition: all 0.2s;
}

.pagination li a:hover {
    background-color: #f3f4f6;
    border-color: #d1d5db;
}

.pagination li.active span {
    background-color: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.pagination li.disabled span {
    color: #9ca3af;
    background-color: #f9fafb;
    border-color: #e5e7eb;
}
</style>
@endsection