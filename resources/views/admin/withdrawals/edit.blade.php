@extends('admin.layouts.app')

@section('title', 'Edit Withdrawal')

@section('content')
<div class="w-full px-4 mx-auto">
    
    <!-- Page Title -->
    <div class="flex items-center justify-between mb-6">
        <h4 class="text-xl font-semibold">Edit Withdrawal #{{ $withdrawal->id }}</h4>

        <nav class="text-sm text-gray-500">
            <ol class="flex space-x-2">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-gray-700">Dashboard</a></li>
                <li>/</li>
                <li><a href="{{ route('admin.withdrawals.index') }}" class="hover:text-gray-700">Withdrawals</a></li>
                <li>/</li>
                <li class="text-gray-700">Edit</li>
            </ol>
        </nav>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        <!-- Left Section -->
        <div class="xl:col-span-2">

            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="border-b px-4 py-3">
                    <h4 class="font-semibold text-gray-700">Withdrawal Information</h4>
                </div>

                <div class="p-4">
                    <form action="{{ route('admin.withdrawals.update', $withdrawal) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">User</label>
                                <input type="text"
                                    class="w-full bg-gray-100 border border-gray-300 rounded-lg p-2 text-sm"
                                    value="{{ $withdrawal->user->email }} ({{ $withdrawal->user->name }})"
                                    readonly>
                                <p class="text-xs text-gray-500 mt-1">User cannot be changed</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Cryptocurrency *</label>
                                <select 
                                    id="cryptocurrency_id" 
                                    name="cryptocurrency_id"
                                    class="w-full border border-gray-300 rounded-lg p-2 text-sm"
                                    required>
                                    @foreach($cryptocurrencies as $crypto)
                                        <option value="{{ $crypto->id }}"
                                            data-symbol="{{ $crypto->symbol }}"
                                            data-decimals="{{ $crypto->decimals }}"
                                            {{ $withdrawal->cryptocurrency_id == $crypto->id ? 'selected' : '' }}>
                                            {{ $crypto->name }} ({{ $crypto->symbol }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('cryptocurrency_id') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                            </div>

                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium mb-1">Withdrawal Address *</label>
                            <input type="text" 
                                id="withdrawal_address" name="withdrawal_address"
                                class="w-full border border-gray-300 rounded-lg p-2 text-sm"
                                value="{{ old('withdrawal_address', $withdrawal->withdrawal_address) }}"
                                required>
                            @error('withdrawal_address') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">

                            <div>
                                <label class="block text-sm font-medium mb-1">Amount *</label>
                                <input type="number"
                                    id="amount" name="amount"
                                    class="w-full border border-gray-300 rounded-lg p-2 text-sm"
                                    step="0.00000001" min="0.00000001"
                                    value="{{ old('amount', $withdrawal->amount) }}" required>
                                @error('amount') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Fee</label>
                                <input type="number"
                                    id="fee" name="fee"
                                    class="w-full border border-gray-300 rounded-lg p-2 text-sm"
                                    step="0.00000001" min="0"
                                    value="{{ old('fee', $withdrawal->fee) }}">
                                <p class="text-xs text-gray-500 mt-1">Network fee (optional)</p>
                                @error('fee') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                            </div>

                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">

                            <div>
                                <label class="block text-sm font-medium mb-1">Status *</label>
                                <select 
                                    id="status" name="status"
                                    class="w-full border border-gray-300 rounded-lg p-2 text-sm"
                                    required>
                                    <option value="pending" {{ old('status', $withdrawal->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ old('status', $withdrawal->status) == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="completed" {{ old('status', $withdrawal->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="failed" {{ old('status', $withdrawal->status) == 'failed' ? 'selected' : '' }}>Failed</option>
                                    <option value="cancelled" {{ old('status', $withdrawal->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Transaction Hash</label>
                                <input type="text" 
                                    id="tx_hash" name="tx_hash"
                                    class="w-full border border-gray-300 rounded-lg p-2 text-sm"
                                    value="{{ old('tx_hash', $withdrawal->tx_hash) }}">
                                <p class="text-xs text-gray-500 mt-1">Required for completed withdrawals</p>
                                @error('tx_hash') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                            </div>

                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium mb-1">Admin Notes</label>
                            <textarea id="notes" name="notes" rows="3"
                                class="w-full border border-gray-300 rounded-lg p-2 text-sm">{{ old('notes', $withdrawal->admin_notes) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Internal notes about this withdrawal</p>
                            @error('notes') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>

                        <div id="balance-alert" class="hidden bg-blue-50 text-blue-700 p-2 rounded-md text-sm mt-3">
                            <span id="balance-text"></span>
                        </div>

                        <div class="flex justify-end gap-2 mt-5">
                            <a href="{{ route('admin.withdrawals.show', $withdrawal) }}"
                                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 text-sm">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded-md text-sm">
                                Update Withdrawal
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="space-y-5">

            <!-- Current Status -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="border-b px-4 py-3">
                    <h4 class="font-semibold text-gray-700">Current Status</h4>
                </div>
                <div class="p-4">
                    <div class="flex items-center space-x-2 mb-3">
                        <span class="px-3 py-1 text-sm font-medium rounded-lg
                            bg-{{ getStatusColor($withdrawal->status) }}-100
                            text-{{ getStatusColor($withdrawal->status) }}-700">
                            {{ ucfirst($withdrawal->status) }}
                        </span>
                        <small class="text-gray-500">{{ $withdrawal->updated_at->diffForHumans() }}</small>
                    </div>

                    @if($withdrawal->processed_at)
                        <p class="text-xs text-gray-500 mb-1">
                            Processed: {{ $withdrawal->processed_at->format('Y-m-d H:i:s') }}
                        </p>
                    @endif

                    @if($withdrawal->processed_by)
                        <p class="text-xs text-gray-500">
                            Processed by: {{ $withdrawal->processedBy->email ?? 'Unknown' }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Withdrawal Details -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="border-b px-4 py-3">
                    <h4 class="font-semibold text-gray-700">Withdrawal Details</h4>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500">Amount</p>
                            <p class="font-medium">{{ $withdrawal->amount }} {{ $withdrawal->cryptocurrency->symbol }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Fee</p>
                            <p class="font-medium">{{ $withdrawal->fee }} {{ $withdrawal->cryptocurrency->symbol }}</p>
                        </div>
                    </div>

                    <p class="text-xs text-gray-500 mt-2">Withdrawal Address</p>
                    <code class="text-xs block">{{ $withdrawal->withdrawal_address }}</code>

                    @if($withdrawal->tx_hash)
                        <p class="text-xs text-gray-500 mt-2">Transaction Hash</p>
                        <code class="text-xs block">{{ $withdrawal->tx_hash }}</code>
                    @endif
                </div>
            </div>

            <!-- Calculation -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="border-b px-4 py-3">
                    <h4 class="font-semibold text-gray-700">Calculation</h4>
                </div>
                <div class="p-4 text-sm">
                    <div class="flex justify-between mb-2">
                        <span>Amount:</span>
                        <span id="calc-amount">{{ number_format($withdrawal->amount, 8) }} {{ $withdrawal->cryptocurrency->symbol }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Fee:</span>
                        <span id="calc-fee">{{ number_format($withdrawal->fee, 8) }} {{ $withdrawal->cryptocurrency->symbol }}</span>
                    </div>
                    <hr class="my-2">
                    <div class="flex justify-between font-semibold">
                        <span>Total:</span>
                        <span id="calc-total">{{ number_format($withdrawal->amount + $withdrawal->fee, 8) }} {{ $withdrawal->cryptocurrency->symbol }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="border-b px-4 py-3">
                    <h4 class="font-semibold text-gray-700">Quick Actions</h4>
                </div>
                <div class="p-4 space-y-2">
                    
                    @if($withdrawal->status === 'pending')
                        <form action="{{ route('admin.withdrawals.approve', $withdrawal) }}" method="POST">
                            @csrf
                            <button type="submit"
                                onclick="return confirm('Approve this withdrawal?')"
                                class="w-full border border-green-600 text-green-600 px-3 py-2 rounded-md text-sm">
                                Quick Approve
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.withdrawals.show', $withdrawal) }}"
                        class="w-full block border border-blue-600 text-blue-600 px-3 py-2 rounded-md text-center text-sm">
                        View Details
                    </a>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.getElementById('amount');
    const feeInput = document.getElementById('fee');
    const cryptoSelect = document.getElementById('cryptocurrency_id');

    function updateCalculation() {
        const amount = parseFloat(amountInput.value) || 0;
        const fee = parseFloat(feeInput.value) || 0;
        const total = amount + fee;
        const cryptoSymbol = document.querySelector(`#cryptocurrency_id option[value="${cryptoSelect.value}"]`)?.dataset.symbol || '';

        document.getElementById('calc-amount').textContent = amount.toFixed(8) + ' ' + cryptoSymbol;
        document.getElementById('calc-fee').textContent = fee.toFixed(8) + ' ' + cryptoSymbol;
        document.getElementById('calc-total').textContent = total.toFixed(8) + ' ' + cryptoSymbol;
    }

    amountInput.addEventListener('input', updateCalculation);
    feeInput.addEventListener('input', updateCalculation);
});

function getStatusColor(status) {
    const colors = {
        'pending': 'warning',
        'processing': 'info',
        'completed': 'success',
        'failed': 'danger',
        'cancelled': 'secondary'
    };
    return colors[status] || 'secondary';
}
</script>
@endsection

@php
function getStatusColor($status) {
    $colors = [
        'pending' => 'warning',
        'processing' => 'info',
        'completed' => 'success',
        'failed' => 'danger',
        'cancelled' => 'secondary'
    ];
    return $colors[$status] ?? 'secondary';
}
@endphp