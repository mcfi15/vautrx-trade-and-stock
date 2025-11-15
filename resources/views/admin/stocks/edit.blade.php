@extends('admin.layouts.app')

@section('title', 'Edit Stock')
@section('page-title', 'Edit Stock - ' . $stock->symbol)

@section('content')
<div class="flex gap-2 m-5">
    <a href="{{ route('admin.stocks.index') }}"
       class="px-3 py-1.5 border border-gray-600 rounded text-black-200 text-sm hover:bg-gray-700 transition">
        <i class="fas fa-arrow-left mr-1"></i> Back to Stocks
    </a>
    <a href="{{ route('admin.stocks.show', $stock) }}"
       class="px-3 py-1.5 border border-cyan-500 rounded text-cyan-400 text-sm hover:bg-cyan-600 transition">
        <i class="fas fa-eye mr-1"></i> View Stock
    </a>
</div>

<div class="flex justify-center">
    <div class="w-full max-w-3xl">
        <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-sm p-6 text-gray-200">
            <h5 class="text-lg font-semibold mb-4">Edit Stock Information</h5>

            <form method="POST" action="{{ route('admin.stocks.update', $stock) }}">
                @csrf
                @method('PUT')

                <!-- Symbol & Current Price -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="symbol" class="block font-semibold mb-1">Stock Symbol <span class="text-red-500">*</span></label>
                        <input type="text" id="symbol" name="symbol"
                               value="{{ old('symbol', $stock->symbol) }}"
                               placeholder="e.g., AAPL"
                               required
                               class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('symbol') ring-red-500 @enderror"
                               style="text-transform: uppercase;">
                        @error('symbol')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                        <small class="text-gray-400">Enter the trading symbol (e.g., AAPL, GOOGL)</small>
                    </div>

                    <div>
                        <label for="current_price" class="block font-semibold mb-1">Current Price <span class="text-red-500">*</span></label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 bg-gray-700 border border-gray-600 text-gray-200 rounded-l">$</span>
                            <input type="number" id="current_price" name="current_price"
                                   value="{{ old('current_price', $stock->current_price) }}"
                                   step="0.01" min="0.01"
                                   placeholder="0.00" required
                                   class="w-full bg-gray-700 border border-gray-600 rounded-r px-3 py-2 text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('current_price') ring-red-500 @enderror">
                        </div>
                        @error('current_price')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Company Name -->
                <div class="mt-4">
                    <label for="name" class="block font-semibold mb-1">Company Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name', $stock->name) }}"
                           placeholder="e.g., Apple Inc."
                           required
                           class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') ring-red-500 @enderror">
                    @error('name')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Sector & Exchange -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label for="sector" class="block font-semibold mb-1">Sector</label>
                        <input type="text" id="sector" name="sector"
                               value="{{ old('sector', $stock->sector) }}"
                               placeholder="e.g., Technology"
                               class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sector') ring-red-500 @enderror">
                        <small class="text-gray-400">Industry sector classification</small>
                        @error('sector')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="exchange" class="block font-semibold mb-1">Exchange</label>
                        <select id="exchange" name="exchange"
                                class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('exchange') ring-red-500 @enderror">
                            <option value="">Select Exchange</option>
                            <option value="NASDAQ" {{ old('exchange', $stock->exchange) === 'NASDAQ' ? 'selected' : '' }}>NASDAQ</option>
                            <option value="NYSE" {{ old('exchange', $stock->exchange) === 'NYSE' ? 'selected' : '' }}>NYSE</option>
                            <option value="AMEX" {{ old('exchange', $stock->exchange) === 'AMEX' ? 'selected' : '' }}>AMEX</option>
                            <option value="OTC" {{ old('exchange', $stock->exchange) === 'OTC' ? 'selected' : '' }}>OTC</option>
                        </select>
                        @error('exchange')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Opening, High & Low Prices -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    @foreach(['opening_price' => 'Opening Price', 'high_price' => 'Day High', 'low_price' => 'Day Low'] as $field => $label)
                        <div>
                            <label for="{{ $field }}" class="block font-semibold mb-1">{{ $label }}</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 bg-gray-700 border border-gray-600 text-gray-200 rounded-l">$</span>
                                <input type="number" id="{{ $field }}" name="{{ $field }}"
                                       value="{{ old($field, $stock->$field) }}"
                                       step="0.01" min="0" placeholder="0.00"
                                       class="w-full bg-gray-700 border border-gray-600 rounded-r px-3 py-2 text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 @error($field) ring-red-500 @enderror">
                            </div>
                            @error($field)
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach
                </div>

                <!-- Volume & Market Cap -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label for="volume" class="block font-semibold mb-1">Volume</label>
                        <input type="number" id="volume" name="volume"
                               value="{{ old('volume', $stock->volume) }}" min="0"
                               placeholder="0"
                               class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('volume') ring-red-500 @enderror">
                        <small class="text-gray-400">Number of shares traded</small>
                        @error('volume')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="market_cap" class="block font-semibold mb-1">Market Cap</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 bg-gray-700 border border-gray-600 text-gray-200 rounded-l">$</span>
                            <input type="number" id="market_cap" name="market_cap"
                                   value="{{ old('market_cap', $stock->market_cap) }}"
                                   step="0.01" min="0"
                                   placeholder="0.00"
                                   class="w-full bg-gray-700 border border-gray-600 rounded-r px-3 py-2 text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('market_cap') ring-red-500 @enderror">
                        </div>
                        <small class="text-gray-400">Total market value of outstanding shares</small>
                        @error('market_cap')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Active Checkbox -->
                <div class="mt-4 flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                           {{ old('is_active', $stock->is_active) ? 'checked' : '' }}
                           class="mr-2 h-4 w-4 text-blue-500 focus:ring-blue-400 rounded">
                    <label for="is_active" class="text-gray-200 font-semibold">
                        Active Stock
                        <br><small class="text-gray-400">Allow users to trade this stock</small>
                    </label>
                </div>

                <!-- Stock Statistics -->
                <div class="bg-gray-700 rounded-lg p-4 mt-6">
                    <h6 class="font-semibold mb-2 flex items-center"><i class="fas fa-info-circle mr-2"></i> Stock Statistics</h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-gray-300">
                        <div>
                            <small>Total Transactions:</small><br>
                            <strong>{{ $stock->transactions_count ?? 0 }}</strong>
                        </div>
                        <div>
                            <small>Portfolio Holdings:</small><br>
                            <strong>{{ $stock->portfolios_count ?? 0 }}</strong>
                        </div>
                        <div>
                            <small>Last Updated:</small><br>
                            <strong>{{ $stock->last_updated ? $stock->last_updated->format('M j, Y H:i') : 'Never' }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-between mt-6">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.stocks.index') }}" class="px-4 py-2 bg-gray-600 rounded text-gray-200 hover:bg-gray-500 transition">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </a>
                        <a href="{{ route('admin.stocks.show', $stock) }}" class="px-4 py-2 border border-cyan-500 text-cyan-400 rounded hover:bg-cyan-600 transition">
                            <i class="fas fa-eye mr-1"></i> View Details
                        </a>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded transition">
                        <i class="fas fa-save mr-1"></i> Update Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('symbol').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});
</script>
@endpush
