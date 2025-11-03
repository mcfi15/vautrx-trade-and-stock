@extends('admin.layouts.app')

@section('title', 'Cryptocurrencies Management')

@section('content')
<div class="px-6 py-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-coins text-indigo-500"></i>
            Cryptocurrencies Management
        </h1>
        <div class="flex flex-wrap gap-2 mt-3 sm:mt-0">
            <a href="{{ route('admin.cryptocurrencies.create') }}" 
               class="inline-flex items-center gap-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-3 py-2 rounded-lg shadow">
                <i class="fas fa-plus"></i> Add Cryptocurrency
            </a>
            <button onclick="syncPrices(event)" id="sync-prices-button"
                class="inline-flex items-center gap-1 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-3 py-2 rounded-lg shadow">
                <i class="fas fa-dollar-sign"></i> Sync Prices
            </button>
            <button onclick="syncCryptos(event)" id="sync-button"
                class="inline-flex items-center gap-1 bg-sky-600 hover:bg-sky-700 text-white text-sm font-medium px-3 py-2 rounded-lg shadow">
                <i class="fas fa-sync-alt"></i> Sync from Binance
            </button>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-start gap-2">
            <i class="fas fa-check-circle mt-1"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-start gap-2">
            <i class="fas fa-exclamation-circle mt-1"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white border-l-4 border-indigo-500 rounded-lg shadow p-5">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xs uppercase text-indigo-600 font-bold">Total Cryptocurrencies</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $cryptocurrencies->total() }}</p>
                </div>
                <i class="fas fa-coins text-gray-400 text-3xl"></i>
            </div>
        </div>

        <div class="bg-white border-l-4 border-green-500 rounded-lg shadow p-5">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xs uppercase text-green-600 font-bold">Real-time Tracking</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $cryptocurrencies->where('enable_realtime', true)->count() }}</p>
                </div>
                <i class="fas fa-broadcast-tower text-gray-400 text-3xl"></i>
            </div>
        </div>

        <div class="bg-white border-l-4 border-sky-500 rounded-lg shadow p-5">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xs uppercase text-sky-600 font-bold">Live Connections</h3>
                    <p class="text-2xl font-bold text-gray-800" id="live-connections">-</p>
                </div>
                <i class="fas fa-plug text-gray-400 text-3xl"></i>
            </div>
        </div>

        <div class="bg-white border-l-4 border-yellow-500 rounded-lg shadow p-5">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xs uppercase text-yellow-600 font-bold">Service Status</h3>
                    <p class="text-2xl font-bold" id="service-status">
                        <span class="px-2 py-1 rounded-full bg-gray-200 text-gray-700 text-sm">Checking...</span>
                    </p>
                </div>
                <i class="fas fa-server text-gray-400 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="border-b border-gray-200 px-4 py-3">
            <h6 class="text-lg font-semibold text-gray-800">Cryptocurrency List</h6>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Symbol</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Name</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Current Price</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">24h Change</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">24h Volume</th>
                        <th class="px-4 py-2 text-center text-sm font-medium text-gray-600">Real-time</th>
                        <th class="px-4 py-2 text-center text-sm font-medium text-gray-600">Active</th>
                        <th class="px-4 py-2 text-center text-sm font-medium text-gray-600">Tradable</th>
                        <th class="px-4 py-2 text-center text-sm font-medium text-gray-600">Last Updated</th>
                        <th class="px-4 py-2 text-center text-sm font-medium text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($cryptocurrencies as $crypto)
                    <tr id="crypto-row-{{ $crypto->id }}" class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold text-gray-800 flex items-center gap-2">
                            {{ $crypto->symbol }}
                            @if($crypto->logo_url)
                                <img src="{{ $crypto->logo_url }}" alt="{{ $crypto->symbol }}" class="w-5 h-5 rounded-full">
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $crypto->name }}</td>
                        <td class="px-4 py-3 crypto-price" data-crypto-id="{{ $crypto->id }}">
                            ${{ number_format($crypto->current_price, 8) }}
                        </td>
                        <td class="px-4 py-3 crypto-change" data-crypto-id="{{ $crypto->id }}">
                            @if($crypto->price_change_24h >= 0)
                                <span class="text-green-600"><i class="fas fa-arrow-up"></i> {{ number_format($crypto->price_change_24h, 2) }}%</span>
                            @else
                                <span class="text-red-600"><i class="fas fa-arrow-down"></i> {{ number_format($crypto->price_change_24h, 2) }}%</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">${{ number_format($crypto->volume_24h, 0) }}</td>
                        <td class="px-4 py-3 text-center">
                            <input type="checkbox" class="peer hidden realtime-toggle" id="realtime-{{ $crypto->id }}" data-crypto-id="{{ $crypto->id }}" {{ $crypto->enable_realtime ? 'checked' : '' }}>
                            <label for="realtime-{{ $crypto->id }}" class="cursor-pointer w-10 h-5 flex items-center bg-gray-300 rounded-full peer-checked:bg-emerald-500 relative after:content-[''] after:absolute after:left-0.5 after:top-0.5 after:bg-white after:h-4 after:w-4 after:rounded-full after:transition-transform peer-checked:after:translate-x-5"></label>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <input type="checkbox" class="peer hidden status-toggle" id="status-{{ $crypto->id }}" data-crypto-id="{{ $crypto->id }}" {{ $crypto->is_active ? 'checked' : '' }}>
                            <label for="status-{{ $crypto->id }}" class="cursor-pointer w-10 h-5 flex items-center bg-gray-300 rounded-full peer-checked:bg-indigo-500 relative after:content-[''] after:absolute after:left-0.5 after:top-0.5 after:bg-white after:h-4 after:w-4 after:rounded-full after:transition-transform peer-checked:after:translate-x-5"></label>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $crypto->is_tradable ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $crypto->is_tradable ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500 text-center">
                            {{ $crypto->price_updated_at ? $crypto->price_updated_at->diffForHumans() : 'Never' }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('admin.cryptocurrencies.show', $crypto->id) }}" 
                               class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white rounded-md p-2 text-xs">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.cryptocurrencies.edit', $crypto->id) }}" 
                               class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white rounded-md p-2 text-xs">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.cryptocurrencies.destroy', $crypto->id) }}" method="POST" class="inline"
                                onsubmit="return confirm('Are you sure you want to delete {{ $crypto->symbol }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center bg-red-600 hover:bg-red-700 text-white rounded-md p-2 text-xs ml-1">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-gray-500 py-6">
                            <i class="fas fa-coins text-4xl mb-2"></i>
                            <p>No cryptocurrencies found. Click “Sync from Binance” to import.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
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
    50% { background-color: #fff3cd; }
}

