@extends('layouts.app')

@section('title', 'Wallet Overview')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Wallet Overview</h1>
        <p class="mt-2 text-gray-600">Manage your cryptocurrency holdings and view detailed portfolio information</p>
    </div>

    <!-- Total Portfolio Value -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="text-center">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Total Portfolio Value</h2>
            <div class="text-4xl font-bold text-green-600 mb-2" id="totalValue">
                ${{ number_format($totalValue ?? 0, 2) }} USDT
            </div>
            <p class="text-sm text-gray-500">≈ {{ $totalValue ?? 0 }} USDT</p>
        </div>
    </div>

    <!-- Detailed Wallet Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Your Holdings</h2>
        </div>
        
        @if($wallets->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">USDT</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($wallets as $wallet)
                    <tr class="hover:bg-gray-50">
                        <!-- Coin -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg crypto-icon">
                                    {{ strtoupper(substr($wallet->cryptocurrency->symbol ?? 'N/A', 0, 2)) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $wallet->cryptocurrency->name ?? 'Unknown' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ strtoupper($wallet->cryptocurrency->symbol ?? 'N/A') }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Balance -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ number_format($wallet->balance, 8) }}
                            </div>
                            <div class="text-sm text-gray-500">
                                Available: {{ number_format($wallet->available_balance ?? $wallet->balance, 8) }}
                            </div>
                        </td>
                        
                        <!-- Order (Locked in orders) -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ number_format($wallet->locked_balance ?? 0, 8) }}
                            </div>
                            <div class="text-sm text-gray-500">
                                In orders
                            </div>
                        </td>
                        
                        <!-- Total -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ number_format($wallet->balance + ($wallet->locked_balance ?? 0), 8) }}
                            </div>
                            <div class="text-sm text-gray-500">
                                Total holdings
                            </div>
                        </td>
                        
                        <!-- Price -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                ${{ number_format($wallet->cryptocurrency->current_price ?? 0, 2) }}
                            </div>
                            <div class="text-sm text-gray-500">
                                Current price
                            </div>
                        </td>
                        
                        <!-- USDT Amount -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-green-600">
                                ${{ number_format(($wallet->balance * ($wallet->cryptocurrency->current_price ?? 0)), 2) }}
                            </div>
                            <div class="text-sm text-gray-500">
                                USDT value
                            </div>
                        </td>
                        
                        <!-- Options -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex space-x-2">
                                <a href="{{ route('wallet.deposit', $wallet->cryptocurrency_id) }}" 
                                   class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Deposit
                                </a>
                                @if($wallet->balance > 0.00000001)
                                <a href="{{ route('wallet.withdraw', $wallet->cryptocurrency_id) }}" 
                                   class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    Withdraw
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-wallet text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Wallets Found</h3>
            <p class="text-gray-500 mb-6">Start by depositing cryptocurrency to your wallet</p>
            <a href="{{ route('wallet.deposit', 1) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                Make Your First Deposit
            </a>
        </div>
        @endif
    </div>

    <!-- Quick Add Funds Section -->
    @if($cryptocurrencies->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Deposit</h2>
        <p class="text-sm text-gray-600 mb-4">Click on any cryptocurrency to generate a deposit address</p>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($cryptocurrencies as $crypto)
            <div class="text-center p-4 border border-gray-200 rounded-lg hover:border-indigo-300 cursor-pointer transition-colors hover:shadow-md"
                 onclick="location.href='{{ route('wallet.deposit', $crypto->id) }}'">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg mx-auto mb-3 crypto-icon">
                    {{ strtoupper(substr($crypto->symbol, 0, 2)) }}
                </div>
                <h3 class="font-medium text-gray-900 text-sm">{{ $crypto->name }}</h3>
                <p class="text-xs text-gray-500">{{ strtoupper($crypto->symbol) }}</p>
                <div class="mt-2">
                    <div class="text-xs text-green-600" data-price-{{ $crypto->id }}>
                        ${{ number_format($crypto->current_price ?? 0, 2) }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Recent Transactions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Recent Transactions</h2>
            <a href="{{ route('wallet.transactions') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                View All Transactions
            </a>
        </div>
        
        <div class="space-y-4">
            @forelse($recentTransactions ?? [] as $transaction)
            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <div class="flex items-center">
                    <div class="w-10 h-10 {{ $transaction->type === 'deposit' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} rounded-full flex items-center justify-center">
                        <i class="fas fa-{{ $transaction->type === 'deposit' ? 'arrow-down' : 'arrow-up' }}"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-medium text-gray-900 capitalize">{{ $transaction->type }}</h4>
                        <p class="text-sm text-gray-500">{{ $transaction->cryptocurrency->symbol ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-400">{{ $transaction->created_at->format('M j, Y g:i A') }}</p>
                    </div> 
                </div>
                <div class="text-right">
                    <div class="font-medium {{ $transaction->type === 'deposit' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $transaction->type === 'deposit' ? '+' : '-' }}{{ number_format($transaction->amount, 8) }}
                    </div>
                    <div class="text-sm text-gray-500">
                        ${{ number_format($transaction->amount * ($transaction->cryptocurrency->current_price ?? 0), 2) }} USDT
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <i class="fas fa-history text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">No recent transactions</p>
                <p class="text-sm text-gray-400 mt-1">Your transaction history will appear here</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
// Calculate total portfolio value and update display
function updatePortfolioSummary() {
    const wallets = @json($wallets ?? []);
    let total = 0;
    const summaryData = [];
    
    wallets.forEach(wallet => {
        const price = wallet.cryptocurrency?.current_price || 0;
        const balance = parseFloat(wallet.balance) || 0;
        const lockedBalance = parseFloat(wallet.locked_balance || 0);
        const totalAmount = balance + lockedBalance;
        const usdtValue = totalAmount * price;
        
        total += usdtValue;
        
        summaryData.push({
            symbol: wallet.cryptocurrency?.symbol || 'N/A',
            amount: totalAmount,
            usdtValue: usdtValue
        });
    });
    
    document.getElementById('totalValue').textContent = `$${total.toFixed(2)} USDT`;
    
    // Log summary for debugging
    console.log('Portfolio Summary:', summaryData);
    console.log('Total Value:', `$${total.toFixed(2)} USDT`);
}

// Update individual row USDT values
function updateRowValues() {
    @foreach($wallets as $wallet)
    const {{ $wallet->cryptocurrency_id }}Price = {{ $wallet->cryptocurrency->current_price ?? 0 }};
    const {{ $wallet->cryptocurrency_id }}Balance = {{ $wallet->balance }};
    const {{ $wallet->cryptocurrency_id }}Locked = {{ $wallet->locked_balance ?? 0 }};
    const {{ $wallet->cryptocurrency_id }}Total = {{ $wallet->balance + ($wallet->locked_balance ?? 0) }};
    const {{ $wallet->cryptocurrency_id }}USDT = {{ ($wallet->balance + ($wallet->locked_balance ?? 0)) * ($wallet->cryptocurrency->current_price ?? 0) }};
    @endforeach
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    updatePortfolioSummary();
    updateRowValues();
});

// Update prices every 30 seconds
setInterval(updatePortfolioSummary, 30000);
</script>

<style>
.crypto-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.crypto-card {
    transition: all 0.3s ease;
}

.crypto-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

table tr:hover {
    background-color: #f9fafb;
}

/* Responsive table */
@media (max-width: 768px) {
    .table-container {
        overflow-x: auto;
    }
    
    table {
        min-width: 800px;
    }
}
</style>
@endsection
