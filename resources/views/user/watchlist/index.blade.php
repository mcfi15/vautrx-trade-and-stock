@extends('layouts.app')

@section('title', 'Watchlist')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mt-5 mb-4">
    <h2>My Watchlist</h2>
    <a href="{{ route('stocks.index') }}" class="btn btn-primary text-white">
      <i class="fa fa-plus mr-1"></i> Add Stocks
    </a>
  </div>

  <!-- Watchlist -->
  <div class="card">
    <div class="card-header">
      <h5 class="card-title mb-0">
        Watched Stocks ({{ $watchlists->count() }})
      </h5>
    </div>
    <div class="card-body">
      @if($watchlists->count() > 0)
      <div class="row">
        @foreach($watchlists as $watchlist)
        <div class="col-md-6 col-lg-4 mb-4">
          <div class="card h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                  <h5 class="card-title mb-1">
                    <a href="{{ route('stocks.show', $watchlist->stock) }}" class="text-decoration-none">
                      {{ $watchlist->stock->symbol }}
                    </a>
                  </h5>
                  <p class="card-text text-muted small">
                    {{ $watchlist->stock->name }}
                  </p>
                </div>

                <div class="dropdown">
                  <button
                    class="btn btn-sm btn-outline-secondary dropdown-toggle"
                    type="button"
                    data-toggle="dropdown"
                  >
                    <i class="fa fa-ellipsis-v"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <button
                      type="button"
                      class="dropdown-item"
                      data-toggle="modal"
                      data-target="#editModal{{ $watchlist->id }}"
                    >
                      <i class="fa fa-edit mr-2"></i> Edit
                    </button>
                    <form
                      method="POST"
                      action="{{ route('watchlist.destroy', $watchlist) }}"
                      class="d-inline"
                    >
                      @csrf
                      @method('DELETE')
                      <button
                        type="submit"
                        class="dropdown-item text-danger"
                        onclick="return confirm('Are you sure you want to remove this stock from your watchlist?')"
                      >
                        <i class="fa fa-trash mr-2"></i> Remove
                      </button>
                    </form>
                  </div>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-6">
                  <div class="text-center">
                    <div class="stock-price h4 mb-0">
                      ${{ number_format($watchlist->stock->current_price, 2) }}
                    </div>
                    <small class="text-muted">Current Price</small>
                  </div>
                </div>
                <div class="col-6">
                  <div class="text-center">
                    @if($watchlist->stock->change !== 0)
                    <div class="h4 mb-0 {{ $watchlist->stock->change >= 0 ? 'text-success' : 'text-danger' }}">
                      {{ $watchlist->stock->change >= 0 ? '+' : '' }}{{ number_format($watchlist->stock->change_percentage, 2) }}%
                    </div>
                    <small class="text-muted">
                      {{ $watchlist->stock->change >= 0 ? '+' : '' }}${{ number_format($watchlist->stock->change, 2) }}
                    </small>
                    @else
                    <div class="h4 mb-0 text-muted">-</div>
                    <small class="text-muted">No change</small>
                    @endif
                  </div>
                </div>
              </div>

              @if($watchlist->target_price)
              <div class="alert alert-info py-2">
                <small>
                  <i class="fa fa-bullseye mr-1"></i>
                  Target: ${{ number_format($watchlist->target_price, 2) }}
                  @if($watchlist->stock->current_price >= $watchlist->target_price)
                  <span class="badge badge-success ml-1">Reached!</span>
                  @endif
                </small>
              </div>
              @endif

              @if($watchlist->notes)
              <div class="mb-2">
                <small class="text-muted">
                  <i class="fa fa-sticky-note mr-1"></i>
                  {{ Str::limit($watchlist->notes, 50) }}
                </small>
              </div>
              @endif

              <small class="text-muted d-block">
                Added: {{ $watchlist->created_at->format('M j, Y') }}
              </small>
            </div>

            <div class="card-footer bg-transparent">
              <div class="row">
                <div class="col">
                  <a
                    href="{{ route('stocks.show', $watchlist->stock) }}"
                    class="btn btn-outline-primary btn-sm btn-block"
                  >
                    <i class="fa fa-line-chart mr-1"></i> View
                  </a>
                </div>
                @if(Auth::user()->trading_enabled)
                <div class="col">
                  <a
                    href="{{ route('trading.index') }}?stock={{ $watchlist->stock->id }}"
                    class="btn btn-primary btn-sm btn-block text-white"
                  >
                    <i class="fa fa-exchange mr-1"></i> Trade
                  </a>
                </div>
                @endif
              </div>
            </div>
          </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal{{ $watchlist->id }}" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <form method="POST" action="{{ route('watchlist.update', $watchlist) }}">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                  <h5 class="modal-title">Edit {{ $watchlist->stock->symbol }}</h5>
                  <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                    <label for="target_price{{ $watchlist->id }}">Target Price</label>
                    <input
                      type="number"
                      class="form-control"
                      id="target_price{{ $watchlist->id }}"
                      name="target_price"
                      step="0.01"
                      min="0"
                      value="{{ $watchlist->target_price }}"
                    >
                  </div>
                  <div class="form-group">
                    <label for="notes{{ $watchlist->id }}">Notes</label>
                    <textarea
                      class="form-control"
                      id="notes{{ $watchlist->id }}"
                      name="notes"
                      rows="3"
                    >{{ $watchlist->notes }}</textarea>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary">Update</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        @endforeach
      </div>
      @else
      <div class="text-center py-5">
        <i class="fa fa-eye fa-3x text-muted mb-3"></i>
        <h4 class="text-muted">No Stocks in Watchlist</h4>
        <p class="text-muted mb-4">Add stocks to your watchlist to track their performance</p>
        <a href="{{ route('stocks.index') }}" class="btn btn-primary">
          <i class="fa fa-plus mr-1"></i> Browse Stocks
        </a>
      </div>
      @endif
    </div>
  </div>
</div>

@endsection