.custom-switch .custom-control-input:checked ~ .custom-control-label::before {
    background-color: #28a745;
    border-color: #28a745;
}
</style>
@endsection


@section('scripts')
<script>
// Real-time price updates
let priceUpdateInterval;

function updatePrices() {
    fetch('/api/v1/admin/crypto')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.data.forEach(crypto => {
                    updateCryptoRow(crypto);
                });
            } else {
                console.error('Failed to fetch prices:', data.message);
                showToast('Failed to fetch price updates: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error fetching prices:', error);
            showToast('Error fetching price updates. Check console for details.', 'error');
        });
}

function updateCryptoRow(crypto) {
    // Update price - prefer cached price if available
    const priceCell = document.querySelector(`.crypto-price[data-crypto-id="${crypto.id}"]`);
    if (priceCell) {
        const newPrice = '$' + parseFloat(crypto.cached_price || crypto.current_price || 0).toFixed(8);
        if (priceCell.textContent.trim() !== newPrice) {
            priceCell.textContent = newPrice;
            priceCell.classList.add('updating');
            setTimeout(() => priceCell.classList.remove('updating'), 500);
        }
    }

    // Update change
    const changeCell = document.querySelector(`.crypto-change[data-crypto-id="${crypto.id}"]`);
    if (changeCell) {
        const change = parseFloat(crypto.price_change_24h || 0);
        const icon = change >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
        const colorClass = change >= 0 ? 'text-success' : 'text-danger';
        changeCell.innerHTML = `<span class="${colorClass}"><i class="fas ${icon}"></i> ${change.toFixed(2)}%</span>`;
    }

    // Update price updated time
    const priceUpdatedCell = document.querySelector(`#crypto-row-${crypto.id} td:nth-child(9) small`);
    if (priceUpdatedCell && crypto.price_updated_at) {
        const updatedAt = new Date(crypto.price_updated_at);
        const timeAgo = getTimeAgo(updatedAt);
        priceUpdatedCell.textContent = timeAgo;
    }
}

// Helper function to format time ago
function getTimeAgo(date) {
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMins / 60);
    const diffDays = Math.floor(diffHours / 24);

    if (diffMins < 1) return 'Just now';
    if (diffMins < 60) return `${diffMins}m ago`;
    if (diffHours < 24) return `${diffHours}h ago`;
    return `${diffDays}d ago`;
}

