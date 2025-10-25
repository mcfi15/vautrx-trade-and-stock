@extends('layouts.app')

@section('title', 'Select Cryptocurrency for Deposit')

@section('content')

<div class="container py-5 text-light" style="background-color: #0d1117; min-height: 100vh;">
    <!-- Header -->
    <div class="mb-5">
        <a href="{{ route('wallet.index') }}" class="text-decoration-none text-warning fw-semibold mb-3 d-inline-flex align-items-center">
            <i class="fa fa-arrow-left me-2"></i> Back to Wallet
        </a>
        <h1 class="fw-bold display-6">Select Cryptocurrency for Deposit</h1>
        <p class="text-white">Choose which cryptocurrency you'd like to deposit to your wallet.</p>
    </div>

    <!-- Quick Action Buttons -->
    <div class="mb-4 d-flex flex-wrap gap-3">
        <a href="{{ route('wallet.deposit') }}" class="btn btn-warning text-dark fw-semibold">
            <i class="fa fa-plus-circle me-2"></i> Deposit
        </a>
        <a href="{{ route('wallet.withdraw') }}" class="btn btn-outline-light">
            <i class="fa fa-minus-circle me-2"></i> Withdraw
        </a>
        <a href="{{ route('wallet.transactions') }}" class="btn btn-outline-light">
            <i class="fa fa-history me-2"></i> Transaction History
        </a>
    </div>

    <!-- Search & Filter -->
        <div class="bg-transparent border-secondary p-4">
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent text-light border-secondary">
                            <i class="fa fa-search"></i>
                        </span>
                        <input type="text" id="search" class="form-control bg-transparent text-light border-secondary"
                               placeholder="Search cryptocurrencies..." onkeyup="filterCryptocurrencies()">
                    </div>
                </div>
                <div class="col-md-4">
                    <select id="statusFilter" class="form-control bg-transparent text-light border-secondary"
                            onchange="filterCryptocurrencies()">
                        <option value="">All Status</option>
                        <option value="active">Active Only</option>
                        <option value="inactive">Inactive Only</option>
                    </select>
                </div>
            </div>
        </div>

    <!-- Cryptocurrencies Grid -->
    <div class="card bg-dark border-0 shadow-lg rounded-4 mb-4">
        <div class="card-header border-secondary bg-transparent">
            <h4 class="fw-bold mb-0 text-warning">Available Cryptocurrencies</h4>
            <small class="text-white">Click on any cryptocurrency to get the deposit address</small>
        </div>

        @if($cryptocurrencies->count() > 0)
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 p-4 " style="background-color: black;">
            @foreach($cryptocurrencies as $crypto)
            <div class="col p-5">
                <div class="card h-100 bg-dark border border-secondary rounded-4 shadow-sm crypto-card" 
                     onclick="location.href='{{ route('wallet.deposit.specific', $crypto->id) }}'">
                    <div class="card-body text-center">
                        <!-- Crypto Icon -->
                        <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center text-dark fw-bold fs-4"
                            style="width:70px; height:70px; background-color:#ffbe40; box-shadow:0 0 15px rgba(255,190,64,0.5);">
                            {{ strtoupper(substr($crypto->symbol, 0, 2)) }}
                        </div>

                        <!-- Crypto Info -->
                        <h5 class="fw-semibold text-light">{{ $crypto->name }}</h5>
                        <p class="text-muted small mb-3">{{ strtoupper($crypto->symbol) }}</p>

                        <div class="text-start px-3">
                            <div class="d-flex justify-content-between small mb-2">
                                <span class="text-white">Price:</span>
                                <span class="fw-semibold">${{ number_format($crypto->current_price ?? 0, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between small">
                                <span class="text-white">Status:</span>
                                @if($crypto->is_active)
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </div>
                        </div>

                        <hr class="border-secondary my-3">
                        <button class="btn btn-outline-warning btn-sm w-100 fw-semibold">
                            <i class="fa fa-arrow-down me-2"></i> Deposit {{ strtoupper($crypto->symbol) }}
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        

        @else
        <div class="text-center py-5">
            <i class="fa fa-coins text-white fs-1 mb-3"></i>
            <h5>No Cryptocurrencies Available</h5>
            <p class="text-muted">Contact support to enable cryptocurrency deposits.</p>
            <a href="{{ route('dashboard') }}" class="btn btn-warning text-dark fw-semibold">
                Return to Dashboard
            </a>
        </div>
        @endif
    </div>

    <!-- Help Section -->
    <div class="card bg-dark border-warning mt-5 rounded-4">
        <div class="card-body d-flex align-items-start">
            <i class="fa fa-info-circle text-warning fs-4 me-3 mt-1"></i>
            <div>
                <h5 class="text-warning fw-bold mb-3">How to Deposit</h5>
                <ul class="text-white small ps-3 mb-0">
                    <li>Select the cryptocurrency you want to deposit.</li>
                    <li>Copy the generated wallet address or scan the QR code.</li>
                    <li>Send the cryptocurrency from your external wallet or exchange.</li>
                    <li>Wait for network confirmations (usually 1–3 confirmations).</li>
                    <li>Your balance will be updated automatically once confirmed.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function filterCryptocurrencies() {
    const search = document.getElementById('search').value.toLowerCase();
    const status = document.getElementById('statusFilter').value;
    const cards = document.querySelectorAll('.crypto-card');
    cards.forEach(card => {
        const name = card.querySelector('h5').textContent.toLowerCase();
        const symbol = card.querySelector('p').textContent.toLowerCase();
        const statusText = card.querySelector('.badge').textContent.toLowerCase();
        const matchesSearch = name.includes(search) || symbol.includes(search);
        const matchesStatus = !status || statusText.includes(status);
        card.style.display = (matchesSearch && matchesStatus) ? 'block' : 'none';
    });
}

// Subtle hover animation
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.crypto-card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-5px)';
            card.style.transition = 'all 0.2s ease';
            card.style.boxShadow = '0 0 25px rgba(255,190,64,0.2)';
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
            card.style.boxShadow = '0 0 10px rgba(255,190,64,0.05)';
        });
    });
});
</script>

<style>
body {
    background-color: #0d1117;
    color: #e6e6e6;
    font-family: 'Inter', sans-serif;
}
.card {
    transition: 0.3s ease-in-out;
}
.card:hover {
    border-color: #ffbe40 !important;
}
.btn-warning {
    background-color: #ffbe40 !important;
    border-color: #ffbe40 !important;
}
.btn-warning:hover {
    background-color: #e0a930 !important;
}
input::placeholder {
    color: #999 !important;
}
</style>

@endsection
