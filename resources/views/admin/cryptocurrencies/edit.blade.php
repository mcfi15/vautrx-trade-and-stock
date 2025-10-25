@extends('admin.layouts.app')

@section('title', 'Edit Cryptocurrency')

@section('content')
<div class="space-y-8">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-center border-b border-gray-200 pb-4">
        <h1 class="text-2xl font-semibold text-gray-800 flex items-center gap-2">
            <i class="fas fa-edit text-indigo-600"></i> Edit Cryptocurrency: {{ $cryptocurrency->symbol }}
        </h1>
        <a href="{{ route('admin.cryptocurrencies.index') }}" 
           class="inline-flex items-center bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm shadow-sm transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    <!-- Alerts -->
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded flex items-start">
            <i class="fas fa-exclamation-circle mt-1 mr-2"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 rounded">
            <div class="flex items-start gap-2">
                <i class="fas fa-exclamation-triangle mt-1"></i>
                <div>
                    <strong>Error:</strong> Please fix the following errors:
                    <ul class="list-disc list-inside mt-2 space-y-1 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Edit Form -->
        <div class="lg:col-span-2 bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-indigo-700 mb-4 flex items-center gap-2">
                <i class="fas fa-coins"></i> Cryptocurrency Information
            </h2>

            <form action="{{ route('admin.cryptocurrencies.update', $cryptocurrency->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="symbol" class="block text-sm font-medium text-gray-700">Symbol <span class="text-red-500">*</span></label>
                        <input type="text" id="symbol" name="symbol" required
                               value="{{ old('symbol', $cryptocurrency->symbol) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="text-xs text-gray-500 mt-1">e.g., BTC</p>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" required
                               value="{{ old('name', $cryptocurrency->name) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="text-xs text-gray-500 mt-1">Full name of the cryptocurrency</p>
                    </div>
                </div>

                <!-- Identifiers -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="coingecko_id" class="block text-sm font-medium text-gray-700">CoinGecko ID</label>
                        <input type="text" id="coingecko_id" value="{{ $cryptocurrency->coingecko_id }}" readonly
                               class="mt-1 block w-full rounded-md bg-gray-100 border-gray-300 text-gray-600">
                        <p class="text-xs text-gray-500 mt-1">Cannot be changed after creation</p>
                    </div>
                    <div>
                        <label for="binance_symbol" class="block text-sm font-medium text-gray-700">Binance Symbol</label>
                        <input type="text" id="binance_symbol" value="{{ $cryptocurrency->binance_symbol }}" readonly
                               class="mt-1 block w-full rounded-md bg-gray-100 border-gray-300 text-gray-600">
                        <p class="text-xs text-gray-500 mt-1">Auto-populated from Binance sync</p>
                    </div>
                </div>

                <!-- Technical Info -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="blockchain" class="block text-sm font-medium text-gray-700">Blockchain</label>
                        <input type="text" id="blockchain" value="{{ ucfirst($cryptocurrency->blockchain) }}" readonly
                               class="mt-1 block w-full rounded-md bg-gray-100 border-gray-300 text-gray-600">
                    </div>
                    <div>
                        <label for="decimals" class="block text-sm font-medium text-gray-700">Decimals</label>
                        <input type="number" id="decimals" value="{{ $cryptocurrency->decimals }}" readonly
                               class="mt-1 block w-full rounded-md bg-gray-100 border-gray-300 text-gray-600">
                    </div>
                    <div>
                        <label for="current_price" class="block text-sm font-medium text-gray-700">Current Price (USD)</label>
                        <input type="text" id="current_price" value="${{ number_format($cryptocurrency->current_price, 8) }}" readonly
                               class="mt-1 block w-full rounded-md bg-gray-100 border-gray-300 text-gray-600">
                    </div>
                </div>

                <!-- Status Settings -->
                <div class="border-t border-gray-200 pt-4">
                    <h3 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-cog"></i> Status Settings
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="flex flex-col">
                            <label class="flex items-center cursor-pointer gap-3">
                                <input type="checkbox" id="is_active" name="is_active" value="1"
                                       class="sr-only peer" {{ old('is_active', $cryptocurrency->is_active) ? 'checked' : '' }}>
                                <div class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-green-500 relative after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:w-4 after:h-4 after:bg-white after:rounded-full after:transition-all peer-checked:after:translate-x-full"></div>
                                <span class="text-sm font-medium text-gray-700">Active</span>
                            </label>
                            <p class="text-xs text-gray-500 mt-1">Enable/disable this cryptocurrency</p>
                        </div>

                        <div class="flex flex-col">
                            <label class="flex items-center cursor-pointer gap-3">
                                <input type="checkbox" id="is_tradable" name="is_tradable" value="1"
                                       class="sr-only peer" {{ old('is_tradable', $cryptocurrency->is_tradable) ? 'checked' : '' }}>
                                <div class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-green-500 relative after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:w-4 after:h-4 after:bg-white after:rounded-full after:transition-all peer-checked:after:translate-x-full"></div>
                                <span class="text-sm font-medium text-gray-700">Tradable</span>
                            </label>
                            <p class="text-xs text-gray-500 mt-1">Allow trading for this cryptocurrency</p>
                        </div>

                        <div class="flex flex-col">
                            <label class="flex items-center cursor-pointer gap-3">
                                <input type="checkbox" id="enable_realtime" name="enable_realtime" value="1"
                                       class="sr-only peer" {{ old('enable_realtime', $cryptocurrency->enable_realtime) ? 'checked' : '' }}>
                                <div class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-green-500 relative after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:w-4 after:h-4 after:bg-white after:rounded-full after:transition-all peer-checked:after:translate-x-full"></div>
                                <span class="text-sm font-medium text-gray-700">Real-time Tracking</span>
                            </label>
                            <p class="text-xs text-gray-500 mt-1">Enable WebSocket price updates</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-between border-t border-gray-200 pt-6">
                    <a href="{{ route('admin.cryptocurrencies.index') }}" 
                       class="inline-flex items-center bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm shadow-sm transition">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm shadow-sm transition">
                        <i class="fas fa-save mr-2"></i> Update Cryptocurrency
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Sidebar -->
        <div class="bg-white shadow rounded-lg p-6 space-y-6">
            @if($cryptocurrency->logo_url)
                <div class="flex justify-center">
                    <img src="{{ $cryptocurrency->logo_url }}" alt="{{ $cryptocurrency->symbol }}" class="w-24 h-24 object-contain">
                </div>
            @endif

            <!-- Status -->
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Current Status</h4>
                <ul class="space-y-1 text-sm text-gray-600">
                    <li><strong>Active:</strong> 
                        @if($cryptocurrency->is_active)
                            <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded text-xs">Yes</span>
                        @else
                            <span class="bg-gray-200 text-gray-600 px-2 py-0.5 rounded text-xs">No</span>
                        @endif
                    </li>
                    <li><strong>Tradable:</strong> 
                        @if($cryptocurrency->is_tradable)
                            <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded text-xs">Yes</span>
                        @else
                            <span class="bg-gray-200 text-gray-600 px-2 py-0.5 rounded text-xs">No</span>
                        @endif
                    </li>
                    <li><strong>Real-time:</strong> 
                        @if($cryptocurrency->enable_realtime)
                            <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded text-xs">Enabled</span>
                        @else
                            <span class="bg-gray-200 text-gray-600 px-2 py-0.5 rounded text-xs">Disabled</span>
                        @endif
                    </li>
                </ul>
            </div>

            <!-- Market Info -->
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Market Data</h4>
                <ul class="space-y-1 text-sm text-gray-600">
                    <li><strong>Price:</strong> ${{ number_format($cryptocurrency->current_price, 8) }}</li>
                    <li><strong>24h Change:</strong> 
                        @if($cryptocurrency->price_change_24h >= 0)
                            <span class="text-green-600">+{{ number_format($cryptocurrency->price_change_24h, 2) }}%</span>
                        @else
                            <span class="text-red-600">{{ number_format($cryptocurrency->price_change_24h, 2) }}%</span>
                        @endif
                    </li>
                    <li><strong>Last Updated:</strong> 
                        @if($cryptocurrency->price_updated_at)
                            <small>{{ $cryptocurrency->price_updated_at->diffForHumans() }}</small>
                        @else
                            <small class="text-gray-400">Never</small>
                        @endif
                    </li>
                </ul>
            </div>

            <!-- Info Note -->
            <div class="bg-blue-50 border-l-4 border-blue-400 text-blue-700 p-3 rounded text-sm">
                <i class="fas fa-info-circle mr-2"></i>
                Some fields like CoinGecko ID, Binance Symbol, Blockchain, and Decimals cannot be changed after creation for data integrity.
            </div>

            <!-- Quick Actions -->
            <div class="space-y-2">
                <a href="{{ route('admin.cryptocurrencies.show', $cryptocurrency->id) }}"
                   class="w-full inline-flex justify-center items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-md text-sm shadow-sm transition">
                    <i class="fas fa-eye mr-2"></i> View Details
                </a>
                <form action="{{ route('admin.cryptocurrencies.destroy', $cryptocurrency->id) }}" method="POST"
                      onsubmit="return confirm('Are you sure you want to delete {{ $cryptocurrency->symbol }}? This action cannot be undone!');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full inline-flex justify-center items-center bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm shadow-sm transition">
                        <i class="fas fa-trash mr-2"></i> Delete Cryptocurrency
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    form.addEventListener('submit', (e) => {
        const symbol = document.getElementById('symbol').value.trim();
        const name = document.getElementById('name').value.trim();
        if (!symbol || !name) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
});
</script>
@endsection
