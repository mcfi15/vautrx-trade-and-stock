@extends('layouts.app')

@section('title', 'Deposit ' . ($cryptocurrency->name ?? 'Cryptocurrency'))

@section('content')
    <style>
        body {
            background-color: #0d1117;
            color: #e6edf3;
            font-family: 'Inter', sans-serif;
        }

        .accent {
            color: #1e90ff !important;
        }

        .bg-accent {
            background-color: #1e90ff !important;
        }

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

        .form-control,
        .input-group-text {
            background-color: #0d1117;
            border: 1px solid #30363d;
            color: #e6edf3;
        }

        .form-control:focus {
            border-color: #1e90ff;
            box-shadow: 0 0 0 0.25rem rgba(255, 190, 64, 0.25);
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

    <div class="py-5 mb-5 text-center bg-black border-bottom border-secondary">
        <div class="container">
            <h1 class="fw-bold mb-2 text-light">Deposit {{ $cryptocurrency->name ?? 'Cryptocurrency' }}</h1>
            <p class="text-muted fs-5">
                Select a payment method and submit your deposit
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
            <!-- Deposit Form -->
            <div class="col-lg-6 mb-4">
                <div class="card p-4 hover-glow">
                    <h2 class="h5 card-title mb-4 fw-semibold">Submit Deposit</h2>

                    <form method="POST" action="{{ route('wallet.deposit.submit', $cryptocurrency->id) }}" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label for="method_id" class="form-label small text-secondary">Select Payment Method</label>
        <select name="method_id" id="method_id" class="form-control" required>
            <option value="">-- Select Method --</option>
            @foreach($paymentMethods as $method)
                <option 
                    value="{{ $method->id }}"
                    data-type="{{ $method->type }}"
                    data-address="{{ $method->address }}"
                    data-bank="{{ $method->bank_name }}"
                    data-acc-num="{{ $method->account_number }}"
                    data-acc-name="{{ $method->account_name }}"
                    data-swift="{{ $method->swift_code }}"
                >
                    {{ $method->name }} {{ $method->type === 'bank' ? '(Bank Transfer)' : '' }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- WALLET ADDRESS BOX (Crypto) -->
    <div id="walletBox" class="mb-3 d-none">
        <label class="form-label small text-secondary">Wallet Address</label>
        <div class="input-group">
            <input type="text" id="walletAddress" class="form-control" readonly>
            <button type="button" class="btn btn-outline-secondary" id="copyBtn">
                <i class="fa fa-copy"></i> Copy
            </button>
        </div>
    </div>

    <!-- BANK DETAILS BOX (Bank) -->
    <div id="bankBox" class="mb-3 d-none bg-dark p-3 border border-secondary rounded">
        <label class="form-label small text-white fw-bold">Bank Transfer Details</label>
        <div class="text-light small">
            <p class="mb-1 text-white"><strong>Bank:</strong> <span id="displayBank"></span></p>
            <p class="mb-1 text-white"><strong>Account Name:</strong> <span id="displayAccName"></span></p>
            <p class="mb-1 text-white"><strong>Account Number:</strong> <span id="displayAccNum"></span></p>
            <p class="mb-0 text-white"><strong>SWIFT/BIC:</strong> <span id="displaySwift"></span></p>
        </div>
    </div>

    <div class="mb-3">
        <label for="amount" class="form-label small text-secondary">Deposit Amount ({{ strtoupper($cryptocurrency->symbol) }})</label>
        <input type="number" step="0.00000001" class="form-control" name="amount" id="amount" required>
    </div>

    <button type="submit" class="btn btn-accent fw-semibold w-100">
        <i class="fa fa-upload me-1"></i> Submit Deposit
    </button>
</form>

<script>
    document.getElementById('method_id').addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        const type = selected.getAttribute('data-type');
        
        // Elements
        const walletBox = document.getElementById('walletBox');
        const bankBox = document.getElementById('bankBox');
        
        // Reset
        walletBox.classList.add('d-none');
        bankBox.classList.add('d-none');

        if (type === 'crypto') {
            document.getElementById('walletAddress').value = selected.getAttribute('data-address');
            walletBox.classList.remove('d-none');
        } else if (type === 'bank') {
            document.getElementById('displayBank').innerText = selected.getAttribute('data-bank');
            document.getElementById('displayAccName').innerText = selected.getAttribute('data-acc-name');
            document.getElementById('displayAccNum').innerText = selected.getAttribute('data-acc-num');
            document.getElementById('displaySwift').innerText = selected.getAttribute('data-swift') || 'N/A';
            bankBox.classList.remove('d-none');
        }
    });

    // Copy function remains the same
    document.getElementById('copyBtn').addEventListener('click', function () {
        const addr = document.getElementById('walletAddress');
        navigator.clipboard.writeText(addr.value);
        alert('Address copied!');
    });
</script>

                </div>
            </div>

            <!-- Recent Deposits -->
            <div class="col-lg-6 mb-4">
                <div class="card p-4 hover-glow">
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
                                    <small class="text-secondary">
                                        @if($deposit->paymentMethod)
                                            {{ $deposit->paymentMethod->name }} • {{ $deposit->created_at->format('M j, Y g:i A') }}
                                        @else
                                            N/A • {{ $deposit->created_at->format('M j, Y g:i A') }}
                                        @endif
                                    </small>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="fw-bold accent">+{{ number_format($deposit->amount, 8) }}</div>
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
            </div>
        </div>
    </main>
@endsection