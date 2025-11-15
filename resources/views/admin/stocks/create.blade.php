@extends('admin.layouts.app')

@section('title', 'Add New Stock')
@section('page-title', 'Add New Stock')

{{-- @section('page-actions')

@endsection --}}

@section('content')
<div class="flex justify-end mb-4">
    <a href="{{ route('admin.stocks.index') }}" 
       class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-gray-700 text-sm rounded-md hover:bg-gray-100 transition">
        <i class="fas fa-arrow-left mr-1"></i> Back to Stocks
    </a>
</div>
<div class="flex justify-center">
    <div class="w-full max-w-3xl">
        <div class="bg-white shadow-sm rounded-xl">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-gray-800">Stock Information</h5>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('admin.stocks.store') }}" class="space-y-6">
                    @csrf

                    <!-- Symbol & Current Price -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="symbol" class="block text-sm font-medium text-gray-700">
                                Stock Symbol <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="symbol" name="symbol"
                                   class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm @error('symbol') border-red-500 @enderror"
                                   value="{{ old('symbol') }}" placeholder="e.g., AAPL" style="text-transform: uppercase;" required>
                            <p class="text-xs text-gray-500 mt-1">Enter the stock trading symbol (e.g., AAPL, GOOGL)</p>
                            @error('symbol')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="current_price" class="block text-sm font-medium text-gray-700">
                                Current Price <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center mt-1">
                                <span class="inline-flex items-center px-2 bg-gray-100 border border-gray-300 border-r-0 text-gray-600 rounded-l-md">$</span>
                                <input type="number" id="current_price" name="current_price"
                                       class="flex-1 border-gray-300 rounded-r-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm @error('current_price') border-red-500 @enderror"
                                       value="{{ old('current_price') }}" step="0.01" min="0.01" placeholder="0.00" required>
                            </div>
                            @error('current_price')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Company Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Company Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name"
                               class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm @error('name') border-red-500 @enderror"
                               value="{{ old('name') }}" placeholder="e.g., Apple Inc." required>
                        @error('name')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sector & Exchange -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="sector" class="block text-sm font-medium text-gray-700">Sector</label>
                            <input type="text" id="sector" name="sector"
                                   class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm @error('sector') border-red-500 @enderror"
                                   value="{{ old('sector') }}" placeholder="e.g., Technology, Healthcare">
                            <p class="text-xs text-gray-500 mt-1">Industry sector classification</p>
                            @error('sector')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="exchange" class="block text-sm font-medium text-gray-700">Exchange</label>
                            <select id="exchange" name="exchange"
                                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm @error('exchange') border-red-500 @enderror">
                                <option value="">Select Exchange</option>
                                <option value="NASDAQ" {{ old('exchange') === 'NASDAQ' ? 'selected' : '' }}>NASDAQ</option>
                                <option value="NYSE" {{ old('exchange') === 'NYSE' ? 'selected' : '' }}>NYSE</option>
                                <option value="AMEX" {{ old('exchange') === 'AMEX' ? 'selected' : '' }}>AMEX</option>
                                <option value="OTC" {{ old('exchange') === 'OTC' ? 'selected' : '' }}>OTC</option>
                            </select>
                            @error('exchange')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Prices -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="opening_price" class="block text-sm font-medium text-gray-700">Opening Price</label>
                            <div class="flex items-center mt-1">
                                <span class="inline-flex items-center px-2 bg-gray-100 border border-gray-300 border-r-0 text-gray-600 rounded-l-md">$</span>
                                <input type="number" id="opening_price" name="opening_price"
                                       class="flex-1 border-gray-300 rounded-r-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm @error('opening_price') border-red-500 @enderror"
                                       value="{{ old('opening_price') }}" step="0.01" min="0" placeholder="0.00">
                            </div>
                            @error('opening_price')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="high_price" class="block text-sm font-medium text-gray-700">Day High</label>
                            <div class="flex items-center mt-1">
                                <span class="inline-flex items-center px-2 bg-gray-100 border border-gray-300 border-r-0 text-gray-600 rounded-l-md">$</span>
                                <input type="number" id="high_price" name="high_price"
                                       class="flex-1 border-gray-300 rounded-r-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm @error('high_price') border-red-500 @enderror"
                                       value="{{ old('high_price') }}" step="0.01" min="0" placeholder="0.00">
                            </div>
                            @error('high_price')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="low_price" class="block text-sm font-medium text-gray-700">Day Low</label>
                            <div class="flex items-center mt-1">
                                <span class="inline-flex items-center px-2 bg-gray-100 border border-gray-300 border-r-0 text-gray-600 rounded-l-md">$</span>
                                <input type="number" id="low_price" name="low_price"
                                       class="flex-1 border-gray-300 rounded-r-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm @error('low_price') border-red-500 @enderror"
                                       value="{{ old('low_price') }}" step="0.01" min="0" placeholder="0.00">
                            </div>
                            @error('low_price')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Volume & Market Cap -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="volume" class="block text-sm font-medium text-gray-700">Volume</label>
                            <input type="number" id="volume" name="volume"
                                   class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm @error('volume') border-red-500 @enderror"
                                   value="{{ old('volume', 0) }}" min="0" placeholder="0">
                            <p class="text-xs text-gray-500 mt-1">Number of shares traded</p>
                            @error('volume')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="market_cap" class="block text-sm font-medium text-gray-700">Market Cap</label>
                            <div class="flex items-center mt-1">
                                <span class="inline-flex items-center px-2 bg-gray-100 border border-gray-300 border-r-0 text-gray-600 rounded-l-md">$</span>
                                <input type="number" id="market_cap" name="market_cap"
                                       class="flex-1 border-gray-300 rounded-r-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm @error('market_cap') border-red-500 @enderror"
                                       value="{{ old('market_cap') }}" step="0.01" min="0" placeholder="0.00">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Total market value of outstanding shares</p>
                            @error('market_cap')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Active Checkbox -->
                    <div class="flex items-start space-x-2">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                               class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active" class="text-sm text-gray-700">
                            <strong>Active Stock</strong><br>
                            <span class="text-gray-500 text-xs">Allow users to trade this stock</span>
                        </label>
                    </div>

                    <hr class="border-gray-200">

                    <!-- Buttons -->
                    <div class="flex justify-between pt-4">
                        <a href="{{ route('admin.stocks.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 transition">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition">
                            <i class="fas fa-plus mr-1"></i> Add Stock
                        </button>
                    </div>
                </form>
            </div>
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
