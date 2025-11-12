@extends('layouts.app')

@section('title', 'Browse Stocks')

@push('styles')
<style>
    .stocks-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
        margin: -1.5rem -15px 2rem -15px;
    }
    
    .market-overview {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .market-index {
        padding: 1.5rem;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
    }
    
    .market-index:hover {
        background-color: #f8f9fa;
    }
    
    .market-index:last-child {
        border-bottom: none;
    }
    
    .stock-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: none;
        overflow: hidden;
    }
    
    .stock-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }
    
    .stock-symbol {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2c3e50;
    }
    
    .stock-price {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2c3e50;
    }
    
    .price-change {
        font-size: 1rem;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
    }
    
    .price-up {
        background-color: #d4edda;
        color: #155724;
    }
    
    .price-down {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .price-neutral {
        background-color: #e2e3e5;
        color: #6c757d;
    }
    
    .sector-badge {
        background: linear-gradient(45deg, #667eea, #764ba2);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .search-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .btn-modern {
        border-radius: 8px;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: all 0.2s;
    }
    
    .btn-modern:hover {
        transform: translateY(-1px);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        text-align: center;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #667eea;
    }
    
    .mini-chart {
        width: 60px;
        height: 30px;
        background: linear-gradient(45deg, #28a745, #20c997);
        border-radius: 4px;
        position: relative;
        display: inline-block;
        margin-left: 10px;
    }
    
    .mini-chart.red {
        background: linear-gradient(45deg, #dc3545, #fd7e14);
    }
    
    .mini-chart::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 10%;
        right: 10%;
        height: 2px;
        background: rgba(255,255,255,0.8);
        border-radius: 1px;
    }
</style>
@endpush

@section('content')

<div class="container">
    <!-- Hero Section -->

    <div class="col-xl-12 m-t-30">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-between align-items-center">
                        {{-- <div class="col-md-3 col-sm-6 m-b-15">
                            <h5 class="text-muted">Total balance</h5>
                            
                            <h2><span id="total_balance_est">{{ number_format($totalValue ?? 0, 2) }}</span> <span> USDT </span></h2>
                        </div> --}}
                        <div class="col-md-9 col-sm-6 text-right hide-mobile">
                            <a href="{{ url('portfolio') }}" class="btn-2"><i class="fa fa-briefcase"></i> Portfolio</a>

                            <a href="{{ url('watchlist') }}" class="btn-1"><i class="fa fa-star"></i> Watchlist</a>
                            
                            <a href="{{ url('trading') }}" class="btn-2"><i class="fa fa-line-chart"></i> Trading</a>

                        </div>
                        <div class=" d-lg-none w-100">
                            
                            <div class="row w-100 mt-2">
                                <div class="col-6"><a href="{{ url('portfolio') }}" class="btn-2 btn-block"><i
                                            class="fa fa-briefcase"></i> Portfolio</a></div>
                                <div class="col-6 text-right"><a href="{{ url('watchlist') }}" class="btn-2 btn-block"><i
                                            class="fa fa-star"></i> Watchlist</a></div>
                            </div>
                            <div class="row w-100 mt-2">
                                <div class="col-12">
                                    <a href="{{ url('trading') }}" class="btn-1 btn-block"><i class="fa fa-line-chart"></i> Start Trading</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    {{-- <div class="stocks-hero mt-5">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-4 fw-bold mb-3">Stock Market</h1>
                    <p class="lead mb-0">Discover and track the best performing stocks in real-time</p>
                </div>
                <div class="col-md-4 text-end">
                    @if(Auth::user()->trading_enabled)
                    <a href="{{ route('trading.index') }}" class="btn btn-light btn-lg btn-modern">
                        <i class="fas fa-rocket me-2"></i>
                        Start Trading
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Market Overview -->
    <div class="stats-grid">
        <div class="stat-card bg-dark">
            <div class="stat-number">{{ number_format($stocks->total() ?? $stocks->count()) }}</div>
            <div class="text">Available Stocks</div>
        </div>
        <div class="stat-card bg-dark">
            <div class="stat-number text-success">{{ number_format($stocks->where('change', '>', 0)->count()) }}</div>
            <div class="text">Gainers Today</div>
        </div>
        <div class="stat-card bg-dark">
            <div class="stat-number text-danger">{{ number_format($stocks->where('change', '<', 0)->count()) }}</div>
            <div class="text">Losers Today</div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="search-container mb-5 bg-dark">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('stocks.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label for="search" class="form-label fw-semibold">Search Stocks</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-end-0">
                                <i class="fa fa-search text"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 ps-0" id="search" name="search" 
                                value="{{ request('search') }}" placeholder="Enter symbol or company name">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="sector" class="form-label fw-semibold">Filter by Sector</label>
                        <select class="form-select" id="sector" name="sector">
                            <option value="">All Sectors</option>
                            @foreach($sectors as $sector)
                            <option value="{{ $sector }}" {{ request('sector') === $sector ? 'selected' : '' }}>
                                {{ $sector }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100 btn-modern">
                            <i class="fas fa-filter me-2"></i> Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Stocks Grid -->
    <div class="row g-4">
        @forelse($stocks as $stock)
        <div class="col-md-6 col-xl-4 p-4">
            <div class="stock-card card h-100 bg-dark">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-2">
                                <h5 class="stock-symbol mb-0">
                                    <a href="{{ route('stocks.show', $stock) }}" class="text-dark" style="text-decoration:none;">
                                        {{ $stock->symbol }}
                                    </a>
                                </h5>
                                <div class="mini-chart {{ $stock->change < 0 ? 'red' : '' }}"></div>
                            </div>
                            <p class="text mb-0 small">{{ Str::limit($stock->name, 30) }}</p>
                        </div>
                        <div class="ml-3">
                            @if(in_array($stock->id, $user_watchlist))
                            <form method="POST" action="{{ route('stocks.remove-from-watchlist', $stock) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-warning rounded-circle" title="Remove from watchlist">
                                    <i class="fa fa-star"></i>
                                </button>
                            </form>
                            @else
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle"
                                    data-toggle="modal" data-target="#watchlistModal{{ $stock->id }}"
                                    title="Add to watchlist">
                                <i class="fa fa-star-o"></i>
                            </button>
                            @endif
                        </div>
                    </div>

                    <div class="row text-center mb-3">
                        <div class="col-7">
                            <div class="stock-price text-primary">${{ number_format($stock->current_price, 2) }}</div>
                            <small class="text">Current Price</small>
                        </div>
                        <div class="col-5">
                            @if($stock->change !== 0)
                                <div class="price-change {{ $stock->change >= 0 ? 'price-up' : 'price-down' }}">
                                    {{ $stock->change >= 0 ? '+' : '' }}{{ number_format($stock->change_percentage, 2) }}%
                                </div>
                                <small class="text d-block mt-1">
                                    {{ $stock->change >= 0 ? '+' : '' }}${{ number_format($stock->change, 2) }}
                                </small>
                            @else
                                <div class="price-change price-neutral">0.00%</div>
                                <small class="text d-block mt-1">No change</small>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        @if($stock->sector)
                        <span class="sector-badge">{{ $stock->sector }}</span>
                        @else
                        <span></span>
                        @endif
                        <div class="text-right">
                            <small class="text d-block">Vol: {{ number_format($stock->volume) }}</small>
                            @if($stock->market_cap)
                            <small class="text">Cap: ${{ number_format($stock->market_cap / 1000000, 0) }}M</small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <div class="row no-gutters">
                        <div class="col pr-1">
                            <a href="{{ route('stocks.show', $stock) }}" class="btn btn-outline-primary btn-block btn-modern">
                                <i class="fa fa-line-chart mr-1"></i> Details
                            </a>
                        </div>
                        @if(Auth::user()->trading_enabled)
                        <div class="col pl-1">
                            <a href="{{ route('trading.index') }}?stock={{ $stock->id }}" class="btn btn-primary btn-block btn-modern text-white">
                                <i class="fa fa-bolt mr-1"></i> Trade
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        
        <!-- Watchlist Modal -->
        <!-- Watchlist Modal -->
        <div class="modal fade" id="watchlistModal{{ $stock->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST" action="{{ route('stocks.add-to-watchlist', $stock) }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Add {{ $stock->symbol }} to Watchlist</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="target_price{{ $stock->id }}">Target Price (Optional)</label>
                                <input type="number" class="form-control"
                                    id="target_price{{ $stock->id }}"
                                    name="target_price" step="0.01" min="0">
                            </div>
                            <div class="form-group">
                                <label for="notes{{ $stock->id }}">Notes (Optional)</label>
                                <textarea class="form-control" id="notes{{ $stock->id }}" name="notes" rows="3"></textarea>
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

@empty
<div class="col-12">
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="fa fa-search fa-4x text" style="opacity:0.5;"></i>
        </div>
        <h3 class="text mb-3">No stocks found</h3>
        <p class="text mb-4">We couldn't find any stocks matching your criteria.</p>
        <a href="{{ route('stocks.index') }}" class="btn btn-primary btn-modern">
            <i class="fa fa-refresh mr-2"></i> View All Stocks
        </a>
    </div>
</div>
@endforelse

    </div>

    <!-- Pagination -->
    @if(method_exists($stocks, 'hasPages') && $stocks->hasPages())
    <div class="mt-5">
        <nav aria-label="Stock pagination">
            {{ $stocks->appends(request()->query())->links() }}
        </nav>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate stock cards on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observe all stock cards
    document.querySelectorAll('.stock-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease-out';
        observer.observe(card);
    });
    
    // Add smooth hover effects for mini charts
    document.querySelectorAll('.mini-chart').forEach(chart => {
        chart.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
            this.style.transition = 'transform 0.2s ease';
        });
        
        chart.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    // Real-time search
    const searchInput = document.getElementById('search');
    let searchTimeout;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Auto-submit form after 500ms of no typing
                if (this.value.length >= 2 || this.value.length === 0) {
                    this.form.submit();
                }
            }, 500);
        });
    }
});
</script>
@endpush