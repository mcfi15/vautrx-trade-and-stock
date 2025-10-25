@extends('layouts.app')

@section('title', 'Withdraw ' . ($cryptocurrency->name ?? 'Cryptocurrency'))

@section('content')
<div class="container py-5 bg-dark text-light rounded-3 shadow-lg">
    <!-- Header -->
    <div class="mb-5">
        <a href="{{ route('wallet.index') }}" class="text-decoration-none text-warning">
            <i class="fa fa-arrow-left me-2"></i> Back to Wallet
        </a>
        <h1 class="fw-bold text-white mt-3">Withdraw {{ $cryptocurrency->name ?? 'Cryptocurrency' }}</h1>
        <p class="text-secondary">Send {{ strtoupper($cryptocurrency->symbol ?? '') }} to an external wallet</p>
    </div>

    <div class="row g-4">
        <!-- Withdrawal Form -->
        <div class="col-lg-6">
            <div class="bg-secondary p-4 rounded-3 shadow-sm">
                <h2 class="h5 fw-semibold mb-3 text-warning">Withdrawal Details</h2>

                <form action="{{ route('wallet.withdraw.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="cryptocurrency_id" value="{{ $cryptocurrency->id }}">

                    <div class="mb-3">
                        <label class="form-label text-light">Withdrawal Address</label>
                        <input type="text" name="address" class="form-control bg-dark text-light border-0" required
                               placeholder="Enter {{ strtoupper($cryptocurrency->symbol ?? '') }} address">
                        @error('address')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-light">Amount</label>
                        <div class="input-group">
                            <input type="number" name="amount" id="amount" class="form-control bg-dark text-light border-0"
                                   required step="0.00000001"
                                   min="{{ $cryptocurrency->min_withdraw_amount ?? 0.001 }}"
                                   max="{{ $wallet->available_balance ?? 0 }}"
                                   placeholder="0.00000000">
                            <span class="input-group-text bg-dark text-warning border-0">
                                {{ strtoupper($cryptocurrency->symbol ?? '') }}
                            </span>
                        </div>
                        <button type="button" onclick="setMaxAmount()" class="btn btn-link text-warning p-0 mt-2">
                            Use Max: {{ number_format($wallet->available_balance ?? 0, 8) }}
                        </button>
                        @error('amount')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Fee Info -->
                    <div class="bg-dark p-3 rounded-3 mb-4">
                        <h6 class="fw-semibold text-warning mb-2">Transaction Details</h6>
                        <div class="small">
                            <div class="d-flex justify-content-between">
                                <span>Available Balance:</span>
                                <span>{{ number_format($wallet->available_balance ?? 0, 8) }} {{ strtoupper($cryptocurrency->symbol ?? '') }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Network Fee:</span>
                                <span>{{ number_format(($cryptocurrency->withdraw_fee ?? 0.001), 8) }} {{ strtoupper($cryptocurrency->symbol ?? '') }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Minimum Withdrawal:</span>
                                <span>{{ number_format($cryptocurrency->min_withdraw_amount ?? 0.001, 8) }} {{ strtoupper($cryptocurrency->symbol ?? '') }}</span>
                            </div>
                            <hr class="border-secondary">
                            <div class="d-flex justify-content-between fw-bold">
                                <span>You Will Receive:</span>
                                <span id="receiveAmount">- {{ strtoupper($cryptocurrency->symbol ?? '') }}</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn w-100 text-dark fw-bold py-2" style="background-color:#ffbe40;">
                        <i class="fa fa-paper-plane me-2"></i> Submit Withdrawal
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Section -->
        <div class="col-lg-6">
            <!-- Balance Card -->
            <div class="bg-secondary p-4 rounded-3 shadow-sm mb-4">
                <h2 class="h5 fw-semibold text-warning mb-3">Current Balance</h2>
                <div class="text-center">
                    <div class="display-6 fw-bold text-white">
                        {{ number_format($wallet->balance ?? 0, 8) }}
                    </div>
                    <p class="text-warning">{{ strtoupper($cryptocurrency->symbol ?? '') }}</p>
                    <div class="row text-center">
                        <div class="col">
                            <small class="text-light">Available</small>
                            <div class="fw-semibold">{{ number_format($wallet->available_balance ?? 0, 8) }}</div>
                        </div>
                        <div class="col">
                            <small class="text-light">Locked</small>
                            <div class="fw-semibold">{{ number_format($wallet->locked_balance ?? 0, 8) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Sections -->
            <div class="bg-secondary p-4 rounded-3 shadow-sm mb-4">
                <h2 class="h5 fw-semibold text-warning mb-3">Withdrawal Info</h2>
                <div class="small">
                    <div class="d-flex mb-3">
                        <i class="fa fa-info-circle text-warning me-3"></i>
                        <div>
                            <strong>Processing Time</strong>
                            <p class="mb-0 text-secondary">Processed within 5–30 mins during business hours.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <i class="fa fa-shield-alt text-warning me-3"></i>
                        <div>
                            <strong>Security</strong>
                            <p class="mb-0 text-secondary">Withdrawals require email verification and security checks.</p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <i class="fa fa-clock text-warning me-3"></i>
                        <div>
                            <strong>Network Confirmation</strong>
                            <p class="mb-0 text-secondary">Depends on blockchain congestion.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Network Info -->
            <div class="bg-secondary p-4 rounded-3 shadow-sm">
                <h2 class="h5 fw-semibold text-warning mb-3">Network Information</h2>
                <div class="small">
                    <div class="d-flex justify-content-between">
                        <span>Network:</span>
                        <span>{{ $cryptocurrency->network ?? 'Main Network' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Current Price:</span>
                        <span>${{ number_format($cryptocurrency->current_price ?? 0, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>24h Volume:</span>
                        <span>${{ number_format($cryptocurrency->volume_24h ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Withdrawals -->
    <div class="bg-secondary rounded-3 shadow-sm p-4 mt-5">
        <h2 class="h5 fw-semibold text-warning mb-3">Recent Withdrawals</h2>
        @forelse($withdrawals as $withdrawal)
        <div class="d-flex justify-content-between align-items-center border-bottom border-dark py-3">
            <div class="d-flex align-items-center">
                <div class="bg-dark text-warning rounded-circle d-flex justify-content-center align-items-center" style="width:40px;height:40px;">
                    <i class="fa fa-arrow-up"></i>
                </div>
                <div class="ms-3">
                    <strong>Withdrawal</strong>
                    <div class="small text-secondary">{{ Str::limit($withdrawal->withdrawal_address, 20) }}</div>
                    <div class="small text-secondary">{{ $withdrawal->created_at->format('M j, Y g:i A') }}</div>
                </div>
            </div>
            <div class="text-end">
                <div class="fw-bold text-warning">-{{ number_format($withdrawal->amount, 8) }}</div>
                <small class="text-light">{{ strtoupper($cryptocurrency->symbol ?? '') }}</small>
                <div>
                    <span class="badge 
                        @if($withdrawal->status === 'completed') bg-success
                        @elseif($withdrawal->status === 'pending') bg-warning text-dark
                        @else bg-danger @endif">
                        {{ ucfirst($withdrawal->status) }}
                    </span>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-4 text-secondary">
            <i class="fa fa-arrow-up fa-2x mb-2"></i>
            <p>No withdrawals yet</p>
        </div>
        @endforelse
    </div>
</div>

<script>
function setMaxAmount() {
    const maxAmount = {{ $wallet->available_balance ?? 0 }};
    document.getElementById('amount').value = maxAmount;
    calculateReceiveAmount();
}

function calculateReceiveAmount() {
    const amount = parseFloat(document.getElementById('amount').value) || 0;
    const fee = {{ $cryptocurrency->withdraw_fee ?? 0.001 }};
    const receive = Math.max(0, amount - fee);
    document.getElementById('receiveAmount').textContent = receive.toFixed(8) + ' {{ strtoupper($cryptocurrency->symbol ?? '') }}';
}

document.getElementById('amount').addEventListener('input', calculateReceiveAmount);
calculateReceiveAmount();
</script>

@endsection