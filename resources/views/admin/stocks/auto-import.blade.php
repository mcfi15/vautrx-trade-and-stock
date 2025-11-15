@extends('admin.layouts.app')

@section('title', 'Automatic Stock Import')
@section('page-title', 'Automatic Stock Import')

{{-- @section('page-actions')

@endsection --}}

@section('content')
<div class="flex gap-2">
    <a href="{{ route('admin.stocks.index') }}" 
       class="px-3 py-1.5 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-500 transition">
        <i class="fas fa-arrow-left mr-1"></i> Back to Stocks
    </a>
    <a href="{{ route('admin.stocks.create') }}" 
       class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-500 transition">
        <i class="fas fa-plus mr-1"></i> Add Manually
    </a>
</div>
<div class="space-y-6 text-gray-300">

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gray-800 p-6 rounded-xl text-center border border-blue-500">
            <i class="fas fa-chart-line text-3xl text-blue-400 mb-2"></i>
            <h3 class="text-2xl font-semibold">{{ $totalStocks }}</h3>
            <p class="text-gray-400 text-sm">Total Stocks</p>
        </div>

        <div class="bg-gray-800 p-6 rounded-xl text-center border border-green-500">
            <i class="fas fa-check-circle text-3xl text-green-400 mb-2"></i>
            <h3 class="text-2xl font-semibold">{{ $activeStocks }}</h3>
            <p class="text-gray-400 text-sm">Active Stocks</p>
        </div>

        <div class="bg-gray-800 p-6 rounded-xl text-center border border-yellow-500">
            <i class="fas fa-pause-circle text-3xl text-yellow-400 mb-2"></i>
            <h3 class="text-2xl font-semibold">{{ $totalStocks - $activeStocks }}</h3>
            <p class="text-gray-400 text-sm">Inactive Stocks</p>
        </div>

        <div class="bg-gray-800 p-6 rounded-xl text-center border border-cyan-500">
            <i class="fas fa-clock text-3xl text-cyan-400 mb-2"></i>
            <h3 class="text-lg">{{ $lastUpdate ? $lastUpdate->diffForHumans() : 'Never' }}</h3>
            <p class="text-gray-400 text-sm">Last Updated</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Import Single Stock -->
        <div class="bg-gray-800 rounded-xl border border-blue-600">
            <div class="bg-blue-600 text-white px-4 py-3 rounded-t-xl">
                <h5 class="font-semibold"><i class="fas fa-download mr-2"></i> Import Single Stock</h5>
            </div>
            <div class="p-5">
                <p class="text-gray-400 text-sm mb-3">Import a specific stock by its symbol with real-time data from APIs.</p>
                <form action="{{ route('admin.stocks.import-single') }}" method="POST" class="space-y-3">
                    @csrf
                    <div class="flex">
                        <input type="text" name="symbol" required
                               placeholder="Enter stock symbol (e.g., AAPL, TSLA)"
                               class="w-full rounded-l-md bg-gray-900 border border-gray-700 text-gray-200 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 uppercase">
                        <button type="submit"
                                class="px-4 bg-blue-600 text-white rounded-r-md hover:bg-blue-500 transition">
                            <i class="fas fa-download mr-1"></i> Import
                        </button>
                    </div>
                    <p class="text-xs text-gray-500">Stock symbols are automatically converted to uppercase.</p>
                </form>
            </div>
        </div>

        <!-- Demo Stocks -->
        <div class="bg-gray-800 rounded-xl border border-cyan-600">
            <div class="bg-cyan-600 text-white px-4 py-3 rounded-t-xl">
                <h5 class="font-semibold"><i class="fas fa-vial mr-2"></i> Demo Stocks</h5>
            </div>
            <div class="p-5">
                <p class="text-gray-400 text-sm mb-3">Add sample stocks (AAPL, TSLA, MSFT) for quick testing.</p>
                <form action="{{ route('admin.stocks.add-demo') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="w-full py-2 bg-cyan-600 hover:bg-cyan-500 rounded-lg text-white font-semibold">
                        <i class="fas fa-vial mr-1"></i> Add Demo Stocks
                    </button>
                </form>
                <p class="text-xs text-gray-500 mt-2"><i class="fas fa-info-circle mr-1 text-cyan-400"></i>Works without API key.</p>
            </div>
        </div>

        <!-- Update All Stocks -->
        <div class="bg-gray-800 rounded-xl border border-green-600">
            <div class="bg-green-600 text-white px-4 py-3 rounded-t-xl">
                <h5 class="font-semibold"><i class="fas fa-sync-alt mr-2"></i> Update All Stocks</h5>
            </div>
            <div class="p-5">
                <p class="text-gray-400 text-sm mb-3">Fetch and refresh all stock prices from the market.</p>
                <form action="{{ route('admin.stocks.update-all') }}" method="POST" onsubmit="return confirmUpdate()">
                    @csrf
                    <button type="submit" 
                            class="w-full py-2 bg-green-600 hover:bg-green-500 rounded-lg text-white font-semibold">
                        <i class="fas fa-sync-alt mr-1"></i> Update All Stocks
                    </button>
                </form>
                <p class="text-xs text-gray-500 mt-2"><i class="fas fa-exclamation-triangle mr-1 text-yellow-400"></i>This may take several minutes.</p>
            </div>
        </div>
    </div>

    <!-- Bulk Import -->
    <div class="bg-gray-800 border border-cyan-700 rounded-xl p-6">
        <h5 class="text-cyan-400 font-semibold mb-4 flex items-center">
            <i class="fas fa-layer-group mr-2"></i> Bulk Import from Popular Lists
        </h5>
        <p class="text-gray-400 text-sm mb-6">Choose from popular market indices and import multiple stocks.</p>

        <form action="{{ route('admin.stocks.bulk-import') }}" method="POST" onsubmit="return confirmBulkImport()">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                @foreach($stockLists as $key => $list)
                <label for="list_{{ $key }}" class="block cursor-pointer border border-gray-700 rounded-lg p-4 hover:bg-gray-700 transition">
                    <div class="flex items-start">
                        <input type="radio" name="list_type" id="list_{{ $key }}" value="{{ $key }}" class="mt-1 mr-3">
                        <div>
                            <p class="font-semibold text-gray-100">{{ $list['name'] }}</p>
                            <p class="text-sm text-gray-400 mt-1">{{ $list['description'] }}</p>
                            <p class="text-xs text-cyan-400 mt-2"><i class="fas fa-info-circle mr-1"></i>{{ count($list['symbols']) }} stocks</p>
                        </div>
                    </div>
                </label>
                @endforeach
            </div>

            <div class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1">
                    <label for="limit" class="text-sm text-gray-300">Import Limit (optional)</label>
                    <input type="number" id="limit" name="limit" min="1" max="500"
                           placeholder="Leave empty for all"
                           class="w-full mt-1 bg-gray-900 border border-gray-700 rounded-md p-2 text-gray-200">
                    <p class="text-xs text-gray-500 mt-1">Limit number of imported stocks for testing.</p>
                </div>
                <button type="submit" 
                        class="px-6 py-2 bg-cyan-600 hover:bg-cyan-500 text-white rounded-lg font-semibold">
                    <i class="fas fa-download mr-1"></i> Start Bulk Import
                </button>
            </div>
        </form>

        <div class="mt-4 p-3 bg-yellow-800 text-yellow-200 text-sm rounded-lg flex items-start">
            <i class="fas fa-exclamation-triangle mt-1 mr-2"></i>
            <p><strong>Important:</strong> Bulk imports may take several minutes due to API rate limits. Please don’t refresh the page during import.</p>
        </div>
    </div>

    <!-- API Configuration -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 mt-6">
        <h5 class="text-gray-200 font-semibold mb-4 flex items-center">
            <i class="fas fa-cog mr-2"></i> API Configuration
        </h5>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h6 class="font-semibold text-gray-100">Current API Provider</h6>
                <p class="text-gray-400 mb-4">Financial Modeling Prep (FMP)</p>

                <h6 class="font-semibold text-gray-100">Available Data</h6>
                <ul class="text-gray-400 text-sm space-y-1 mt-2">
                    <li><i class="fas fa-check text-green-400 mr-2"></i>Real-time prices</li>
                    <li><i class="fas fa-check text-green-400 mr-2"></i>Daily OHLC data</li>
                    <li><i class="fas fa-check text-green-400 mr-2"></i>Market cap & volume</li>
                    <li><i class="fas fa-check text-green-400 mr-2"></i>Company profiles</li>
                    <li><i class="fas fa-check text-green-400 mr-2"></i>Sector information</li>
                </ul>
            </div>
            <div>
                <h6 class="font-semibold text-gray-100">Setup Instructions</h6>
                <ol class="list-decimal ml-5 text-sm text-gray-400 space-y-1 mt-2">
                    <li>Get a free API key from <a href="https://financialmodelingprep.com/developer/docs" target="_blank" class="text-cyan-400 underline">Financial Modeling Prep</a>.</li>
                    <li>Add <code class="text-gray-300 bg-gray-700 px-1 rounded">FMP_API_KEY=your_api_key</code> to your <code>.env</code> file.</li>
                    <li>If no API key is set, demo data will be used (limited).</li>
                </ol>
                <div class="mt-3 p-3 bg-cyan-900 text-cyan-200 text-xs rounded-lg">
                    <i class="fas fa-info-circle mr-1"></i> Free tier allows 250 requests/day. Consider upgrading for production use.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmUpdate() {
    return confirm('Are you sure you want to update all stocks? This may take several minutes.');
}

function confirmBulkImport() {
    const selectedList = document.querySelector('input[name="list_type"]:checked');
    if (!selectedList) {
        alert('Please select a stock list to import.');
        return false;
    }
    const listName = selectedList.parentElement.textContent.trim();
    const limit = document.getElementById('limit').value;
    const limitText = limit ? ` (limited to ${limit} stocks)` : '';
    return confirm(`Import stocks from ${listName}${limitText}?`);
}

document.querySelector('input[name="symbol"]').addEventListener('input', function(e) {
    e.target.value = e.target.value.toUpperCase();
});
</script>
@endpush
