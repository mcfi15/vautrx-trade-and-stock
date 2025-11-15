@extends('admin.layouts.app')

@section('title', 'Stocks Management')

@section('content')

<div class="flex space-x-2 m-5">
    <a href="{{ route('admin.stocks.auto-import') }}" class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition">
        <i class="fas fa-download mr-1"></i> Auto Import
    </a>
    <a href="{{ route('admin.stocks.create') }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition">
        <i class="fas fa-plus mr-1"></i> Add Stock
    </a>
</div>

<!-- Live Update Status -->
<div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 rounded-md mb-4 flex items-center">
    <i class="fas fa-broadcast-tower mr-2"></i>
    <p><strong>Live Updates:</strong> Stock prices update automatically every 30 seconds
        <span class="inline-block w-2 h-2 bg-green-500 rounded-full ml-2 animate-pulse" title="Live updates active"></span>
    </p>
</div>

<!-- Search and Filters -->
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="p-6">
        <form method="GET" action="{{ route('admin.stocks.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                           class="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm"
                           placeholder="Symbol or name">
                </div>
                <div>
                    <label for="sector" class="block text-sm font-medium text-gray-700 mb-1">Sector</label>
                    <select id="sector" name="sector" class="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">All Sectors</option>
                        @foreach($sectors ?? [] as $sector)
                            <option value="{{ $sector }}" {{ request('sector') === $sector ? 'selected' : '' }}>
                                {{ $sector }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="exchange" class="block text-sm font-medium text-gray-700 mb-1">Exchange</label>
                    <select id="exchange" name="exchange" class="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">All Exchanges</option>
                        @foreach($exchanges ?? [] as $exchange)
                            <option value="{{ $exchange }}" {{ request('exchange') === $exchange ? 'selected' : '' }}>
                                {{ $exchange }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition">
                        <i class="fas fa-search mr-1"></i> Search
                    </button>
                    <a href="{{ route('admin.stocks.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-gray-600 text-sm font-medium rounded-md hover:bg-gray-100">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Stocks Table -->
<div class="bg-white shadow-sm rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h5 class="text-lg font-semibold text-gray-800">Stocks ({{ $stocks->total() }})</h5>
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="min-w-full text-sm text-left text-gray-700">
            <thead class="bg-gray-50 border-b text-gray-600 text-xs uppercase">
                <tr>
                    <th class="px-4 py-2">Stock</th>
                    <th class="px-4 py-2">Price</th>
                    <th class="px-4 py-2">Change</th>
                    <th class="px-4 py-2">Volume</th>
                    <th class="px-4 py-2">Market Cap</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Stats</th>
                    <th class="px-4 py-2">Last Updated</th>
                    <th class="px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stocks as $stock)
                <tr class="border-b hover:bg-gray-50 transition" data-stock-symbol="{{ $stock->symbol }}">
                    <td class="px-4 py-3">
                        <div>
                            <div class="font-semibold text-gray-800 flex items-center">
                                {{ $stock->symbol }}
                                <span class="ml-2 w-2 h-2 bg-green-500 rounded-full animate-pulse" title="Live updates enabled"></span>
                            </div>
                            <small class="text-gray-500">{{ $stock->name }}</small>
                            @if($stock->sector)
                                <div><span class="inline-block mt-1 px-2 py-0.5 bg-gray-100 text-gray-700 text-xs rounded-md">{{ $stock->sector }}</span></div>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3 font-medium">${{ number_format($stock->current_price, 2) }}</td>
                    <td class="px-4 py-3">
                        @if($stock->change !== 0)
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $stock->change >= 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $stock->change >= 0 ? '+' : '' }}{{ number_format($stock->change_percentage, 2) }}%
                            </span>
                            <div class="text-xs text-gray-500">
                                {{ $stock->change >= 0 ? '+' : '' }}${{ number_format($stock->change, 2) }}
                            </div>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ number_format($stock->volume) }}</td>
                    <td class="px-4 py-3">
                        @if($stock->market_cap)
                            ${{ number_format($stock->market_cap / 1000000, 0) }}M
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $stock->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $stock->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-500">
                        {{ $stock->transactions_count }} transactions<br>
                        {{ $stock->portfolios_count }} holders
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-500">
                        @if($stock->last_updated)
                            {{ $stock->last_updated->format('M j, H:i') }}
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('admin.stocks.show', $stock) }}" class="text-blue-500 hover:text-blue-700" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.stocks.edit', $stock) }}" class="text-yellow-500 hover:text-yellow-600" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="syncStock({{ $stock->id }}, '{{ $stock->symbol }}')" class="text-indigo-500 hover:text-indigo-700" title="Sync">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                            <button onclick="toggleStatus({{ $stock->id }}, '{{ $stock->is_active ? 'deactivate' : 'activate' }}')" 
                                class="{{ $stock->is_active ? 'text-red-500 hover:text-red-700' : 'text-green-500 hover:text-green-700' }}"
                                title="{{ $stock->is_active ? 'Deactivate' : 'Activate' }}">
                                <i class="fas fa-{{ $stock->is_active ? 'ban' : 'check' }}"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-gray-400 py-6">No stocks found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($stocks->hasPages())
    <div class="p-4 border-t border-gray-100">
        {{ $stocks->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function toggleStatus(stockId, action) {
    if (confirm(`Are you sure you want to ${action} this stock?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/stocks/${stockId}/toggle-status`;

        form.innerHTML = `
            @csrf
            @method('PATCH')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function syncStock(stockId, symbol) {
    if (confirm(`Sync ${symbol} with latest market data?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/stocks/${stockId}/sync`;
        form.innerHTML = `@csrf`;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
