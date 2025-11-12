@extends('layouts.app')

@section('title', 'Portfolio')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mt-5 mb-4">
    <h2>My Portfolio</h2>
    <div class="text-right">
      <div class="font-weight-bold">Total Value:
        <span class="text-primary">${{ number_format($stats['total_value'], 2) }}</span>
      </div>
    </div>
  </div>

  <!-- Portfolio Stats -->
  <div class="row mb-4">
    <div class="col-md-3">
      <div class="card text-white bg-primary">
        <div class="card-body text-center">
          <h4 class="card-title">${{ number_format($stats['total_value'], 2) }}</h4>
          <p class="card-text">Portfolio Value</p>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-white bg-info">
        <div class="card-body text-center">
          <h4 class="card-title">${{ number_format($stats['total_investment'], 2) }}</h4>
          <p class="card-text">Total Investment</p>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-white bg-{{ $stats['profit_loss'] >= 0 ? 'success' : 'danger' }}">
        <div class="card-body text-center">
          <h4 class="card-title">
            {{ $stats['profit_loss'] >= 0 ? '+' : '' }}${{ number_format($stats['profit_loss'], 2) }}
          </h4>
          <p class="card-text">Unrealized P&amp;L</p>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-white bg-{{ $stats['profit_loss_percentage'] >= 0 ? 'success' : 'danger' }}">
        <div class="card-body text-center">
          <h4 class="card-title">
            {{ $stats['profit_loss_percentage'] >= 0 ? '+' : '' }}{{ number_format($stats['profit_loss_percentage'], 2) }}%
          </h4>
          <p class="card-text">Return %</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Holdings Table -->
  <div class="card">
    <div class="card-header">
      <h5 class="card-title mb-0">Holdings ({{ $portfolios->count() }})</h5>
    </div>
    <div class="card-body">
      @if($portfolios->count() > 0)
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Stock</th>
              <th>Quantity</th>
              <th>Avg Price</th>
              <th>Current Price</th>
              <th>Market Value</th>
              <th>Cost Basis</th>
              <th>Unrealized P&amp;L</th>
              <th>Return %</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($portfolios as $portfolio)
            <tr>
              <td>
                <div>
                  <h6 class="mb-0">
                    <a href="{{ route('stocks.show', $portfolio->stock) }}" class="text-decoration-none">
                      {{ $portfolio->stock->symbol }}
                    </a>
                  </h6>
                  <small class="text-muted">{{ $portfolio->stock->name }}</small>
                </div>
              </td>
              <td>{{ number_format($portfolio->quantity) }}</td>
              <td>${{ number_format($portfolio->average_price, 2) }}</td>
              <td class="stock-price">${{ number_format($portfolio->stock->current_price, 2) }}</td>
              <td class="font-weight-bold">${{ number_format($portfolio->current_value, 2) }}</td>
              <td>${{ number_format($portfolio->total_invested, 2) }}</td>
              <td class="{{ $portfolio->profit_loss >= 0 ? 'text-success' : 'text-danger' }} font-weight-bold">
                {{ $portfolio->profit_loss >= 0 ? '+' : '' }}${{ number_format($portfolio->profit_loss, 2) }}
              </td>
              <td class="{{ $portfolio->profit_loss_percentage >= 0 ? 'text-success' : 'text-danger' }} font-weight-bold">
                {{ $portfolio->profit_loss_percentage >= 0 ? '+' : '' }}{{ number_format($portfolio->profit_loss_percentage, 2) }}%
              </td>
              <td>
                <div class="btn-group btn-group-sm" role="group">
                  <a href="{{ route('stocks.show', $portfolio->stock) }}" class="btn btn-outline-info" title="View Stock">
                    <i class="fa fa-eye"></i>
                  </a>
                  @if(Auth::user()->trading_enabled)
                  <button type="button" class="btn btn-outline-danger"
                          data-toggle="modal" data-target="#sellModal{{ $portfolio->id }}"
                          title="Quick Sell">
                    <i class="fa fa-minus-circle"></i>
                  </button>
                  <a href="{{ route('trading.index') }}?stock={{ $portfolio->stock->id }}"
                     class="btn btn-outline-primary" title="Go to Trading">
                    <i class="fa fa-exchange"></i>
                  </a>
                  @endif
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @else
      <div class="text-center py-5">
        <i class="fa fa-briefcase fa-3x text-muted mb-3"></i>
        <h4 class="text-muted">No Holdings Yet</h4>
        <p class="text-muted mb-4">Start building your portfolio by purchasing stocks</p>
        <div class="d-flex justify-content-center">
          <a href="{{ route('stocks.index') }}" class="btn btn-primary mr-2">
            <i class="fa fa-search mr-1"></i> Browse Stocks
          </a>
          @if(Auth::user()->trading_enabled)
          <a href="{{ route('trading.index') }}" class="btn btn-outline-primary">
            <i class="fa fa-plus mr-1"></i> Start Trading
          </a>
          @endif
        </div>
      </div>
      @endif
    </div>
  </div>

  @if($portfolios->count() > 0)
  <!-- Portfolio Allocation -->
  <div class="card mt-4">
    <div class="card-header">
      <h5 class="card-title mb-0">Portfolio Allocation</h5>
    </div>
    <div class="card-body">
      <div class="row">
        @foreach($portfolios as $portfolio)
        @php
          $percentage = $stats['total_value'] > 0 ? ($portfolio->current_value / $stats['total_value']) * 100 : 0;
        @endphp
        <div class="col-md-6 col-lg-4 mb-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <span class="font-weight-bold">{{ $portfolio->stock->symbol }}</span><br>
              <small class="text-muted">${{ number_format($portfolio->current_value, 2) }}</small>
            </div>
            <div class="text-right">
              <span class="badge badge-primary">{{ number_format($percentage, 1) }}%</span>
            </div>
          </div>
          <div class="progress mt-2" style="height: 8px;">
            <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%"></div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
  @endif

  <!-- Quick Sell Modals -->
  @if($portfolios->count() > 0)
  @foreach($portfolios as $portfolio)
  <div class="modal fade" id="sellModal{{ $portfolio->id }}" tabindex="-1" role="dialog"
       aria-labelledby="sellModalLabel{{ $portfolio->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="sellModalLabel{{ $portfolio->id }}">Quick Sell {{ $portfolio->stock->symbol }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('trading.sell') }}" method="POST">
          @csrf
          <input type="hidden" name="stock_id" value="{{ $portfolio->stock->id }}">
          <input type="hidden" name="price" value="{{ $portfolio->stock->current_price }}">
          <div class="modal-body">
            <div class="alert alert-info">
              <div class="row">
                <div class="col-6">
                  <small class="text-muted">Current Holdings</small>
                  <div class="font-weight-bold">{{ number_format($portfolio->quantity) }} shares</div>
                </div>
                <div class="col-6">
                  <small class="text-muted">Current Price</small>
                  <div class="font-weight-bold">${{ number_format($portfolio->stock->current_price, 2) }}</div>
                </div>
              </div>
              <hr class="my-2">
              <div class="row">
                <div class="col-6">
                  <small class="text-muted">Avg Cost</small>
                  <div>${{ number_format($portfolio->average_price, 2) }}</div>
                </div>
                <div class="col-6">
                  <small class="text-muted">Potential P&amp;L per share</small>
                  <div class="{{ ($portfolio->stock->current_price - $portfolio->average_price) >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ ($portfolio->stock->current_price - $portfolio->average_price) >= 0 ? '+' : '' }}${{ number_format($portfolio->stock->current_price - $portfolio->average_price, 2) }}
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="quantity{{ $portfolio->id }}">Quantity to Sell</label>
              <input type="number" class="form-control quantity-input"
                     id="quantity{{ $portfolio->id }}" name="quantity"
                     min="1" max="{{ $portfolio->quantity }}" required
                     data-price="{{ $portfolio->stock->current_price }}"
                     data-portfolio-id="{{ $portfolio->id }}">
              <small class="text-muted">Available: {{ number_format($portfolio->quantity) }} shares</small>
            </div>

            <div class="row">
              <div class="col-6">
                <small class="text-muted">Gross Amount</small>
                <div class="font-weight-bold" id="grossAmount{{ $portfolio->id }}">$0.00</div>
              </div>
              <div class="col-6">
                <small class="text-muted">Commission (0.1%)</small>
                <div id="commission{{ $portfolio->id }}">$0.00</div>
              </div>
            </div>
            <hr>
            <div class="text-center">
              <small class="text-muted">Net Proceeds</small>
              <h5 class="text-success mb-0" id="netAmount{{ $portfolio->id }}">$0.00</h5>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger">
              <i class="fa fa-minus-circle mr-2"></i> Sell Shares
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  @endforeach
  @endif
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Quick sell calculation
    $('.quantity-input').on('input', function() {
        const quantity = parseFloat($(this).val()) || 0;
        const price = parseFloat($(this).data('price')) || 0;
        const portfolioId = $(this).data('portfolio-id');
        
        const grossAmount = quantity * price;
        const commission = grossAmount * 0.001; // 0.1%
        const netAmount = grossAmount - commission;
        
        $('#grossAmount' + portfolioId).text('$' + grossAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        $('#commission' + portfolioId).text('$' + commission.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        $('#netAmount' + portfolioId).text('$' + netAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
    });
});
</script>
@endpush