function updateServiceStatus() {
    fetch('/api/v1/admin/crypto/status')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const status = data.data;
                document.getElementById('live-connections').textContent = status.live_connections || 0;
                
                const statusBadge = document.getElementById('service-status');
                if (status.service_running) {
                    statusBadge.innerHTML = '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Running</span>';
                } else {
                    statusBadge.innerHTML = '<span class="badge badge-danger"><i class="fas fa-times-circle"></i> Offline</span>';
                }
                
                document.getElementById('realtime-count').textContent = status.tracked_cryptocurrencies || 0;
                document.getElementById('total-cryptos').textContent = status.total_cryptocurrencies || 0;
            } else {
                console.error('Failed to fetch status:', data.message);
                document.getElementById('service-status').innerHTML = '<span class="badge badge-warning">Error</span>';
                showToast('Failed to fetch service status: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error fetching status:', error);
            document.getElementById('service-status').innerHTML = '<span class="badge badge-warning">Error</span>';
            showToast('Error fetching service status. Check console for details.', 'error');
        });
}

// Toggle real-time tracking
document.addEventListener('DOMContentLoaded', function() {
    // Start price updates
    updatePrices();
    updateServiceStatus();
    priceUpdateInterval = setInterval(updatePrices, 3000); // Update every 3 seconds
    setInterval(updateServiceStatus, 10000); // Update status every 10 seconds

    // Real-time toggle switches
    document.querySelectorAll('.realtime-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const cryptoId = this.dataset.cryptoId;
            const isEnabled = this.checked;
            
            fetch(`/admin/cryptocurrencies/${cryptoId}/toggle-realtime`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ enable_realtime: isEnabled })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    updateServiceStatus();
                } else {
                    this.checked = !isEnabled;
                    showToast('Failed to toggle real-time tracking', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.checked = !isEnabled;
                showToast('Error toggling real-time tracking', 'error');
            });
        });
    });

    // Status toggle switches
    document.querySelectorAll('.status-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const cryptoId = this.dataset.cryptoId;
            const isActive = this.checked;
            
            fetch(`/admin/cryptocurrencies/${cryptoId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ is_active: isActive })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    // Update the badge text in the label
                    const label = document.querySelector(`label[for="status-${cryptoId}"] .badge`);
                    if (label) {
                        label.textContent = isActive ? 'Active' : 'Inactive';
                        label.className = `badge badge-${isActive ? 'success' : 'secondary'} ml-2`;
                    }
                } else {
                    this.checked = !isActive;
                    showToast('Failed to toggle status', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.checked = !isActive;
                showToast('Error toggling status', 'error');
            });
        });
    });
});

function syncCryptos(event) {
    if (!confirm('Sync cryptocurrencies from Binance?\n\nThis will import the top 50 cryptocurrencies by trading volume.\nIt may take 10-30 seconds.')) {
        return;
    }
    
    // Show loading state
    const syncButton = event.target;
    const originalText = syncButton.innerHTML;
    syncButton.disabled = true;
    syncButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Syncing...';
    
    showToast('Syncing cryptocurrencies from Binance... Please wait.', 'info');
    
    fetch('{{ route("admin.cryptocurrencies.sync-from-binance") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ limit: 50 })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            // Reload page after 2 seconds to show new cryptocurrencies
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            showToast(data.message || 'Failed to sync cryptocurrencies', 'error');
            syncButton.disabled = false;
            syncButton.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error syncing cryptocurrencies. Check console for details.', 'error');
        syncButton.disabled = false;
        syncButton.innerHTML = originalText;
    });
}

function syncPrices(event) {
    // Show loading state
    const syncButton = event.target;
    const originalText = syncButton.innerHTML;
    syncButton.disabled = true;
    syncButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Syncing...';
    
    showToast('Syncing prices from CoinGecko... Please wait.', 'info');
    
    // Use the new batch update endpoint
    fetch('/api/v1/admin/crypto/batch-update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            // Update prices in real-time without page reload
            updatePrices();
            updateServiceStatus();
            
            // Also sync through admin route for consistency
            return fetch('{{ route("admin.cryptocurrencies.sync-prices") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
        } else {
            showToast(data.message || 'Failed to sync prices', 'error');
            syncButton.disabled = false;
            syncButton.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error syncing prices. Check console for details.', 'error');
        syncButton.disabled = false;
        syncButton.innerHTML = originalText;
    })
    .finally(() => {
        // Reset button after a delay
        setTimeout(() => {
            syncButton.disabled = false;
            syncButton.innerHTML = originalText;
        }, 2000);
    });
}

function showToast(message, type = 'info') {
    const alertClass = {
        'success': 'alert-success',
        'error': 'alert-danger',
        'info': 'alert-info',
        'warning': 'alert-warning'
    }[type] || 'alert-info';
    
    const toast = document.createElement('div');
    toast.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 5000);
}
</script>
@endsection
