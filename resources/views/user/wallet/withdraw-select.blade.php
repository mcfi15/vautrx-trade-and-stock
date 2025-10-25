@extends('layouts.app')

@section('title', 'Select Cryptocurrency for Withdrawal')

@section('content')
<div class="container my-5 text-light">
  <!-- Header -->
  <div class="mb-4">
    <a href="{{ route('wallet.index') }}" class="text-decoration-none text-warning fw-semibold">
      <i class="fa fa-arrow-left me-2"></i> Back to Wallet
    </a>
    <h1 class="display-6 fw-bold mt-3">Select Cryptocurrency for Withdrawal</h1>
    <p class="text-secondary">Choose which cryptocurrency you'd like to withdraw from your wallet</p>
  </div>

  <!-- Action Buttons -->
  <div class="d-flex flex-wrap gap-3 mb-4">
    <a href="{{ route('wallet.deposit') }}" class="btn btn-outline-light">
      <i class="fa fa-plus-circle me-2"></i> Deposit
    </a>
    <a href="{{ route('wallet.withdraw') }}" class="btn btn-warning text-dark fw-semibold">
      <i class="fa fa-minus-circle me-2"></i> Withdraw
    </a>
    <a href="{{ route('wallet.transactions') }}" class="btn btn-outline-light">
      <i class="fa fa-history me-2"></i> Transaction History
    </a>
  </div>

  <!-- Search + Filter -->
    <div class=" bg-transparent border-secondary p-4">
      <div class="row g-3 align-items-center">
        <div class="col-md-8">
          <div class="input-group">
            <span class="input-group-text bg-transparent border-secondary text-light">
              <i class="fa fa-search"></i>
            </span>
            <input id="search" type="text" class="form-control bg-transparent text-light border-secondary"
                   placeholder="Search cryptocurrencies..." onkeyup="filterCryptocurrencies()">
          </div>
        </div>
        <div class="col-md-4">
          <select id="balanceFilter" class="form-control bg-dark text-light border-secondary" onchange="filterCryptocurrencies()">
            <option value="">All Amounts</option>
            <option value="low">Low Balance (&lt; 0.01)</option>
            <option value="medium">Medium Balance (0.01 - 1)</option>
            <option value="high">High Balance (&gt; 1)</option>
          </select>
        </div>
      </div>
    </div>

  <!-- Holdings Section -->
    <div class="card bg-dark border-0 shadow-lg rounded-4 mb-4">
        <div class="card-header bg-secondary border-0 text-light">
        <h5 class="mb-1 fw-semibold">Your Cryptocurrency Holdings</h5>
        <p class="text-unmute small mb-0">Select a cryptocurrency to withdraw funds to an external wallet</p>
    </div>

    

    @php
      $userWallets = Auth::user()->wallets()->with('cryptocurrency')->get();
      $walletsWithBalance = $userWallets->filter(fn($wallet) => $wallet->available_balance > 0);
    @endphp

    @if($walletsWithBalance->count() > 0)
    <div class="row g-4 p-4">
      @foreach($walletsWithBalance as $wallet)
      <div class="col-12 col-sm-6 col-lg-4 col-xl-3 p-4">
        <div onclick="location.href='{{ route('wallet.withdraw.specific', $wallet->cryptocurrency_id) }}'"
             class="card bg-gradient border-0 text-center h-100 shadow-sm hover-scale"
             style="background: linear-gradient(135deg, #1e1e1e, #2c2c2c); cursor: pointer;">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-center mx-auto mb-3"
                 style="width:70px; height:70px; border-radius:50%; background: linear-gradient(135deg, #ffbe40, #ffda85); color:#1e1e1e; font-weight:700; font-size:1.5rem;">
              {{ strtoupper(substr($wallet->cryptocurrency->symbol, 0, 2)) }}
            </div>
            <h5 class="fw-semibold text-light mb-1">{{ $wallet->cryptocurrency->name }}</h5>
            <p class="text-muted small mb-3">{{ strtoupper($wallet->cryptocurrency->symbol) }}</p>

            <div class="text-start small">
              <div class="d-flex justify-content-between">
                <span class="text-muted">Available:</span>
                <span class="fw-semibold text-light">{{ number_format($wallet->available_balance, 8) }}</span>
              </div>

              @if($wallet->locked_balance > 0)
              <div class="d-flex justify-content-between">
                <span class="text-muted">Locked:</span>
                <span class="fw-semibold text-warning">{{ number_format($wallet->locked_balance, 8) }}</span>
              </div>
              @endif

              <div class="d-flex justify-content-between">
                <span class="text-muted">USDT Value:</span>
                <span class="fw-semibold text-success">
                  ${{ number_format($wallet->available_balance * ($wallet->cryptocurrency->current_price ?? 0), 2) }}
                </span>
              </div>
            </div>

            <div class="mt-3 border-top border-secondary pt-2 small text-muted">
              Min Withdrawal: {{ number_format($wallet->cryptocurrency->min_withdraw_amount ?? 0.001, 8) }}
              {{ strtoupper($wallet->cryptocurrency->symbol) }}
            </div>

            <div class="mt-3 pt-2 border-top border-secondary">
              <span class="text-warning fw-semibold">
                <i class="fa fa-arrow-up me-2"></i>
                Withdraw {{ strtoupper($wallet->cryptocurrency->symbol) }}
                <i class="fa fa-arrow-right ms-2"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    

    @else
    <!-- Empty State -->
    <div class="card-body text-center py-5">
      <i class="fa fa-wallet text-muted fs-1 mb-3"></i>
      <h5 class="fw-semibold text-light mb-2">No Funds Available for Withdrawal</h5>
      <p class="text-muted mb-4">You need to deposit cryptocurrency before making withdrawals</p>
      <a href="{{ route('wallet.deposit') }}" class="btn btn-warning text-dark me-2">
        <i class="fa fa-plus-circle me-2"></i> Deposit Funds
      </a>
      <a href="{{ route('wallet.index') }}" class="btn btn-outline-light">
        <i class="fa fa-eye me-2"></i> View Wallet
      </a>
    </div>
    @endif
  </div>

  <!-- Help Section -->
  <div class="alert bg-black border border-warning mt-4 rounded-3 shadow-sm">
    <div class="d-flex">
      <i class="fa fa-exclamation-triangle text-warning fs-5 me-3 mt-1"></i>
      <div>
        <h5 class="fw-semibold text-warning mb-2">Withdrawal Information</h5>
        <ul class="mb-0 text-light small">
          <li>Double-check the withdrawal address before submitting</li>
          <li>Withdrawals are subject to network fees and minimum amounts</li>
          <li>Large withdrawals may require additional verification</li>
          <li>Processing time varies by network (5–30 minutes typically)</li>
          <li>Once submitted, withdrawals cannot be cancelled</li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Portfolio Summary -->
  @if($walletsWithBalance->count() > 0)
  <div class="card bg-black border-0 shadow-lg rounded-4 mt-4 p-4">
    <h5 class="fw-semibold text-light mb-3">Total Portfolio Available for Withdrawal</h5>
    <div class="row g-4 text-center">
      <div class="col-md-4">
        <div class="p-3 rounded-3 bg-dark">
          <h4 class="fw-bold text-light mb-1">{{ number_format($walletsWithBalance->sum('available_balance'), 8) }}</h4>
          <small class="text-muted">Total Available</small>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-3 rounded-3 bg-dark">
          <h4 class="fw-bold text-success mb-1">
            ${{ number_format($walletsWithBalance->sum(fn($wallet) => $wallet->available_balance * ($wallet->cryptocurrency->current_price ?? 0)), 2) }}
          </h4>
          <small class="text-muted">Total USDT Value</small>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-3 rounded-3 bg-dark">
          <h4 class="fw-bold text-warning mb-1">{{ $walletsWithBalance->count() }}</h4>
          <small class="text-muted">Assets Available</small>
        </div>
      </div>
    </div>
  </div>
  @endif
</div>

<style>
body {
  background-color: #121212;
  color: #eaeaea;
}
.hover-scale {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hover-scale:hover {
  transform: scale(1.04);
  box-shadow: 0 0 25px rgba(255, 190, 64, 0.2);
}
</style>

<script>
function filterCryptocurrencies() {
  const search = document.getElementById('search').value.toLowerCase();
  const filter = document.getElementById('balanceFilter').value;
  const cards = document.querySelectorAll('.card.bg-gradient');

  cards.forEach(card => {
    const name = card.querySelector('h5').textContent.toLowerCase();
    const balance = parseFloat(card.querySelector('.fw-semibold.text-light')?.textContent || 0);
    let match = name.includes(search);
    if (filter === 'low') match = match && balance < 0.01;
    else if (filter === 'medium') match = match && balance >= 0.01 && balance <= 1;
    else if (filter === 'high') match = match && balance > 1;
    card.style.display = match ? '' : 'none';
  });
}
</script>


@endsection
