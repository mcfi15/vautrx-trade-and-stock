@extends('admin.layouts.app')

@section('title', 'Cryptocurrencies Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Cryptocurrencies Management</h1>
        <div>
            <a href="{{ route('admin.cryptocurrencies.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Cryptocurrency
            </a>
            <button onclick="syncPrices(event)" class="btn btn-success btn-sm" id="sync-prices-button">
                <i class="fas fa-dollar-sign"></i> Sync Prices
            </button>
            <button onclick="syncCryptos(event)" class="btn btn-info btn-sm" id="sync-button">
                <i class="fas fa-sync-alt"></i> Sync from Binance
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <!-- WebSocket Status Card -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Cryptocurrencies</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-cryptos">{{ $cryptocurrencies->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-coins fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Real-time Tracking</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="realtime-count">{{ $cryptocurrencies->where('enable_realtime', true)->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-broadcast-tower fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Live Connections</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="live-connections">-</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-plug fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Service Status</div>
                            <div class="h5 mb-0 font-weight-bold" id="service-status">
                                <span class="badge badge-secondary">Checking...</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-server fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cryptocurrencies Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Cryptocurrency List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="cryptoTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Symbol</th>
                            <th>Name</th>
                            <th>Current Price</th>
                            <th>24h Change</th>
                            <th>24h Volume</th>
                            <th>Real-time</th>
                            <th>Active</th>
                            <th>Tradable</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cryptocurrencies as $crypto)
                        <tr id="crypto-row-{{ $crypto->id }}">
                            <td>
                                <strong>{{ $crypto->symbol }}</strong>
                                @if($crypto->logo_url)
                                    <img src="{{ $crypto->logo_url }}" alt="{{ $crypto->symbol }}" width="20" class="ml-2">
                                @endif
                            </td>
                            <td>{{ $crypto->name }}</td>
                            <td class="crypto-price" data-crypto-id="{{ $crypto->id }}">
                                ${{ number_format($crypto->current_price, 8) }}
                            </td>
                            <td class="crypto-change" data-crypto-id="{{ $crypto->id }}">
                                @if($crypto->price_change_24h >= 0)
                                    <span class="text-success">
                                        <i class="fas fa-arrow-up"></i> {{ number_format($crypto->price_change_24h, 2) }}%
                                    </span>
                                @else
                                    <span class="text-danger">
                                        <i class="fas fa-arrow-down"></i> {{ number_format($crypto->price_change_24h, 2) }}%
                                    </span>
                                @endif
                            </td>
                            <td>${{ number_format($crypto->volume_24h, 0) }}</td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" 
                                           class="custom-control-input realtime-toggle" 
                                           id="realtime-{{ $crypto->id }}" 
                                           data-crypto-id="{{ $crypto->id }}"
                                           {{ $crypto->enable_realtime ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="realtime-{{ $crypto->id }}"></label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" 
                                           class="custom-control-input status-toggle" 
                                           id="status-{{ $crypto->id }}" 
                                           data-crypto-id="{{ $crypto->id }}"
                                           {{ $crypto->is_active ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="status-{{ $crypto->id }}">
                                        <span class="badge badge-{{ $crypto->is_active ? 'success' : 'secondary' }} ml-2">
                                            {{ $crypto->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-{{ $crypto->is_tradable ? 'success' : 'secondary' }}">
                                    {{ $crypto->is_tradable ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>
                                <small>{{ $crypto->price_updated_at ? $crypto->price_updated_at->diffForHumans() : 'Never' }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.cryptocurrencies.edit', $crypto->id) }}" 
                                   class="btn btn-sm btn-primary" 
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.cryptocurrencies.destroy', $crypto->id) }}" 
                                      method="POST" 
                                      class="d-inline" 
                                      onsubmit="return confirm('Are you sure you want to delete {{ $crypto->symbol }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">
                                <div class="py-4">
                                    <i class="fas fa-coins fa-3x mb-3"></i>
                                    <p>No cryptocurrencies found. Click "Sync from Binance" to import.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $cryptocurrencies->links() }}
            </div>
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
