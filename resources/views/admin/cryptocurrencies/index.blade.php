@extends('admin.layouts.app')

@section('title', 'Cryptocurrencies Management')

@section('content')
<div class="w-full px-4 mx-auto">

    {{-- Header --}}
    <div class="flex flex-wrap justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Cryptocurrencies Management</h1>

        <div class="space-x-2">
            <a href=""
               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm flex items-center space-x-1 inline-flex">
                <i class="fas fa-plus"></i><span>Add Cryptocurrency</span>
            </a>

            <button onclick="syncPrices(event)"
                    id="sync-prices-button"
                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm flex items-center space-x-1 inline-flex">
                <i class="fas fa-dollar-sign"></i><span>Sync Prices</span>
            </button>

            <button onclick="syncCryptos(event)"
                    id="sync-button"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm flex items-center space-x-1 inline-flex">
                <i class="fas fa-sync-alt"></i><span>Sync from Binance</span>
            </button>
        </div>
    </div>

    {{-- Success --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-4 flex justify-between">
        <span><i class="fas fa-check-circle"></i> {{ session('success') }}</span>
        <button onclick="this.parentNode.remove()" class="text-green-800">&times;</button>
    </div>
    @endif

    {{-- Error --}}
    @if(session('error'))
    <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-4 flex justify-between">
        <span><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</span>
        <button onclick="this.parentNode.remove()" class="text-red-800">&times;</button>
    </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

        <div class="bg-white border rounded shadow p-4 flex items-center justify-between">
            <div>
                <span class="text-xs uppercase text-blue-600 font-semibold">Total Cryptocurrencies</span>
                <div class="text-xl font-bold text-gray-800" id="total-cryptos">{{ $cryptocurrencies->total() }}</div>
            </div>
            <i class="fas fa-coins text-gray-300 text-3xl"></i>
        </div>

        <div class="bg-white border rounded shadow p-4 flex items-center justify-between">
            <div>
                <span class="text-xs uppercase text-green-600 font-semibold">Realtime Tracking</span>
                <div class="text-xl font-bold text-gray-800" id="realtime-count">{{ $cryptocurrencies->where('enable_realtime', true)->count() }}</div>
            </div>
            <i class="fas fa-broadcast-tower text-gray-300 text-3xl"></i>
        </div>

        <div class="bg-white border rounded shadow p-4 flex items-center justify-between">
            <div>
                <span class="text-xs uppercase text-cyan-600 font-semibold">Live Connections</span>
                <div class="text-xl font-bold text-gray-800" id="live-connections">-</div>
            </div>
            <i class="fas fa-plug text-gray-300 text-3xl"></i>
        </div>

        <div class="bg-white border rounded shadow p-4 flex items-center justify-between">
            <div>
                <span class="text-xs uppercase text-yellow-600 font-semibold">Service Status</span>
                <div class="text-xl font-bold" id="service-status">
                    <span class="text-gray-600 bg-gray-200 px-2 py-1 rounded text-xs">Checking...</span>
                </div>
            </div>
            <i class="fas fa-server text-gray-300 text-3xl"></i>
        </div>

    </div>

    {{-- Table --}}
    <div class="bg-white border rounded shadow">
        <div class="border-b px-4 py-3 font-semibold text-gray-700">Cryptocurrency List</div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                    <tr>
                        <th class="p-2 text-left">Symbol</th>
                        <th class="p-2 text-left">Name</th>
                        <th class="p-2 text-left">Price</th>
                        <th class="p-2 text-left">24h Change</th>
                        <th class="p-2 text-left">Volume</th>
                        <th class="p-2 text-left">Realtime</th>
                        <th class="p-2 text-left">Active</th>
                        <th class="p-2 text-left">Tradable</th>
                        <th class="p-2 text-left">Updated</th>
                        <th class="p-2 text-left">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                @forelse($cryptocurrencies as $crypto)
                <tr id="crypto-row-{{ $crypto->id }}" class="hover:bg-gray-50">

                    <td class="p-2 font-semibold flex items-center gap-2">
                        {{ $crypto->symbol }}
                        @if($crypto->logo_url)
                            <img src="{{ $crypto->logo_url }}" class="h-5 w-5 rounded" alt="">
                        @endif
                    </td>

                    <td class="p-2">{{ $crypto->name }}</td>

                    <td class="p-2 crypto-price"
                        data-crypto-id="{{ $crypto->id }}">
                        ${{ number_format($crypto->current_price, 8) }}
                    </td>

                    <td class="p-2 crypto-change"
                        data-crypto-id="{{ $crypto->id }}">
                        @if($crypto->price_change_24h >= 0)
                        <span class="text-green-600 flex items-center gap-1">
                            <i class="fas fa-arrow-up"></i> {{ number_format($crypto->price_change_24h, 2) }}%
                        </span>
                        @else
                        <span class="text-red-600 flex items-center gap-1">
                            <i class="fas fa-arrow-down"></i> {{ number_format($crypto->price_change_24h, 2) }}%
                        </span>
                        @endif
                    </td>

                    <td class="p-2">${{ number_format($crypto->volume_24h, 0) }}</td>

                    {{-- realtime toggle --}}
                    <td class="p-2">
                        <input type="checkbox"
                            class="realtime-toggle h-4 w-4 text-green-500"
                            data-crypto-id="{{ $crypto->id }}"
                            {{ $crypto->enable_realtime ? 'checked' : '' }}>
                    </td>

                    {{-- active toggle --}}
                    <td class="p-2">
                        <input type="checkbox"
                            class="status-toggle h-4 w-4 text-blue-600"
                            data-crypto-id="{{ $crypto->id }}"
                            {{ $crypto->is_active ? 'checked' : '' }}>
                    </td>

                    <td class="p-2">
                        <span class="px-2 py-1 rounded text-xs {{ $crypto->is_tradable ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }}">
                            {{ $crypto->is_tradable ? 'Yes' : 'No' }}
                        </span>
                    </td>

                    <td class="p-2 text-xs">
                        {{ $crypto->price_updated_at ? $crypto->price_updated_at->diffForHumans() : 'Never' }}
                    </td>

                    <td class="p-2 flex space-x-1">
                        <a href=""
                           class="px-2 py-1 bg-blue-500 text-white rounded text-xs">
                           <i class="fas fa-edit"></i>
                        </a>

                        <form action=""
                              method="POST"
                              onsubmit="return confirm('Delete {{ $crypto->symbol }}?');">
                            @csrf @method('DELETE')
                            <button class="px-2 py-1 bg-red-600 text-white rounded text-xs">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-8 text-gray-600">
                        <i class="fas fa-coins text-3xl mb-2"></i>
                        <p>No cryptocurrencies found</p>
                    </td>
                </tr>
                @endforelse

                </tbody>
            </table>
        </div>

        <div class="p-4">
            {{ $cryptocurrencies->links() }}
        </div>
    </div>
</div>

<style>
.crypto-price.updating {
    animation: pulse 0.5s ease-in-out;
}
@keyframes pulse {
    0%, 100% { background-color: transparent; }
    50% { background-color: #fef3c7; }
}
</style>

<script src="{{ asset('js/binance.js') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    window.startLivePrices([
        'BTCUSDT', 'ETHUSDT', 'BNBUSDT', 'SOLUSDT', 'XRPUSDT',
        'ADAUSDT', 'SHIBUSDT', 'LTCUSDT', 'LINKUSDT'
    ]);
});
</script>


