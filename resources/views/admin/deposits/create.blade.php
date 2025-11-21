@extends('admin.layouts.app')

@section('title', 'Create Manual Deposit')

@section('content')
<div class="container-fluid mx-auto px-4">
    <div class="flex flex-wrap -mx-4">
        <div class="w-full px-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between py-4">
                <h4 class="text-lg font-semibold text-gray-900 mb-2 sm:mb-0">Create Manual Deposit</h4>
                <div class="text-sm">
                    <ol class="flex items-center space-x-2">
                        <li class="flex items-center">
                            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800 transition-colors">Dashboard</a>
                            <svg class="w-4 h-4 mx-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </li>
                        <li class="flex items-center">
                            <a href="{{ route('admin.deposits.index') }}" class="text-blue-600 hover:text-blue-800 transition-colors">Deposits</a>
                            <svg class="w-4 h-4 mx-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </li>
                        <li class="text-gray-500">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap -mx-4">
        <div class="w-full lg:w-8/12 px-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h4 class="text-lg font-semibold text-gray-900">Deposit Information</h4>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.deposits.store') }}" method="POST">
                        @csrf
                        
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full md:w-1/2 px-3 mb-4 md:mb-0">
                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">User *</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('user_id') border-red-500 @enderror" 
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
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="w-full md:w-1/2 px-3">
                                <label for="cryptocurrency_id" class="block text-sm font-medium text-gray-700 mb-2">Cryptocurrency *</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('cryptocurrency_id') border-red-500 @enderror" 
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
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full md:w-1/2 px-3 mb-4 md:mb-0">
                                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount *</label>
                                <input type="number" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('amount') border-red-500 @enderror" 
                                       id="amount" name="amount" 
                                       value="{{ old('amount') }}" 
                                       step="0.00000001" 
                                       min="0.00000001" 
                                       required>
                                @error('amount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="w-full md:w-1/2 px-3">
                                <label for="fee" class="block text-sm font-medium text-gray-700 mb-2">Fee</label>
                                <input type="number" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('fee') border-red-500 @enderror" 
                                       id="fee" name="fee" 
                                       value="{{ old('fee', 0) }}" 
                                       step="0.00000001" 
                                       min="0">
                                <p class="mt-1 text-sm text-gray-500">Network fee (optional)</p>
                                @error('fee')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full md:w-1/2 px-3 mb-4 md:mb-0">
                                <label for="transaction_hash" class="block text-sm font-medium text-gray-700 mb-2">Transaction Hash *</label>
                                <input type="text" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('transaction_hash') border-red-500 @enderror" 
                                       id="transaction_hash" name="transaction_hash" 
                                       value="{{ old('transaction_hash') }}" 
                                       required>
                                @error('transaction_hash')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="w-full md:w-1/2 px-3">
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror" 
                                        id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full md:w-1/2 px-3 mb-4 md:mb-0">
                                <label for="confirmations" class="block text-sm font-medium text-gray-700 mb-2">Confirmations</label>
                                <input type="number" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('confirmations') border-red-500 @enderror" 
                                       id="confirmations" name="confirmations" 
                                       value="{{ old('confirmations', 0) }}" 
                                       min="0">
                                <p class="mt-1 text-sm text-gray-500">Number of blockchain confirmations</p>
                                @error('confirmations')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="w-full md:w-1/2 px-3">
                                <label for="required_confirmations" class="block text-sm font-medium text-gray-700 mb-2">Required Confirmations</label>
                                <input type="number" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed" 
                                       value="3" 
                                       disabled>
                                <p class="mt-1 text-sm text-gray-500">Default required confirmations</p>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Internal notes about this deposit</p>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-wrap gap-3 justify-end">
                            <a href="{{ route('admin.deposits.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                Cancel
                            </a>
                            <button class="px-4 py-2 border border-transparent rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" type="submit">
                                Create Deposit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-4/12 px-4 space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h4 class="text-lg font-semibold text-gray-900">Wallet Balance</h4>
                </div>
                <div class="p-6">
                    <div id="wallet-balance">
                        <p class="text-gray-500">Select a user and cryptocurrency to view wallet balance</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h4 class="text-lg font-semibold text-gray-900">Recent Deposits</h4>
                </div>
                <div class="p-6">
                    <div id="recent-deposits">
                        <p class="text-gray-500">Select a user to view recent deposits</p>
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
                            <h5 class="font-semibold text-gray-900 mb-2">Current Balance</h5>
                            <p class="text-gray-900"><strong>${balance} ${document.querySelector(`#cryptocurrency_id option[value="${cryptoId}"]`)?.dataset.symbol || ''}</strong></p>
                        `;
                    }
                })
                .catch(error => console.error('Error:', error));

            // Update recent deposits
            fetch(`/admin/deposits/user/${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.deposits.length > 0) {
                        let depositsHtml = '<ul class="space-y-3">';
                        data.deposits.forEach(deposit => {
                            depositsHtml += `
                                <li class="flex justify-between items-center">
                                    <span class="text-gray-700">${deposit.amount} ${deposit.cryptocurrency.symbol}</span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full ${getStatusClasses(deposit.status)}">${deposit.status}</span>
                                </li>
                            `;
                        });
                        depositsHtml += '</ul>';
                        recentDepositsDiv.innerHTML = depositsHtml;
                    } else {
                        recentDepositsDiv.innerHTML = '<p class="text-gray-500">No recent deposits found</p>';
                    }
                })
                .catch(error => console.error('Error:', error));
        } else {
            walletBalanceDiv.innerHTML = '<p class="text-gray-500">Select a user and cryptocurrency to view wallet balance</p>';
            recentDepositsDiv.innerHTML = '<p class="text-gray-500">Select a user to view recent deposits</p>';
        }
    }

    function getStatusClasses(status) {
        const classes = {
            'pending': 'bg-yellow-100 text-yellow-800',
            'confirmed': 'bg-blue-100 text-blue-800',
            'completed': 'bg-green-100 text-green-800',
            'failed': 'bg-red-100 text-red-800'
        };
        return classes[status] || 'bg-gray-100 text-gray-800';
    }

    userSelect.addEventListener('change', updateWalletInfo);
    cryptoSelect.addEventListener('change', updateWalletInfo);
});
</script>
@endsection