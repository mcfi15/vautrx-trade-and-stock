@extends('admin.layouts.app')

@section('title', 'Stock Details')
@section('page-title', 'Stock Details - ' . $stock->symbol)

@section('content')
<div class="flex flex-wrap m-5 gap-2">
    <a href="{{ route('admin.stocks.index') }}"
       class="inline-flex items-center px-3 py-1.5 text-sm border border-gray-600 rounded-md text-black-300 hover:bg-gray-700">
        <i class="fas fa-arrow-left mr-2"></i> Back to Stocks
    </a>
    <a href="{{ route('admin.stocks.edit', $stock) }}"
       class="inline-flex items-center px-3 py-1.5 text-sm bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
        <i class="fas fa-edit mr-2"></i> Edit
    </a>
    <button type="button"
        onclick="toggleStatus({{ $stock->id }}, '{{ $stock->is_active ? 'deactivate' : 'activate' }}')"
        class="inline-flex items-center px-3 py-1.5 text-sm rounded-md text-white 
               {{ $stock->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
        <i class="fas fa-{{ $stock->is_active ? 'ban' : 'check' }} mr-2"></i>
        {{ $stock->is_active ? 'Deactivate' : 'Activate' }}
    </button>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Stock Info -->
    <div class="bg-gray-900 border border-gray-800 rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4 flex items-center text-gray-100">
            <i class="fas fa-info-circle mr-2 text-blue-400"></i> Stock Information
        </h2>

        <div class="space-y-3 text-gray-300">
            <div class="flex justify-between">
                <span class="font-medium">Symbol:</span>
                <span class="bg-blue-700 text-white px-2 py-0.5 rounded">{{ $stock->symbol }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-medium">Company:</span>
                <span>{{ $stock->name }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-medium">Current Price:</span>
                <span class="text-blue-400 text-lg">${{ number_format($stock->current_price, 2) }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-medium">Price Change:</span>
                @if($stock->change !== 0)
                    <span class="{{ $stock->change >= 0 ? 'text-green-400' : 'text-red-400' }}">
                        {{ $stock->change >= 0 ? '+' : '' }}${{ number_format($stock->change, 2) }}
                        ({{ $stock->change >= 0 ? '+' : '' }}{{ number_format($stock->change_percentage, 2) }}%)
                    </span>
                @else
                    <span class="text-gray-500">No change</span>
                @endif
            </div>

            @if($stock->sector)
            <div class="flex justify-between">
                <span class="font-medium">Sector:</span>
                <span class="bg-gray-700 text-gray-200 px-2 py-0.5 rounded">{{ $stock->sector }}</span>
            </div>
            @endif

            @if($stock->exchange)
            <div class="flex justify-between">
                <span class="font-medium">Exchange:</span>
                <span class="bg-blue-600 text-white px-2 py-0.5 rounded">{{ $stock->exchange }}</span>
            </div>
            @endif

            <div class="flex justify-between">
                <span class="font-medium">Status:</span>
                <span class="px-2 py-0.5 rounded {{ $stock->is_active ? 'bg-green-700 text-white' : 'bg-red-700 text-white' }}">
                    {{ $stock->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <div class="flex justify-between">
                <span class="font-medium">Last Updated:</span>
                <span>{{ $stock->last_updated ? $stock->last_updated->format('M j, Y H:i:s') : 'Never' }}</span>
            </div>
        </div>
    </div>

    <!-- Price Info -->
    <div class="bg-gray-900 border border-gray-800 rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4 flex items-center text-gray-100">
            <i class="fas fa-chart-line mr-2 text-blue-400"></i> Price Information
        </h2>

        <div class="grid grid-cols-2 gap-4 text-center text-gray-300">
            <div class="border border-gray-700 rounded p-3">
                <div class="text-xl font-semibold">${{ number_format($stock->opening_price ?: 0, 2) }}</div>
                <div class="text-gray-500 text-sm">Opening Price</div>
            </div>
            <div class="border border-gray-700 rounded p-3">
                <div class="text-xl font-semibold">${{ number_format($stock->closing_price ?: 0, 2) }}</div>
                <div class="text-gray-500 text-sm">Closing Price</div>
            </div>
            <div class="border border-gray-700 rounded p-3">
                <div class="text-xl font-semibold text-green-400">${{ number_format($stock->high_price ?: 0, 2) }}</div>
                <div class="text-gray-500 text-sm">Day High</div>
            </div>
            <div class="border border-gray-700 rounded p-3">
                <div class="text-xl font-semibold text-red-400">${{ number_format($stock->low_price ?: 0, 2) }}</div>
                <div class="text-gray-500 text-sm">Day Low</div>
            </div>
            <div class="border border-gray-700 rounded p-3">
                <div class="text-xl font-semibold">{{ number_format($stock->volume) }}</div>
                <div class="text-gray-500 text-sm">Volume</div>
            </div>
            <div class="border border-gray-700 rounded p-3">
                <div class="text-xl font-semibold">
                    @if($stock->market_cap)
                        ${{ number_format($stock->market_cap / 1000000, 0) }}M
                    @else
                        N/A
                    @endif
                </div>
                <div class="text-gray-500 text-sm">Market Cap</div>
            </div>
        </div>
    </div>
</div>

<!-- Trading Statistics -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
    <div class="bg-gray-900 border border-gray-800 rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4 flex items-center text-gray-100">
            <i class="fas fa-chart-bar mr-2 text-blue-400"></i> Trading Statistics
        </h2>

        <div class="grid grid-cols-3 text-center text-gray-300 divide-x divide-gray-700">
            <div>
                <div class="text-xl text-blue-400 font-semibold">{{ $stock->transactions()->count() }}</div>
                <small class="text-gray-500">Transactions</small>
            </div>
            <div>
                <div class="text-xl text-blue-300 font-semibold">{{ $stock->portfolios()->count() }}</div>
                <small class="text-gray-500">Holdings</small>
            </div>
            <div>
                <div class="text-xl text-green-400 font-semibold">${{ number_format($stock->transactions()->sum('total_amount'), 2) }}</div>
                <small class="text-gray-500">Total Volume</small>
            </div>
        </div>

        <hr class="my-4 border-gray-700">

        <div class="grid grid-cols-2 text-center text-gray-300 divide-x divide-gray-700">
            <div>
                <div class="text-xl text-green-400 font-semibold">{{ $stock->transactions()->where('type', 'buy')->count() }}</div>
                <small class="text-gray-500">Buy Orders</small>
            </div>
            <div>
                <div class="text-xl text-red-400 font-semibold">{{ $stock->transactions()->where('type', 'sell')->count() }}</div>
                <small class="text-gray-500">Sell Orders</small>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-gray-900 border border-gray-800 rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-100 flex items-center">
                <i class="fas fa-history mr-2 text-blue-400"></i> Recent Transactions
            </h2>
            <a href="{{ route('admin.transactions.index') }}?stock_id={{ $stock->id }}"
               class="text-sm text-blue-400 hover:underline">View All</a>
        </div>

        @forelse($stock->transactions()->with('user')->latest()->limit(5)->get() as $transaction)
            <div class="flex justify-between items-center p-3 border border-gray-800 rounded mb-3 text-gray-300">
                <div>
                    <div class="font-semibold">{{ $transaction->user->name }}</div>
                    <small class="text-gray-500">{{ $transaction->created_at->format('M j, H:i') }}</small>
                </div>
                <div class="text-center">
                    <span class="px-2 py-0.5 rounded text-white text-xs {{ $transaction->type === 'buy' ? 'bg-green-600' : 'bg-red-600' }}">
                        {{ ucfirst($transaction->type) }}
                    </span>
                    <div class="text-xs mt-1">{{ number_format($transaction->quantity) }} @ ${{ number_format($transaction->price, 2) }}</div>
                </div>
                <div class="text-right">
                    <div class="font-semibold text-gray-100">${{ number_format($transaction->total_amount, 2) }}</div>
                    <span class="text-xs px-2 py-0.5 rounded {{ $transaction->status === 'executed' ? 'bg-green-700 text-white' : 'bg-yellow-600 text-white' }}">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 py-3">
                <i class="fas fa-chart-bar text-2xl mb-2 block"></i>
                No transactions yet
            </div>
        @endforelse
    </div>
</div>

<!-- Current Holders -->
<div class="bg-gray-900 border border-gray-800 rounded-lg shadow p-6 mt-6">
    <h2 class="text-lg font-semibold mb-4 flex items-center text-gray-100">
        <i class="fas fa-users mr-2 text-blue-400"></i> Current Holders
    </h2>

    @forelse($stock->portfolios()->with('user')->get() as $portfolio)
    <div class="grid grid-cols-6 text-gray-300 border-b border-gray-800 py-3">
        <div class="col-span-2">
            <a href="{{ route('admin.users.show', $portfolio->user) }}" class="text-blue-400 hover:underline font-semibold">
                {{ $portfolio->user->name }}
            </a>
            <div class="text-gray-500 text-sm">{{ $portfolio->user->email }}</div>
        </div>
        <div>
            <div class="font-semibold">{{ number_format($portfolio->quantity) }}</div>
            <small class="text-gray-500">Shares</small>
        </div>
        <div>
            <div class="font-semibold">${{ number_format($portfolio->average_price, 2) }}</div>
            <small class="text-gray-500">Avg Price</small>
        </div>
        <div>
            <div class="font-semibold">${{ number_format($portfolio->current_value, 2) }}</div>
            <small class="text-gray-500">Value</small>
        </div>
        <div class="{{ $portfolio->profit_loss >= 0 ? 'text-green-400' : 'text-red-400' }}">
            <div class="font-semibold">{{ $portfolio->profit_loss >= 0 ? '+' : '' }}${{ number_format($portfolio->profit_loss, 2) }}</div>
            <small class="text-gray-500">P&L</small>
        </div>
    </div>
    @empty
    <div class="text-center text-gray-500 py-4">
        <i class="fas fa-users text-2xl mb-2 block"></i>
        No current holders
    </div>
    @endforelse
</div>
@endsection

@push('scripts')
<script>
function toggleStatus(stockId, action) {
    if (confirm(`Are you sure you want to ${action} this stock?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/stocks/${stockId}/toggle-status`;

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';

        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'PATCH';

        form.appendChild(csrf);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