@endsection

@section('scripts')
<script>
/**
 * Correct backend routes
 */
const priceUrl = "{{ route('admin.cryptocurrencies.prices') }}";
const statusUrl = "{{ route('admin.cryptocurrencies.status') }}";
const syncUrl = "{{ route('admin.cryptocurrencies.sync-prices') }}";
const syncBinanceUrl = "{{ route('admin.cryptocurrencies.sync-from-binance') }}";

/**
 * Fetch & Update Prices
 */
function updatePrices() {
    fetch(priceUrl)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                data.data.forEach(crypto => updateCryptoRow(crypto));
            }
        })
        .catch(err => console.error("Price fetch error:", err));
}

/**
 * Update Service Status
 */
function updateServiceStatus() {
    fetch(statusUrl)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                let s = data;

                document.getElementById('live-connections').textContent = s.connections ?? 0;
                document.getElementById('realtime-count').textContent = s.tracked ?? 0;
                document.getElementById('total-cryptos').textContent = s.total ?? 0;

                document.getElementById('service-status').innerHTML =
                    `<span class="px-2 py-1 rounded text-xs ${
                        s.running ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'
                    }">${s.running ? 'Running' : 'Offline'}</span>`;
            }
        })
        .catch(err => console.error("Status fetch error:", err));
}

/**
 * Sync Prices
 */
function syncPrices(event) {
    const btn = event.target;
    const html = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';

    fetch(syncUrl, {
        method: "POST",
        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
    })
        .then(res => res.json())
        .then(data => {
            showToast(data.message, data.success ? 'success' : 'error');
            updatePrices();
            updateServiceStatus();
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = html;
        });
}

/**
 * Sync from Binance
 */
function syncCryptos(event) {
    if (!confirm("Import top coins from Binance?")) return;

    const btn = event.target;
    const html = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Syncing...';

    fetch(syncBinanceUrl, {
        method: "POST",
        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
    })
        .then(res => res.json())
        .then(data => {
            showToast(data.message, data.success ? 'success' : 'error');
            setTimeout(() => location.reload(), 1500);
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = html;
        });
}

/**
 * Update row UI
 */
function updateCryptoRow(c) {
    const price = document.querySelector(`.crypto-price[data-crypto-id="${c.id}"]`);
    if (!price) return;

    const newPrice = "$" + parseFloat(c.current_price).toFixed(8);
    if (price.textContent.trim() !== newPrice) {
        price.textContent = newPrice;
        price.classList.add('updating');
        setTimeout(() => price.classList.remove('updating'), 400);
    }

    const change = document.querySelector(`.crypto-change[data-crypto-id="${c.id}"]`);
    if (change) {
        const pct = parseFloat(c.price_change_24h).toFixed(2);
        const up = pct >= 0;
        change.innerHTML = `<span class="${up ? 'text-green-600' : 'text-red-600'} flex items-center gap-1">
            <i class="fas ${up ? 'fa-arrow-up' : 'fa-arrow-down'}"></i> ${pct}%
        </span>`;
    }
}

/** Toast Helper */
function showToast(message, type = "info") {
    const colors = {
        success: "bg-green-600",
        error: "bg-red-600",
        info: "bg-blue-600",
        warning: "bg-yellow-600",
    };

    const box = document.createElement("div");
    box.className = `${colors[type]} text-white px-4 py-2 rounded shadow fixed top-5 right-5 z-50`;
    box.textContent = message;
    document.body.appendChild(box);
    setTimeout(() => box.remove(), 3500);
}

/** Init */
document.addEventListener("DOMContentLoaded", () => {
    updatePrices();
    updateServiceStatus();

    setInterval(updatePrices, 3000);
    setInterval(updateServiceStatus, 10000);
});
</script>
@endsection

