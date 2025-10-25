@extends('layouts.app')

@section('title', 'Transaction History')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('wallet.index') }}" class="text-indigo-600 hover:text-indigo-800 mb-4 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Wallet
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Transaction History</h1>
        <p class="mt-2 text-gray-600">View all your wallet transactions</p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Filters</h2>
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Type</label>
                <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Types</option>
                    <option value="deposit" {{ request('type') === 'deposit' ? 'selected' : '' }}>Deposit</option>
                    <option value="withdrawal" {{ request('type') === 'withdrawal' ? 'selected' : '' }}>Withdrawal</option>
                    <option value="trade" {{ request('type') === 'trade' ? 'selected' : '' }}>Trade</option>
                    <option value="transfer" {{ request('type') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cryptocurrency</label>
                <select name="crypto" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Cryptocurrencies</option>
                    @foreach($cryptocurrencies ?? [] as $crypto)
                    <option value="{{ $crypto->id }}" {{ request('crypto') == $crypto->id ? 'selected' : '' }}>
                        {{ $crypto->name }} ({{ strtoupper($crypto->symbol) }})
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <i class="fas fa-filter mr-2"></i>Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Transactions</h2>
        </div>
        
        @if($transactions->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cryptocurrency</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($transactions as $transaction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 
                                    {{ $transaction->type === 'deposit' ? 'bg-green-100 text-green-600' : 
                                       ($transaction->type === 'withdrawal' ? 'bg-red-100 text-red-600' : 
                                        ($transaction->type === 'trade' ? 'bg-blue-100 text-blue-600' : 'bg-purple-100 text-purple-600')) }} 
                                    rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-{{ 
                                        $transaction->type === 'deposit' ? 'arrow-down' : 
                                        ($transaction->type === 'withdrawal' ? 'arrow-up' : 
                                         ($transaction->type === 'trade' ? 'exchange-alt' : 'transfer'))
                                    }}"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-900 capitalize">{{ $transaction->type }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xs font-bold mr-3">
                                    {{ strtoupper(substr($transaction->cryptocurrency->symbol ?? 'N/A', 0, 2)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $transaction->cryptocurrency->symbol ?? 'N/A' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $transaction->cryptocurrency->name ?? 'Unknown' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium {{ $transaction->type === 'deposit' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->type === 'deposit' ? '+' : '-' }}{{ number_format($transaction->amount, 8) }}
                            </div>
                            <div class="text-sm text-gray-500">
                                ≈ ${{ number_format($transaction->amount * ($transaction->cryptocurrency->current_price ?? 0), 2) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($transaction->fee ?? 0, 8) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    ($transaction->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div>{{ $transaction->created_at->format('M j, Y') }}</div>
                            <div>{{ $transaction->created_at->format('g:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="viewTransaction({{ $transaction->id }})" 
                                    class="text-indigo-600 hover:text-indigo-900">
                                <i class="fas fa-eye"></i> View
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $transactions->appends(request()->query())->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-history text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Transactions Found</h3>
            <p class="text-gray-500 mb-6">
                @if(request()->any())
                    No transactions match your current filters.
                @else
                    You haven't made any transactions yet.
                @endif
            </p>
            @if(request()->any())
            <a href="{{ route('wallet.transactions') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                Clear Filters
            </a>
            @endif
        </div>
        @endif
    </div>

    <!-- Summary Stats -->
    @if($transactions->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-arrow-down text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Deposits</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $transactions->where('type', 'deposit')->where('status', 'completed')->count() }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-arrow-up text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Withdrawals</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $transactions->where('type', 'withdrawal')->where('status', 'completed')->count() }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pending</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $transactions->where('status', 'pending')->count() }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exchange-alt text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Volume</p>
                    <p class="text-2xl font-bold text-gray-900">
                        ${{ number_format($transactions->where('status', 'completed')->sum(function($t) {
                            return $t->amount * ($t->cryptocurrency->current_price ?? 0);
                        }), 2) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Transaction Detail Modal -->
<div id="transactionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 1000;">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Transaction Details</h3>
            <div id="transactionDetails">
                <!-- Transaction details will be loaded here -->
            </div>
            <div class="mt-6">
                <button onclick="closeModal()" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function viewTransaction(transactionId) {
    // This would typically fetch transaction details via AJAX
    // For now, showing a placeholder
    document.getElementById('transactionDetails').innerHTML = `
        <div class="text-center">
            <i class="fas fa-spinner fa-spin text-2xl text-gray-400 mb-4"></i>
            <p class="text-gray-600">Loading transaction details...</p>
        </div>
    `;
    document.getElementById('transactionModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('transactionModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('transactionModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection