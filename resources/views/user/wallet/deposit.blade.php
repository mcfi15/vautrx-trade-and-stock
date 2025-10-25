@extends('layouts.app')

@section('title', 'Deposit ' . ($cryptocurrency->name ?? 'Cryptocurrency'))

@section('content')


    <style>
        /* body {
            background-color: #0d1117;
            color: #e6edf3;
            font-family: 'Inter', sans-serif;
        } */
        .accent {
            color: #ffbe40 !important;
        }
        .bg-accent {
            background-color: #ffbe40 !important;
        }
        .btn-accent {
            background-color: #ffbe40;
            color: #000;
            border: none;
        }
        .btn-accent:hover {
            background-color: #ffca61;
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
            border-color: #ffbe40;
            box-shadow: 0 0 0 0.25rem rgba(255,190,64,0.25);
        }
        .alert-warning {
            background-color: rgba(255, 190, 64, 0.1);
            border-color: #ffbe40;
            color: #ffbe40;
        }
        .card-title {
            color: #ffbe40;
        }
        .hover-glow:hover {
            box-shadow: 0 0 12px rgba(255,190,64,0.4);
            transition: 0.3s ease-in-out;
        }
        .badge {
            font-size: 0.75rem;
        }
    </style>

    <!-- Header -->
    <div class="py-5 mb-5 text-center bg-black border-bottom border-secondary">
        <div class="container">
            <h1 class="fw-bold mb-2 text-light">Deposit {{ $cryptocurrency->name ?? 'Cryptocurrency' }}</h1>
            <p class="text-muted fs-5">
                Send <span class="accent">{{ strtoupper($cryptocurrency->symbol ?? '') }}</span> to your wallet address
            </p>
        </div>
    </div>

    <main class="container pb-5">
        <div class="row g-4">
            <!-- Deposit Information -->
            <div class="col-lg-6">
                <div class="card p-4 hover-glow">
                    <h2 class="h5 card-title mb-4 fw-semibold">Deposit Information</h2>

                    <div class="mb-4">
                        <label class="form-label small text-secondary">Wallet Address</label>
                        <div class="input-group">
                            <input type="text" id="depositAddress"
                                class="form-control"
                                readonly
                                value="{{ $wallet->address ?? 'Generating address...' }}">
                            <button onclick="copyAddress()" class="btn btn-accent fw-semibold">
                                <i class="fa fa-copy me-1"></i> Copy
                            </button>
                        </div>
                        @if(!$wallet->address)
                        <small class="text-warning mt-2 d-block">
                            <i class="fa fa-exclamation-triangle me-1"></i>
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

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-secondary">Minimum Deposit</label>
                            <div class="form-control bg-transparent">
                                {{ number_format($cryptocurrency->min_deposit_amount ?? 0.001, 8) }} {{ strtoupper($cryptocurrency->symbol ?? '') }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-secondary">Current Balance</label>
                            <div class="form-control bg-transparent">
                                {{ number_format($wallet->balance ?? 0, 8) }} {{ strtoupper($cryptocurrency->symbol ?? '') }}
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <div class="border border-secondary rounded p-4 d-inline-block bg-dark-subtle bg-opacity-10">
                            <div class="d-flex align-items-center justify-content-center rounded bg-dark"
                                 style="width: 160px; height: 160px;">
                                <i class="fa fa-qrcode fs-1 text-secondary"></i>
                            </div>
                            <p class="text-secondary small mt-3 mb-0">QR Code (Coming Soon)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deposit Instructions -->
            <div class="col-lg-6">
                <div class="card p-4 hover-glow">
                    <h2 class="h5 card-title mb-4 fw-semibold">How to Deposit</h2>

                    @php
                        $steps = [
                            ['icon'=>'fa fa-copy', 'title'=>'Copy Address', 'text'=>'Copy the wallet address above or scan the QR code'],
                            ['icon'=>'fa fa-paper-plane', 'title'=>'Send Funds', 'text'=>'Send '. strtoupper($cryptocurrency->symbol ?? '') .' from your external wallet or exchange'],
                            ['icon'=>'fa fa-clock', 'title'=>'Confirm', 'text'=>'Wait for network confirmation (usually 1–3 confirmations)'],
                            ['icon'=>'fa fa-wallet', 'title'=>'Receive', 'text'=>'Funds will appear in your wallet after confirmation']
                        ];
                    @endphp

                    @foreach($steps as $i => $step)
                    <div class="d-flex align-items-start mb-4">
                        <div class="rounded-circle bg-accent bg-opacity-25 text-dark fw-bold d-flex align-items-center justify-content-center me-3"
                            style="width: 44px; height: 44px;">
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
                            <i class="fa fa-exclamation-triangle me-2"></i> Important Notice
                        </h6>
                        <ul class="small mb-0 ps-3">
                            <li>- Only send {{ strtoupper($cryptocurrency->symbol ?? '') }} to this address</li>
                            <li>- Deposits require network confirmations</li>
                            <li>- Minimum deposit: {{ number_format($cryptocurrency->min_deposit_amount ?? 0.001, 8) }} {{ strtoupper($cryptocurrency->symbol ?? '') }}</li>
                            <li>- Ensure you’re using the correct network</li>
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
            <div class="d-flex align-items-center justify-content-between border rounded p-3 mb-3 bg-dark bg-opacity-50">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-25 text-success d-flex align-items-center justify-content-center me-3"
                        style="width: 48px; height: 48px;">
                        <i class="fa fa-arrow-down"></i>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-0 text-light">Deposit</h6>
                        <small class="text-secondary">{{ $deposit->created_at->format('M j, Y g:i A') }}</small>
                    </div>
                </div>
                <div class="text-end">
                    <div class="fw-bold accent">
                        +{{ number_format($deposit->amount, 8) }}
                    </div>
                    <small class="text-secondary">{{ strtoupper($cryptocurrency->symbol ?? '') }}</small><br>
                    <span class="badge rounded-pill
                        @if($deposit->status === 'completed') bg-success
                        @elseif($deposit->status === 'pending') bg-warning text-dark
                        @else bg-danger
                        @endif">
                        {{ ucfirst($deposit->status) }}
                    </span>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="fa fa-arrow-down fs-1 text-secondary mb-3"></i>
                <p class="text-muted">No deposits yet</p>
            </div>
            @endforelse
        </div>
    </main>

    <script>
        function copyAddress() {
            const addressInput = document.getElementById('depositAddress');
            addressInput.select();
            addressInput.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(addressInput.value).then(() => {
                const btn = event.target.closest('button');
                const original = btn.innerHTML;
                btn.innerHTML = '<i class="fa fa-check me-1"></i> Copied!';
                btn.classList.remove('btn-accent');
                btn.classList.add('btn-success');
                setTimeout(() => {
                    btn.innerHTML = original;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-accent');
                }, 2000);
            }).catch(() => alert('Address copied!'));
        }
    </script>


@endsection