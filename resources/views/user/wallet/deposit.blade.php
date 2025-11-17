@extends('layouts.app')

@section('title', 'Deposit ' . ($cryptocurrency->name ?? 'Cryptocurrency'))

@section('content')
<style>
    body {
        background-color: #0d1117;
        color: #e6edf3;
        font-family: 'Inter', sans-serif;
    }
    .accent { color: #1e90ff !important; }
    .bg-accent { background-color: #1e90ff !important; }
    .btn-accent {
        background-color: #1e90ff;
        color: #000;
        border: none;
        transition: 0.3s;
    }
    .btn-accent:hover {
        background-color: #1e90ff;
        color: #000;
    }
    .card {
        background-color: #161b22;
        border: 1px solid #30363d;
        border-radius: 1rem;
    }
    .form-control, .input-group-text {
        background-color: #0d1117;
        border: 1px solid #30363d;
        color: #e6edf3;
    }
    .form-control:focus {
        border-color: #1e90ff;
        box-shadow: 0 0 0 0.25rem rgba(255,190,64,0.25);
    }
    .alert-warning {
        background-color: rgba(255, 190, 64, 0.1);
        border-color: #1e90ff;
        color: #1e90ff;
    }
    .hover-glow:hover {
        box-shadow: 0 0 12px #1e90ff;
        transition: 0.3s ease-in-out;
    }
</style>

<!-- Header -->
<div class="py-5 mb-5 text-center bg-black border-bottom border-secondary">
    <div class="container">
        <h1 class="fw-bold mb-2 text-light">
            Deposit {{ $cryptocurrency->name ?? 'Cryptocurrency' }}
        </h1>
        <p class="text-muted fs-5">
            Send <span class="accent">{{ strtoupper($cryptocurrency->symbol ?? '') }}</span> to your wallet address
        </p>

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
    </div>
</div>

<main class="container pb-5">
    <div class="row">
        <!-- Deposit Information -->
        <div class="col-lg-6 mb-4">
            <div class="card p-4 hover-glow">
                <h2 class="h5 card-title mb-4 fw-semibold">Deposit Information</h2>

                <div class="mb-4">
                    <label class="form-label small text-secondary">Wallet Address</label>
                    <div class="input-group">
                        <input type="text" id="depositAddress"
                               class="form-control"
                               readonly
                               value="{{ isset($wallet->address) ? substr($wallet->address, 0, 8) . '****' . substr($wallet->address, -6) : 'Generating address...' }}">
                        <button type="button" 
                                class="btn btn-accent fw-semibold"
                                data-address="{{ $wallet->address ?? '' }}"
                                onclick="copyAddress(this)">
                            <i class="fa fa-copy me-1"></i> Copy
                        </button>

                    </div>
                    @if(!$wallet->address)
                    <small class="text-warning mt-2 d-block">
                        <i class="fa fa-exclamation-triangle mr-1"></i>
                        Address is being generated. Please wait...
                    </small>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="form-label small text-secondary">Network</label>
                    <div class="form-control bg-transparent">
                        {{ $cryptocurrency->network ?? 'Main Network' }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label small text-secondary">Minimum Deposit</label>
                        <div class="form-control bg-transparent">
                            {{ number_format($cryptocurrency->min_deposit_amount ?? 0.001, 8) }} {{ strtoupper($cryptocurrency->symbol ?? '') }}
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label small text-secondary">Current Balance</label>
                        <div class="form-control bg-transparent">
                            {{ number_format($wallet->balance ?? 0, 8) }} {{ strtoupper($cryptocurrency->symbol ?? '') }}
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ url('wallet/deposit/submit', $cryptocurrency->id) }}" enctype="multipart/form-data" class="mt-4">
                    @csrf

                    <div class="mb-3">
                        <label for="amount" class="form-label small text-secondary">Deposit Amount</label>
                        <input type="number" step="0.00000001" min="0" class="form-control" name="amount" id="amount" required>
                    </div>

                    <div class="mb-3">
                        <label for="payment_proof" class="form-label small text-secondary">Upload Payment Proof (optional)</label>
                        <input type="file" class="form-control" name="payment_proof" id="payment_proof" accept=".jpg,.jpeg,.png,.pdf">
                    </div>

                    <button type="submit" class="btn btn-accent fw-semibold w-100">
                        <i class="fa fa-upload me-1"></i> Submit Deposit
                    </button>
                </form>

                <div class="text-center mt-5">
                    <div class="border border-secondary rounded p-4 d-inline-block bg-dark">
                        <div class="d-flex align-items-center justify-content-center rounded"
                             style="width: 160px; height: 160px;">
                            <i class="fa fa-qrcode fs-1 text-secondary"></i>
                        </div>
                        <p class="text-secondary small mt-3 mb-0">QR Code (Coming Soon)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deposit Instructions -->
        <div class="col-lg-6 mb-4">
            <div class="card p-4 hover-glow">
                <h2 class="h5 card-title mb-4 fw-semibold">How to Deposit</h2>

                @php
                    $steps = [
                        ['title'=>'Copy Address', 'text'=>'Copy the wallet address above or scan the QR code.'],
                        ['title'=>'Send Funds', 'text'=>'Send ' . strtoupper($cryptocurrency->symbol ?? '') . ' from your external wallet or exchange.'],
                        ['title'=>'Upload Proof of Payment', 'text'=>'After payment, upload the amount and proof of payment for confirmation.'],
                        ['title'=>'Confirm', 'text'=>'Wait for network confirmation (usually 1–3 confirmations).'],
                        ['title'=>'Receive', 'text'=>'Funds will appear in your wallet after confirmation.']
                    ];
                @endphp

                @foreach($steps as $i => $step)
                <div class="d-flex align-items-start mb-4">
                    <div class="rounded-circle bg-accent text-dark d-flex align-items-center justify-content-center mr-3"
                         style="width: 44px; height: 44px; font-weight: 600;">
                        {{ $i+1 }}
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1">{{ $step['title'] }}</h6>
                        <p class="small text-secondary mb-0">{{ $step['text'] }}</p>
                    </div>
                </div>
                @endforeach

                


                <div class="alert alert-warning mt-4">
                    <div>
                        <h6 class="fw-bold mb-2">
                            <i class="fa fa-exclamation-triangle mr-2"></i> Important Notice
                        </h6>
                        <ul class="small mb-0 pl-3">
                            <li>Only send {{ strtoupper($cryptocurrency->symbol ?? '') }} to this address.</li>
                            <li>Deposits require network confirmations.</li>
                            <li>Minimum deposit: {{ number_format($cryptocurrency->min_deposit_amount ?? 0.001, 8) }} {{ strtoupper($cryptocurrency->symbol ?? '') }}.</li>
                            <li>Ensure you’re using the correct network.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Deposits -->
    <div class="card p-4 mt-5 hover-glow">
        <h2 class="h5 card-title mb-4 fw-semibold">Recent Deposits</h2>

        @forelse($deposits as $deposit)
        <div class="d-flex align-items-center justify-content-between border rounded p-3 mb-3 bg-dark">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-success bg-opacity-25 text-success d-flex align-items-center justify-content-center mr-3"
                    style="width: 48px; height: 48px;">
                    <i class="fa fa-arrow-down"></i>
                </div>
                <div>
                    <h6 class="fw-semibold mb-0 text-light">Deposit</h6>
                    <small class="text-secondary">{{ $deposit->created_at->format('M j, Y g:i A') }}</small>
                </div>
            </div>
            <div class="text-right">
                <div class="fw-bold accent">
                    +{{ number_format($deposit->amount, 8) }}
                </div>
                <small class="text-secondary">{{ strtoupper($cryptocurrency->symbol ?? '') }}</small><br>
                <span class="badge rounded-pill
                    @if($deposit->status === 'completed' || $deposit->status === 'confirmed') badge-success
                    @elseif($deposit->status === 'pending') badge-warning text-dark
                    @else badge-danger
                    @endif">
                    {{ ucfirst($deposit->status) }}
                </span>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="fa fa-arrow-down fa-2x text-secondary mb-3"></i>
            <p class="text-muted">No deposits yet</p>
        </div>
        @endforelse
    </div>
</main>

<script>
    function copyAddress(btn) {
        // Get the actual wallet address safely
        const address = btn.getAttribute('data-address') || '';
        if (!address) {
            showTempButtonText(btn, 'No address', 'btn-danger');
            return;
        }

        // Modern browsers clipboard API
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(address).then(() => {
                showTempButtonText(btn, '<i class="fa fa-check me-1"></i> Copied!', 'btn-success');
            }).catch(() => fallbackCopyText(address, btn));
        } else {
            // Fallback for older browsers
            fallbackCopyText(address, btn);
        }
    }

    // Fallback copy method
    function fallbackCopyText(text, btn) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.top = '-9999px';
        document.body.appendChild(textarea);
        textarea.focus();
        textarea.select();

        try {
            const successful = document.execCommand('copy');
            if (successful) {
                showTempButtonText(btn, '<i class="fa fa-check me-1"></i> Copied!', 'btn-success');
            }
        } catch (err) {
            alert('Could not copy address. Please copy manually.');
        }

        document.body.removeChild(textarea);
    }

    // Button feedback animation
    function showTempButtonText(btn, tempHtml, successClass = 'btn-success') {
        const originalHtml = btn.innerHTML;
        const originalClass = btn.className;

        btn.innerHTML = tempHtml;
        btn.classList.remove('btn-accent');
        btn.classList.add(successClass);

        setTimeout(() => {
            btn.innerHTML = originalHtml;
            btn.className = originalClass;
        }, 2000);
    }
</script>

@endsection
