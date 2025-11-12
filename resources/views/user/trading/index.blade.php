@extends('layouts.app')

@section('title', 'Trading')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mt-4">
    <h4>Stock Trading</h4>
    <div class="text-right">
        <div class="font-weight-bold">
            Available USDT Balance:
            <span class="text-success">${{ number_format($usdtBalance, 2) }}</span>
        </div>
    </div>
  </div>

  @if(!Auth::user()->trading_enabled)
  <div class="alert alert-warning mt-3">
    <i class="fa fa-exclamation-triangle mr-2"></i>
    Trading is not enabled for your account. Please contact support to enable trading.
  </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible p-3 fade show" role="alert">
        <i class="fa fa-exclamation-triangle-fill mr-2"></i>
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible p-3 fade show" role="alert">
        <i class="fa fa-check-circle-fill mr-2"></i>
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

  <div class="row mt-4">
    <!-- Trading Form -->
    <div class="col-md-6 ">
      <div class="card">
        <div class="card-header p-0">
          <ul class="nav nav-tabs card-header-tabs" id="tradingTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="buy-tab" data-toggle="tab" href="#buy" role="tab">
                <i class="fa fa-plus-circle mr-1 text-success"></i> Buy
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="sell-tab" data-toggle="tab" href="#sell" role="tab">
                <i class="fa fa-minus-circle mr-1 text-danger"></i> Sell
              </a>
            </li>
          </ul>
        </div>

        <div class="card-body">
          <div class="tab-content" id="tradingTabContent">
            <!-- Buy Tab -->
            <div class="tab-pane fade show active" id="buy" role="tabpanel">
              <form method="POST" action="{{ route('trading.buy') }}" id="buyForm">
                @csrf
                <div class="form-group">
                  <label for="buy_stock_id">Select Stock</label>
                  <select class="form-control" id="buy_stock_id" name="stock_id" required>
                    <option value="">Choose a stock...</option>
                    @foreach($stocks as $stock)
                    <option value="{{ $stock->id }}"
                      data-price="{{ $stock->current_price }}"
                      {{ $selectedStock && $selectedStock->id == $stock->id ? 'selected' : '' }}>
                      {{ $stock->symbol }} - {{ $stock->name }} (${{ number_format($stock->current_price, 2) }})
                    </option>
                    @endforeach
                  </select>
                </div>

                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="buy_quantity">Quantity</label>
                    <input type="number" class="form-control" id="buy_quantity" name="quantity" min="1" required>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="buy_price">Price per Share</label>
                    <input type="number" class="form-control" id="buy_price" name="price" step="0.01" min="0.01" required>
                  </div>
                </div>

                <div class="mb-3">
                  <div class="row">
                    <div class="col-6">
                      <small class="text-muted">Total Amount: <span id="buy_total">$0.00</span></small>
                    </div>
                    <div class="col-6 text-right">
                      <small class="text-muted">Commission: <span id="buy_commission">$0.00</span></small>
                    </div>
                  </div>
                  <div class="font-weight-bold">Net Amount: <span id="buy_net">$0.00</span></div>
                </div>

                <button type="submit" class="btn btn-success btn-block">
                  <i class="fa fa-shopping-cart mr-2"></i> Place Buy Order
                </button>
              </form>
            </div>

            <!-- Sell Tab -->
            <div class="tab-pane fade" id="sell" role="tabpanel">
              <form method="POST" action="{{ route('trading.sell') }}" id="sellForm">
                @csrf
                <div class="form-group">
                  <label for="sell_stock_id">Select Stock from Portfolio</label>
                  <select class="form-control" id="sell_stock_id" name="stock_id" required>
                    <option value="">Choose a stock...</option>
                    @foreach($user_portfolios as $portfolio)
                    <option value="{{ $portfolio->stock->id }}"
                      data-price="{{ $portfolio->stock->current_price }}"
                      data-available="{{ $portfolio->quantity }}"
                      {{ $selectedStock && $selectedStock->id == $portfolio->stock->id ? 'selected' : '' }}>
                      {{ $portfolio->stock->symbol }} - {{ $portfolio->quantity }} shares available
                      (${{ number_format($portfolio->stock->current_price, 2) }})
                    </option>
                    @endforeach
                  </select>
                  @if($user_portfolios->isEmpty())
                  <small class="text-muted">You don't have any stocks to sell</small>
                  @endif
                </div>

                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="sell_quantity">Quantity</label>
                    <input type="number" class="form-control" id="sell_quantity" name="quantity" min="1" required>
                    <small class="text-muted">Available: <span id="available_shares">-</span></small>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="sell_price">Price per Share</label>
                    <input type="number" class="form-control" id="sell_price" name="price" step="0.01" min="0.01" required>
                  </div>
                </div>

                <div class="mb-3">
                  <div class="row">
                    <div class="col-6">
                      <small class="text-muted">Total Amount: <span id="sell_total">$0.00</span></small>
                    </div>
                    <div class="col-6 text-right">
                      <small class="text-muted">Commission: <span id="sell_commission">$0.00</span></small>
                    </div>
                  </div>
                  <div class="font-weight-bold">Net Amount: <span id="sell_net">$0.00</span></div>
                </div>

                <button type="submit" class="btn btn-danger btn-block" {{ $user_portfolios->isEmpty() ? 'disabled' : '' }}>
                  <i class="fa fa-hand-holding-usd mr-2"></i> Place Sell Order
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Portfolio Summary -->
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">Your Portfolio</h5>
        </div>
        <div class="card-body">
          @forelse($user_portfolios as $portfolio)
          <div class="d-flex justify-content-between align-items-center mb-3 p-3 border rounded">
            <div>
              <h6 class="mb-1">{{ $portfolio->stock->symbol }}</h6>
              <small class="text-muted">{{ $portfolio->quantity }} shares @ ${{ number_format($portfolio->average_price, 2) }}</small>
            </div>
            <div class="text-right">
              <div class="font-weight-bold">${{ number_format($portfolio->current_value, 2) }}</div>
              <small class="{{ $portfolio->profit_loss >= 0 ? 'text-success' : 'text-danger' }}">
                {{ $portfolio->profit_loss >= 0 ? '+' : '' }}${{ number_format($portfolio->profit_loss, 2) }}
                ({{ $portfolio->profit_loss >= 0 ? '+' : '' }}{{ number_format($portfolio->profit_loss_percentage, 2) }}%)
              </small>
            </div>
          </div>
          @empty
          <div class="text-center text-muted py-4">
            <i class="fa fa-briefcase fa-2x mb-3 d-block"></i>
            <p class="mb-0">No holdings yet</p>
            <small>Start by buying some stocks</small>
          </div>
          @endforelse
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="card mt-4">
        <div class="card-header">
          <h5 class="card-title mb-0">Quick Stats</h5>
        </div>
        <div class="card-body">
          <div class="row text-center">
            <div class="col-4">
                <div class="font-weight-bold text-primary">${{ number_format($totalPortfolioValue, 2) }}</div>
                <small class="text-muted">Portfolio Value</small>
            </div>
            <div class="col-4">
                <div class="font-weight-bold text-info">${{ number_format($totalInvestment, 2) }}</div>
                <small class="text-muted">Total Invested</small>
            </div>
            <div class="col-4">
                <div class="font-weight-bold {{ $profitLoss >= 0 ? 'text-success' : 'text-danger' }}">
                {{ $profitLoss >= 0 ? '+' : '' }}${{ number_format($profitLoss, 2) }}
                </div>
                <small class="text-muted">P&amp;L</small>
            </div>
            </div>

        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
