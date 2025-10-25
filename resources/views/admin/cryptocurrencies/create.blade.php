@extends('admin.layouts.app')

@section('title', 'Add Cryptocurrency')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Add New Cryptocurrency</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.cryptocurrencies.store') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Symbol</label>
                    <input type="text" name="symbol" value="{{ old('symbol') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="BTC">
                    @error('symbol')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Bitcoin">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">CoinGecko ID</label>
                    <input type="text" name="coingecko_id" value="{{ old('coingecko_id') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="bitcoin">
                    <p class="text-sm text-gray-500 mt-1">Find ID at <a href="https://www.coingecko.com" target="_blank" class="text-indigo-600">CoinGecko.com</a></p>
                    @error('coingecko_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contract Address (Optional)</label>
                    <input type="text" name="contract_address" value="{{ old('contract_address') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="0x...">
                    @error('contract_address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Blockchain</label>
                    <select name="blockchain" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="ethereum" {{ old('blockchain') == 'ethereum' ? 'selected' : '' }}>Ethereum</option>
                        <option value="bsc" {{ old('blockchain') == 'bsc' ? 'selected' : '' }}>Binance Smart Chain</option>
                        <option value="polygon" {{ old('blockchain') == 'polygon' ? 'selected' : '' }}>Polygon</option>
                    </select>
                    @error('blockchain')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Decimals</label>
                    <input type="number" name="decimals" value="{{ old('decimals', 18) }}" required min="0" max="18"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    @error('decimals')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-4 pt-4">
                    <a href="{{ route('admin.cryptocurrencies.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Add Cryptocurrency
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
