@extends('layouts.app')

@section('title', 'Stock Details - ' . $stock->name)

@section('content')
<div class="container">
    <div class="container-fluid">
        <div class="row">
            <!-- Stock Header -->
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h1 class="mb-1">{{ $stock->name }}</h1>
                                <h3 class="text-muted mb-2">{{ $stock->symbol }}</h3>
                                <span class="badge badge-{{ $stock->sector == 'Technology' ? 'primary' : ($stock->sector == 'Healthcare' ? 'success' : 'info') }} mb-2">
                                    {{ $stock->sector }}
                                </span>
                            </div>
                            <div class="col-md-4 text-md-right">
                                <h2 class="mb-1">${{ number_format($stock->current_price, 2) }}</h2>
                                @php
                                    $priceChange = $stock->current_price - $stock->opening_price;
                                    $changePercent = $stock->opening_price > 0 ? ($priceChange / $stock->opening_price) * 100 : 0;
                                @endphp
                                <h5 class="mb-2 {{ $priceChange >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $priceChange >= 0 ? '+' : '' }}${{ number_format($priceChange, 2) }}
                                    ({{ $priceChange >= 0 ? '+' : '' }}{{ number_format($changePercent, 2) }}%)
                                </h5>
                                <small class="text-muted">Last updated: {{ $stock->updated_at->format('M d, Y H:i') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Trading Panel -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Quick Trade</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-6">
                                <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#buyModal">
                                    <i class="fa fa-arrow-up"></i> Buy
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#sellModal">
                                    <i class="fa fa-arrow-down"></i> Sell
                                </button>
                            </div>
                        </div>

                        <!-- Add to Watchlist -->
                        @if($is_in_watchlist)
                            <form action="{{ route('stocks.remove-from-watchlist', $stock) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-secondary btn-block">
                                    <i class="fa fa-heart"></i> Remove from Watchlist
                                </button>
                            </form>
                        @else
                            <button type="button" class="btn btn-outline-secondary btn-block" data-toggle="modal" data-target="#watchlistModal">
                                <i class="fa fa-heart-o"></i> Add to Watchlist
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Your Position -->
                @if($userPortfolio)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Your Position</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Shares Owned</small>
                                <h6>{{ number_format($userPortfolio->quantity) }}</h6>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Avg. Cost</small>
                                <h6>${{ number_format($userPortfolio->average_price, 2) }}</h6>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Total Value</small>
                                <h6>${{ number_format($userPortfolio->quantity * $stock->current_price, 2) }}</h6>
                            </div>
                            <div class="col-6">
                                @php
                                    $totalGainLoss = ($stock->current_price - $userPortfolio->average_price) * $userPortfolio->quantity;
                                    $gainLossPercent = $userPortfolio->average_price > 0 ? ($totalGainLoss / ($userPortfolio->average_price * $userPortfolio->quantity)) * 100 : 0;
                                @endphp
                                <small class="text-muted">Gain/Loss</small>
                                <h6 class="{{ $totalGainLoss >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $totalGainLoss >= 0 ? '+' : '' }}${{ number_format($totalGainLoss, 2) }}
                                    <br>
                                    <small>({{ $totalGainLoss >= 0 ? '+' : '' }}{{ number_format($gainLossPercent, 2) }}%)</small>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Stock Details & Chart -->
            <div class="col-lg-8">
                <!-- Stock Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Stock Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <small class="text-muted">Market Cap</small>
                                <h6>${{ number_format($stock->market_cap ?? 0, 0) }}</h6>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Volume</small>
                                <h6>{{ number_format($stock->volume ?? 0) }}</h6>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">P/E Ratio</small>
                                <h6>{{ $stock->pe_ratio ?? 'N/A' }}</h6>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <small class="text-muted">Day's Range</small>
                                <h6>${{ number_format($stock->day_low ?? $stock->current_price, 2) }} - ${{ number_format($stock->day_high ?? $stock->current_price, 2) }}</h6>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">52 Week Range</small>
                                <h6>${{ number_format($stock->week_52_low ?? $stock->current_price, 2) }} - ${{ number_format($stock->week_52_high ?? $stock->current_price, 2) }}</h6>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Dividend Yield</small>
                                <h6>{{ $stock->dividend_yield ?? 'N/A' }}%</h6>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Price History Chart -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Price Chart - Last 30 Days</h5>
                            <small class="text-muted" id="dataSourceIndicator">Loading...</small>
                        </div>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary active" data-period="30">30D</button>
                            <button type="button" class="btn btn-outline-primary" data-period="90">90D</button>
                            <button type="button" class="btn btn-outline-primary" data-period="365">1Y</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height: 400px;">
                            <canvas id="priceChart"></canvas>
                        </div>
                        <div id="chartLoading" class="text-center py-5" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading chart...</span>
                            </div>
                            <p class="mt-2 text-muted">Loading price data...</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions -->
                @if($recentTransactions && $recentTransactions->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Your Recent Transactions</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTransactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $transaction->type == 'buy' ? 'success' : 'danger' }}">
                                                {{ ucfirst($transaction->type) }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($transaction->quantity) }}</td>
                                        <td>${{ number_format($transaction->price, 2) }}</td>
                                        <td>${{ number_format($transaction->total_amount, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Buy Modal -->
    <div class="modal fade" id="buyModal" tabindex="-1" role="dialog" aria-labelledby="buyModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="buyModalLabel">Buy {{ $stock->symbol }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('trading.buy') }}" method="POST">
                    @csrf
                    <input type="hidden" name="stock_id" value="{{ $stock->id }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                        </div>
                        <input type="hidden" name="price" value="{{ $stock->current_price }}">
                        <div class="form-group">
                            <label>Current Price</label>
                            <p class="form-control-plaintext">${{ number_format($stock->current_price, 2) }}</p>
                        </div>
                        <div class="form-group">
                            <label>Estimated Total</label>
                            <p class="form-control-plaintext" id="estimatedTotal">$0.00</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Buy Shares</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sell Modal -->
    <div class="modal fade" id="sellModal" tabindex="-1" role="dialog" aria-labelledby="sellModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sellModalLabel">Sell {{ $stock->symbol }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('trading.sell') }}" method="POST">
                    @csrf
                    <input type="hidden" name="stock_id" value="{{ $stock->id }}">
                    <div class="modal-body">
                        @if($userPortfolio && $userPortfolio->quantity > 0)
                        <div class="alert alert-info">
                            You currently own {{ number_format($userPortfolio->quantity) }} shares
                        </div>
                        <div class="form-group">
                            <label for="sell_quantity">Quantity to Sell</label>
                            <input type="number" class="form-control" id="sell_quantity" name="quantity" 
                                min="1" max="{{ $userPortfolio->quantity }}" required>
                        </div>
                        <input type="hidden" name="price" value="{{ $stock->current_price }}">
                        <div class="form-group">
                            <label>Current Price</label>
                            <p class="form-control-plaintext">${{ number_format($stock->current_price, 2) }}</p>
                        </div>
                        <div class="form-group">
                            <label>Estimated Total</label>
                            <p class="form-control-plaintext" id="estimatedSellTotal">$0.00</p>
                        </div>
                        @else
                        <div class="alert alert-warning">
                            You don't own any shares of {{ $stock->symbol }} to sell.
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        @if($userPortfolio && $userPortfolio->quantity > 0)
                        <button type="submit" class="btn btn-danger">Sell Shares</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Watchlist Modal -->
    <div class="modal fade" id="watchlistModal" tabindex="-1" role="dialog" aria-labelledby="watchlistModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="watchlistModalLabel">Add {{ $stock->symbol }} to Watchlist</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('stocks.add-to-watchlist', $stock) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="target_price">Target Price (Optional)</label>
                            <input type="number" class="form-control" id="target_price" name="target_price" step="0.01" min="0">
                            <small class="form-text text-muted">You'll be notified when the stock reaches this price</small>
                        </div>
                        <div class="form-group">
                            <label for="notes">Notes (Optional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" maxlength="500"></textarea>
                            <small class="form-text text-muted">Personal notes about this stock</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add to Watchlist</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const currentPrice = {{ $stock->current_price }};
    let priceChart = null;
    
    // Initialize the price chart
    initializePriceChart();
    
    // Chart period selector
    document.querySelectorAll('.btn-group [data-period]').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.btn-group [data-period]').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const period = this.getAttribute('data-period');
            updateChartTitle(period);
            loadChartData(period);
        });
    });
    
    function initializePriceChart() {
        const ctx = document.getElementById('priceChart').getContext('2d');
        
        priceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Price ($)',
                        data: [],
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Volume',
                        data: [],
                        type: 'bar',
                        backgroundColor: 'rgba(118, 75, 162, 0.3)',
                        borderColor: 'rgba(118, 75, 162, 0.5)',
                        borderWidth: 1,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#e0e0e0',
                            filter: function(item, chart) {
                                // Only show price legend
                                return item.text === 'Price ($)';
                            }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(30, 30, 30, 0.9)',
                        titleColor: '#e0e0e0',
                        bodyColor: '#e0e0e0',
                        borderColor: '#3c3c3c',
                        borderWidth: 1,
                        callbacks: {
                            title: function(context) {
                                return 'Date: ' + context[0].label;
                            },
                            label: function(context) {
                                if (context.datasetIndex === 0) {
                                    return 'Price: $' + context.parsed.y.toFixed(2);
                                } else {
                                    return 'Volume: ' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Date',
                            color: '#e0e0e0'
                        },
                        ticks: {
                            color: '#e0e0e0',
                            maxTicksLimit: 10
                        },
                        grid: {
                            color: '#3c3c3c'
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Price ($)',
                            color: '#e0e0e0'
                        },
                        ticks: {
                            color: '#e0e0e0',
                            callback: function(value) {
                                return '$' + value.toFixed(2);
                            }
                        },
                        grid: {
                            color: '#3c3c3c'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: false,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                }
            }
        });
        
        // Load initial chart data
        loadChartData(30);
    }
    
    function loadChartData(period = 30) {
        const chartLoading = document.getElementById('chartLoading');
        const priceChartElement = document.getElementById('priceChart');
        
        chartLoading.style.display = 'block';
        priceChartElement.style.display = 'none';
        
        // Fetch chart data from the backend
        fetch(`{{ route('stocks.chart-data', $stock) }}?period=${period}`)
            .then(response => response.json())
            .then(data => {
                updateChart(data);
            })
            .catch(error => {
                console.error('Error loading chart data:', error);
                showChartError();
            })
            .finally(() => {
                chartLoading.style.display = 'none';
                priceChartElement.style.display = 'block';
            });
    }
    
    function updateChart(data) {
        if (priceChart) {
            priceChart.data.labels = data.labels;
            priceChart.data.datasets[0].data = data.prices;
            priceChart.data.datasets[0].label = `${data.symbol} Price ($)`;
            priceChart.data.datasets[1].data = data.volumes;
            priceChart.update();
            
            // Update data source indicator
            const indicator = document.getElementById('dataSourceIndicator');
            if (data.data_source === 'real') {
                indicator.innerHTML = '<i class="fas fa-database text-success"></i> Historical Data';
                indicator.className = 'text-success';
            } else {
                indicator.innerHTML = '<i class="fas fa-chart-line text-warning"></i> Sample Data';
                indicator.className = 'text-warning';
            }
        }
    }
    
    function updateChartTitle(period) {
        const title = document.querySelector('.card-title');
        const periodText = period == 30 ? 'Last 30 Days' : 
                          period == 90 ? 'Last 90 Days' : 
                          'Last Year';
        title.textContent = `Price Chart - ${periodText}`;
    }
    
    function showChartError() {
        const ctx = document.getElementById('priceChart').getContext('2d');
        ctx.fillStyle = '#dc3545';
        ctx.font = '16px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('Error loading chart data', ctx.canvas.width / 2, ctx.canvas.height / 2);
    }
    
    // Update estimated total for buying
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        quantityInput.addEventListener('input', function() {
            const quantity = parseFloat(this.value) || 0;
            const total = quantity * currentPrice;
            const commission = total * 0.001; // 0.1% commission
            const net = total + commission;
            const estimatedTotalElement = document.getElementById('estimatedTotal');
            if (estimatedTotalElement) {
                estimatedTotalElement.textContent = '$' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            }
        });
    }
    
    // Update estimated total for selling
    const sellQuantityInput = document.getElementById('sell_quantity');
    if (sellQuantityInput) {
        sellQuantityInput.addEventListener('input', function() {
            const quantity = parseFloat(this.value) || 0;
            const total = quantity * currentPrice;
            const commission = total * 0.001; // 0.1% commission
            const net = total - commission;
            const estimatedSellTotalElement = document.getElementById('estimatedSellTotal');
            if (estimatedSellTotalElement) {
                estimatedSellTotalElement.textContent = '$' + net.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            }
        });
    }
});
</script>
@endpush