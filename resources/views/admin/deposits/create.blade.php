@extends('admin.layouts.app')

@section('title', 'Create Manual Deposit')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Create Manual Deposit</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.deposits.index') }}">Deposits</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Deposit Information</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.deposits.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">User *</label>
                                    <select class="form-select @error('user_id') is-invalid @enderror" 
                                            id="user_id" name="user_id" required>
                                        <option value="">Select User</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" 
                                                    {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->email }} ({{ $user->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cryptocurrency_id" class="form-label">Cryptocurrency *</label>
                                    <select class="form-select @error('cryptocurrency_id') is-invalid @enderror" 
                                            id="cryptocurrency_id" name="cryptocurrency_id" required>
                                        <option value="">Select Cryptocurrency</option>
                                        @foreach($cryptocurrencies as $crypto)
                                            <option value="{{ $crypto->id }}" 
                                                    data-symbol="{{ $crypto->symbol }}"
                                                    data-decimals="{{ $crypto->decimals }}"
                                                    {{ old('cryptocurrency_id') == $crypto->id ? 'selected' : '' }}>
                                                {{ $crypto->name }} ({{ $crypto->symbol }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cryptocurrency_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount *</label>
                                    <input type="number" 
                                           class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" name="amount" 
                                           value="{{ old('amount') }}" 
                                           step="0.00000001" 
                                           min="0.00000001" 
                                           required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fee" class="form-label">Fee</label>
                                    <input type="number" 
                                           class="form-control @error('fee') is-invalid @enderror" 
                                           id="fee" name="fee" 
                                           value="{{ old('fee', 0) }}" 
                                           step="0.00000001" 
                                           min="0">
                                    <div class="form-text">Network fee (optional)</div>
                                    @error('fee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="transaction_hash" class="form-label">Transaction Hash *</label>
                                    <input type="text" 
                                           class="form-control @error('transaction_hash') is-invalid @enderror" 
                                           id="transaction_hash" name="transaction_hash" 
                                           value="{{ old('transaction_hash') }}" 
                                           required>
                                    @error('transaction_hash')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="confirmations" class="form-label">Confirmations</label>
                                    <input type="number" 
                                           class="form-control @error('confirmations') is-invalid @enderror" 
                                           id="confirmations" name="confirmations" 
                                           value="{{ old('confirmations', 0) }}" 
                                           min="0">
                                    <div class="form-text">Number of blockchain confirmations</div>
                                    @error('confirmations')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="required_confirmations" class="form-label">Required Confirmations</label>
                                    <input type="number" 
                                           class="form-control" 
                                           value="3" 
                                           disabled>
                                    <div class="form-text">Default required confirmations</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Admin Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            <div class="form-text">Internal notes about this deposit</div>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="hstack gap-2 justify-content-end">
                            <a href="{{ route('admin.deposits.index') }}" class="btn btn-light">Cancel</a>
                            <button class="btn btn-success" type="submit">Create Deposit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Wallet Balance</h4>
                </div>
                <div class="card-body">
                    <div id="wallet-balance">
                        <p class="text-muted">Select a user and cryptocurrency to view wallet balance</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Recent Deposits</h4>
                </div>
                <div class="card-body">
                    <div id="recent-deposits">
                        <p class="text-muted">Select a user to view recent deposits</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const userSelect = document.getElementById('user_id');
    const cryptoSelect = document.getElementById('cryptocurrency_id');
    const walletBalanceDiv = document.getElementById('wallet-balance');
    const recentDepositsDiv = document.getElementById('recent-deposits');

    function updateWalletInfo() {
        const userId = userSelect.value;
        const cryptoId = cryptoSelect.value;

        if (userId && cryptoId) {
            // Update wallet balance
            fetch(`/admin/deposits/user/${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const user = data.deposits[0]?.user;
                        const balance = user?.wallet_balance || 0;
                        
                        walletBalanceDiv.innerHTML = `
                            <h5>Current Balance</h5>
                            <p class="mb-0"><strong>${balance} ${document.querySelector(`#cryptocurrency_id option[value="${cryptoId}"]`)?.dataset.symbol || ''}</strong></p>
                        `;
                    }
                })
                .catch(error => console.error('Error:', error));

            // Update recent deposits
            fetch(`/admin/deposits/user/${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.deposits.length > 0) {
                        let depositsHtml = '<ul class="list-unstyled mb-0">';
                        data.deposits.forEach(deposit => {
                            depositsHtml += `
                                <li class="d-flex justify-content-between align-items-center mb-2">
                                    <span>${deposit.amount} ${deposit.cryptocurrency.symbol}</span>
                                    <span class="badge bg-${getStatusColor(deposit.status)}">${deposit.status}</span>
                                </li>
                            `;
                        });
                        depositsHtml += '</ul>';
                        recentDepositsDiv.innerHTML = depositsHtml;
                    } else {
                        recentDepositsDiv.innerHTML = '<p class="text-muted mb-0">No recent deposits found</p>';
                    }
                })
                .catch(error => console.error('Error:', error));
        } else {
            walletBalanceDiv.innerHTML = '<p class="text-muted">Select a user and cryptocurrency to view wallet balance</p>';
            recentDepositsDiv.innerHTML = '<p class="text-muted">Select a user to view recent deposits</p>';
        }
    }

    function getStatusColor(status) {
        const colors = {
            'pending': 'warning',
            'confirmed': 'info',
            'completed': 'success',
            'failed': 'danger'
        };
        return colors[status] || 'secondary';
    }

    userSelect.addEventListener('change', updateWalletInfo);
    cryptoSelect.addEventListener('change', updateWalletInfo);
});
</script>
@endsection