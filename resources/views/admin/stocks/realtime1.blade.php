@extends('admin.layouts.app')

@section('title', 'Real-Time Stock Market')

@section('content')
    <style>
        .price-up {
            animation: flash-green 0.5s ease-in-out;
        }

        .price-down {
            animation: flash-red 0.5s ease-in-out;
        }

        @keyframes flash-green {
            0% {
                background-color: rgba(34, 197, 94, 0.3);
            }

            100% {
                background-color: transparent;
            }
        }

        @keyframes flash-red {
            0% {
                background-color: rgba(239, 68, 68, 0.3);
            }

            100% {
                background-color: transparent;
            }
        }

        .updating {
            opacity: 0.6;
            pointer-events: none;
        }
    </style>

    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold">Real-Time Stock Market</h1>
                <p class="text-gray-500">Live updates every 30 seconds - Data saved to database</p>
            </div>
            <div class="flex gap-2">
                <button onclick="syncAllStocks()" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                    <i class="fas fa-database mr-1"></i> Sync ALL to Database
                </button>
                <button onclick="refreshFromDatabase()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    <i class="fas fa-sync-alt mr-1"></i> Refresh
                </button>
            </div>
        </div>

        <!-- Status Message -->
        <div id="statusMsg" class="hidden mb-4 p-3 rounded"></div>

        <!-- Market Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-500 text-white p-4 rounded-lg">
                <div class="text-sm opacity-90">Total Stocks</div>
                <div class="text-2xl font-bold" id="totalStocks">0</div>
            </div>
            <div class="bg-green-500 text-white p-4 rounded-lg">
                <div class="text-sm opacity-90">Total Volume</div>
                <div class="text-2xl font-bold" id="totalVolume">0</div>
            </div>
            <div class="bg-purple-500 text-white p-4 rounded-lg">
                <div class="text-sm opacity-90">Market Cap</div>
                <div class="text-2xl font-bold" id="totalMarketCap">$0</div>
            </div>
            <div class="bg-orange-500 text-white p-4 rounded-lg">
                <div class="text-sm opacity-90">Last Update</div>
                <div class="text-2xl font-bold" id="lastUpdateTime">--:--:--</div>
            </div>
        </div>

        <!-- Controls -->
        <div class="bg-white p-4 rounded-lg shadow mb-4">
            <div class="flex flex-wrap gap-4">
                <input type="text" id="search" placeholder="Search symbol or name..."
                    class="border px-3 py-2 rounded flex-1 min-w-[200px]">
                <select id="sectorFilter" class="border px-3 py-2 rounded">
                    <option value="">All Sectors</option>
                    @foreach($sectors ?? ['Technology', 'Financial', 'Healthcare', 'Energy', 'Consumer'] as $sector)
                        <option value="{{ $sector }}">{{ $sector }}</option>
                    @endforeach
                </select>
                <button onclick="toggleAutoUpdate()" id="toggleBtn"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    <i class="fas fa-pause"></i> Pause Updates
                </button>
            </div>
        </div>

        <!-- Stocks Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Symbol</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Name</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold">Price</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold">Change</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold">Change %</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold">Day Range</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold">Volume</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody id="stocksTable">
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500">Loading stocks from database...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        let autoUpdate = true;
        let updateInterval;
        let stocksData = [];

        // Load stocks from your API endpoint (gets REAL data from database)
        async function loadStocksFromDatabase() {
            try {
                showStatus('Loading stocks from database...', 'info');

                const response = await fetch('{{ route("admin.stocks.live-data") }}');
                const result = await response.json();

                if (result.stocks && result.stocks.length > 0) {
                    stocksData = result.stocks;
                    renderTable();
                    updateSummary();
                    updateLastUpdateTime(result.timestamp);
                    showStatus(`✅ Loaded ${stocksData.length} stocks from database`, 'success');
                } else {
                    showStatus('⚠️ No stocks found in database. Please add stocks first.', 'error');
                    // Demo data to show interface works
                    loadDemoData();
                }
            } catch (error) {
                console.error('Error loading stocks:', error);
                showStatus('❌ Error loading stocks. Using demo data.', 'error');
                loadDemoData();
            }
        }

        // Demo data for testing (shows interface works)
        function loadDemoData() {
            stocksData = [
                { id: 1, symbol: 'AAPL', name: 'Apple Inc.', current_price: 175.34, volume: 50345000, market_cap: 2750000000000, high_price: 178.50, low_price: 174.20, sector: 'Technology', change: 2.45, change_percentage: 1.42, last_updated: new Date().toISOString() },
                { id: 2, symbol: 'GOOGL', name: 'Alphabet Inc.', current_price: 138.21, volume: 20123000, market_cap: 1750000000000, high_price: 140.00, low_price: 137.50, sector: 'Technology', change: -1.23, change_percentage: -0.88, last_updated: new Date().toISOString() },
                { id: 3, symbol: 'MSFT', name: 'Microsoft Corp.', current_price: 378.45, volume: 25467000, market_cap: 2810000000000, high_price: 382.00, low_price: 376.50, sector: 'Technology', change: 5.67, change_percentage: 1.52, last_updated: new Date().toISOString() }
            ];
            renderTable();
            updateSummary();
            showStatus('📊 Using demo data - Add real stocks to database for live updates', 'info');
        }

        // Render the table
        function renderTable() {
            const tbody = document.getElementById('stocksTable');
            const searchTerm = document.getElementById('search')?.value.toLowerCase() || '';
            const sectorFilter = document.getElementById('sectorFilter')?.value || '';

            let filtered = stocksData.filter(s =>
                (s.symbol?.toLowerCase().includes(searchTerm) || s.name?.toLowerCase().includes(searchTerm)) &&
                (!sectorFilter || s.sector === sectorFilter)
            );

            if (filtered.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center py-8 text-gray-500">No stocks found</td></tr>';
                return;
            }

            tbody.innerHTML = filtered.map(stock => {
                const change = stock.change || (stock.current_price - (stock.opening_price || stock.current_price));
                const changePercent = stock.change_percentage || ((change / (stock.opening_price || stock.current_price)) * 100);
                const isPositive = change >= 0;

                return `
                <tr class="border-b hover:bg-gray-50 transition" id="stock-row-${stock.id}">
                    <td class="px-4 py-3">
                        <div class="font-bold text-gray-900">${stock.symbol}</div>
                        <div class="text-xs text-gray-500">${stock.sector || '-'}</div>
                    </td>
                    <td class="px-4 py-3 text-gray-600">${stock.name}</td>
                    <td class="px-4 py-3 text-right">
                        <span class="stock-price-${stock.id} font-semibold text-lg">$${parseFloat(stock.current_price).toFixed(2)}</span>
                        <div class="text-xs text-gray-400">${stock.last_updated ? new Date(stock.last_updated).toLocaleTimeString() : '-'}</div>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <span class="stock-change-${stock.id} ${isPositive ? 'text-green-600' : 'text-red-600'} font-semibold">
                            ${isPositive ? '▲' : '▼'} $${Math.abs(change).toFixed(2)}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <span class="stock-change-percent-${stock.id} ${isPositive ? 'text-green-600' : 'text-red-600'} font-semibold">
                            ${isPositive ? '+' : ''}${changePercent.toFixed(2)}%
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right text-sm">
                        $${parseFloat(stock.low_price || stock.current_price).toFixed(2)} - $${parseFloat(stock.high_price || stock.current_price).toFixed(2)}
                    </td>
                    <td class="px-4 py-3 text-right">${parseInt(stock.volume || 0).toLocaleString()}</td>
                    <td class="px-4 py-3 text-center">
                        <button onclick="syncSingleStock(${stock.id}, '${stock.symbol}')" 
                                class="sync-btn-${stock.id} bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                            <i class="fas fa-sync-alt"></i> Sync
                        </button>
                    </td>
                </tr>
            `;
            }).join('');
        }

        // Sync single stock - UPDATES DATABASE with real price
        async function syncSingleStock(stockId, symbol) {
            const btn = document.querySelector(`.sync-btn-${stockId}`);
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
            btn.disabled = true;

            try {
                showStatus(`🔄 Fetching real price for ${symbol}...`, 'info');

                const response = await fetch(`/admin/stocks/${stockId}/update-price`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const result = await response.json();

                if (result.success && result.data) {
                    // Update the stock in our local array
                    const stockIndex = stocksData.findIndex(s => s.id == stockId);
                    if (stockIndex !== -1) {
                        stocksData[stockIndex].current_price = result.data.new_price;
                        stocksData[stockIndex].last_updated = result.data.last_updated;

                        // Update UI with animation
                        const priceSpan = document.querySelector(`.stock-price-${stockId}`);
                        const oldPrice = result.data.old_price;

                        priceSpan.innerHTML = `$${result.data.new_price.toFixed(2)}<div class="text-xs text-gray-400">${new Date().toLocaleTimeString()}</div>`;

                        // Add animation based on price movement
                        const row = document.getElementById(`stock-row-${stockId}`);
                        if (result.data.new_price > oldPrice) {
                            row.classList.add('price-up');
                            setTimeout(() => row.classList.remove('price-up'), 500);
                        } else if (result.data.new_price < oldPrice) {
                            row.classList.add('price-down');
                            setTimeout(() => row.classList.remove('price-down'), 500);
                        }

                        // Update change displays
                        const changeSpan = document.querySelector(`.stock-change-${stockId}`);
                        const changePercentSpan = document.querySelector(`.stock-change-percent-${stockId}`);
                        const isPositive = result.data.change >= 0;

                        changeSpan.className = `stock-change-${stockId} ${isPositive ? 'text-green-600' : 'text-red-600'} font-semibold`;
                        changeSpan.innerHTML = `${isPositive ? '▲' : '▼'} $${Math.abs(result.data.change).toFixed(2)}`;

                        changePercentSpan.className = `stock-change-percent-${stockId} ${isPositive ? 'text-green-600' : 'text-red-600'} font-semibold`;
                        changePercentSpan.innerHTML = `${isPositive ? '+' : ''}${result.data.change_percentage.toFixed(2)}%`;
                    }

                    showStatus(`✅ ${symbol} updated! New price: $${result.data.new_price.toFixed(2)} (Saved to database)`, 'success');
                } else {
                    showStatus(`❌ Failed to update ${symbol}: ${result.message || 'API error'}`, 'error');
                }
            } catch (error) {
                console.error('Sync error:', error);
                showStatus(`❌ Error updating ${symbol}. Check API configuration.`, 'error');
            } finally {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }
        }

        // Sync all stocks - BULK DATABASE UPDATE
        async function syncAllStocks() {
            if (!confirm('⚠️ This will update ALL stocks with real prices from API. This may take a minute. Continue?')) return;

            showStatus('🔄 Updating all stocks with real prices...', 'info');

            for (const stock of stocksData) {
                await syncSingleStock(stock.id, stock.symbol);
                await new Promise(resolve => setTimeout(resolve, 2000)); // Rate limiting
            }

            showStatus('✅ All stocks updated successfully!', 'success');
            await loadStocksFromDatabase(); // Reload fresh data
        }

        // Auto-refresh - gets latest data from database
        async function autoRefresh() {
            if (!autoUpdate) return;

            try {
                showStatus('🔄 Auto-refreshing data from database...', 'info');
                const response = await fetch('{{ route("admin.stocks.live-data") }}');
                const result = await response.json();

                if (result.stocks) {
                    stocksData = result.stocks;
                    renderTable();
                    updateSummary();
                    updateLastUpdateTime(result.timestamp);
                    showStatus(`✅ Auto-refreshed ${stocksData.length} stocks from database`, 'success');
                }
            } catch (error) {
                console.error('Auto-refresh error:', error);
            }
        }

        // Refresh from database (manual)
        async function refreshFromDatabase() {
            await loadStocksFromDatabase();
        }

        // Update summary stats
        function updateSummary() {
            const totalStocks = stocksData.length;
            const totalVolume = stocksData.reduce((sum, s) => sum + (parseInt(s.volume) || 0), 0);
            const totalMarketCap = stocksData.reduce((sum, s) => sum + (parseFloat(s.market_cap) || 0), 0);

            document.getElementById('totalStocks').innerHTML = totalStocks;
            document.getElementById('totalVolume').innerHTML = totalVolume.toLocaleString();
            document.getElementById('totalMarketCap').innerHTML = '$' + (totalMarketCap / 1000000000).toFixed(2) + 'B';
        }

        // Update last update time
        function updateLastUpdateTime(timestamp) {
            const time = timestamp ? new Date(timestamp) : new Date();
            document.getElementById('lastUpdateTime').innerHTML = time.toLocaleTimeString();
        }

        // Show status message
        function showStatus(message, type) {
            const msgDiv = document.getElementById('statusMsg');
            msgDiv.className = `mb-4 p-3 rounded ${type === 'success' ? 'bg-green-100 text-green-700 border border-green-200' : type === 'error' ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-blue-100 text-blue-700 border border-blue-200'}`;
            msgDiv.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'} mr-2"></i> ${message}`;
            msgDiv.classList.remove('hidden');

            setTimeout(() => {
                msgDiv.classList.add('hidden');
            }, 3000);
        }

        // Toggle auto update
        function toggleAutoUpdate() {
            autoUpdate = !autoUpdate;
            const btn = document.getElementById('toggleBtn');

            if (autoUpdate) {
                if (updateInterval) clearInterval(updateInterval);
                updateInterval = setInterval(autoRefresh, 30000);
                btn.innerHTML = '<i class="fas fa-pause"></i> Pause Updates';
                btn.classList.remove('bg-yellow-600');
                btn.classList.add('bg-green-600');
                showStatus('Auto updates resumed (every 30 sec)', 'success');
            } else {
                if (updateInterval) clearInterval(updateInterval);
                btn.innerHTML = '<i class="fas fa-play"></i> Resume Updates';
                btn.classList.remove('bg-green-600');
                btn.classList.add('bg-yellow-600');
                showStatus('Auto updates paused', 'info');
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            loadStocksFromDatabase();
            updateInterval = setInterval(autoRefresh, 30000);
            document.getElementById('search')?.addEventListener('input', renderTable);
            document.getElementById('sectorFilter')?.addEventListener('change', renderTable);
        });
    </script>
@endsection