// Auto-fill price when page loads if stock is pre-selected
document.addEventListener('DOMContentLoaded', function() {
    const buyStockSelect = document.getElementById('buy_stock_id');
    const sellStockSelect = document.getElementById('sell_stock_id');
    
    // Check if stock is pre-selected for buying
    if (buyStockSelect.value) {
        const selectedOption = buyStockSelect.options[buyStockSelect.selectedIndex];
        const price = selectedOption.dataset.price;
        if (price) {
            document.getElementById('buy_price').value = parseFloat(price).toFixed(2);
            calculateBuyTotal();
        }
    }
    
    // Check if stock is pre-selected for selling (user owns shares)
    if (sellStockSelect.value) {
        const selectedOption = sellStockSelect.options[sellStockSelect.selectedIndex];
        const price = selectedOption.dataset.price;
        const available = selectedOption.dataset.available;
        
        if (price) {
            document.getElementById('sell_price').value = parseFloat(price).toFixed(2);
        }
        
        if (available) {
            document.getElementById('available_shares').textContent = available + ' shares';
            document.getElementById('sell_quantity').max = available;
        }
        
        calculateSellTotal();
        
        // Auto-switch to sell tab if user owns shares of the pre-selected stock
        const sellTab = document.getElementById('sell-tab');
        const sellTabPane = document.getElementById('sell');
        const buyTab = document.getElementById('buy-tab');
        const buyTabPane = document.getElementById('buy');
        
        buyTab.classList.remove('active');
        buyTabPane.classList.remove('show', 'active');
        sellTab.classList.add('active');
        sellTabPane.classList.add('show', 'active');
    }
});

// Auto-fill price when stock is selected
document.getElementById('buy_stock_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const price = selectedOption.dataset.price;
    if (price) {
        document.getElementById('buy_price').value = parseFloat(price).toFixed(2);
        calculateBuyTotal();
    }
});

document.getElementById('sell_stock_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const price = selectedOption.dataset.price;
    const available = selectedOption.dataset.available;
    
    if (price) {
        document.getElementById('sell_price').value = parseFloat(price).toFixed(2);
    }
    
    if (available) {
        document.getElementById('available_shares').textContent = available + ' shares';
        document.getElementById('sell_quantity').max = available;
    }
    
    calculateSellTotal();
});

// Calculate totals
function calculateBuyTotal() {
    const quantity = parseFloat(document.getElementById('buy_quantity').value) || 0;
    const price = parseFloat(document.getElementById('buy_price').value) || 0;
    const total = quantity * price;
    const commission = total * 0.001; // 0.1%
    const net = total + commission;
    
    document.getElementById('buy_total').textContent = '$' + total.toFixed(2);
    document.getElementById('buy_commission').textContent = '$' + commission.toFixed(2);
    document.getElementById('buy_net').textContent = '$' + net.toFixed(2);
}

function calculateSellTotal() {
    const quantity = parseFloat(document.getElementById('sell_quantity').value) || 0;
    const price = parseFloat(document.getElementById('sell_price').value) || 0;
    const total = quantity * price;
    const commission = total * 0.001; // 0.1%
    const net = total - commission;
    
    document.getElementById('sell_total').textContent = '$' + total.toFixed(2);
    document.getElementById('sell_commission').textContent = '$' + commission.toFixed(2);
    document.getElementById('sell_net').textContent = '$' + net.toFixed(2);
}

// Add event listeners for real-time calculation
document.getElementById('buy_quantity').addEventListener('input', calculateBuyTotal);
document.getElementById('buy_price').addEventListener('input', calculateBuyTotal);
document.getElementById('sell_quantity').addEventListener('input', calculateSellTotal);
document.getElementById('sell_price').addEventListener('input', calculateSellTotal);
</script>
@endpush