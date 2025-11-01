@extends('admin.layouts.app')

@section('title', 'Create Manual Withdrawal')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row justify-between mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Create Manual Withdrawal</h2>
            <nav class="text-sm text-gray-500 mt-2 md:mt-0">
                <ol class="flex space-x-2">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a> /</li>
                    <li><a href="{{ route('admin.withdrawals.index') }}" class="hover:text-blue-600">Withdrawals</a> /</li>
                    <li class="text-gray-800 font-semibold">Create</li>
                </ol>
            </nav>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Form --}}
            <div class="bg-white shadow-md rounded-lg p-6 lg:col-span-2 border">

                <h3 class="text-lg font-semibold mb-4 text-gray-800">Withdrawal Information</h3>
                
                <form id="withdrawalForm" method="POST" action="{{ route('admin.withdrawals.store') }}">
                    @csrf

                    {{-- User & Crypto --}}
                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600">User *</label>
                            <select name="user_id" id="user_id"
                                class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:ring-blue-600 focus:border-blue-600"
                                required>
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->email }} ({{ $user->name }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Cryptocurrency *</label>
                            <select name="cryptocurrency_id" id="cryptocurrency_id"
                                class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:ring-blue-600 focus:border-blue-600"
                                required>
                                <option value="">Select Cryptocurrency</option>
                                @foreach($cryptocurrencies as $crypto)
                                <option value="{{ $crypto->id }}" 
                                        data-symbol="{{ $crypto->symbol }}"
                                        data-decimals="{{ $crypto->decimals }}">
                                    {{ $crypto->name }} ({{ $crypto->symbol }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Withdrawal Address --}}
                    <div class="mb-4">
                        <label class="text-sm font-medium text-gray-600">Withdrawal Address *</label>
                        <input type="text" name="withdrawal_address"
                            class="w-full rounded-lg border-gray-300 text-sm mt-1 focus:ring-blue-600 focus:border-blue-600"
                            required>
                    </div>

                    {{-- Amount & Fee --}}
                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Amount *</label>
                            <input type="number" step="0.00000001" min="0.00000001"
                                id="amount" name="amount"
                                class="w-full rounded-lg border-gray-300 text-sm mt-1 focus:ring-blue-600 focus:border-blue-600"
                                required>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Fee</label>
                            <input type="number" step="0.00000001" min="0"
                                id="fee" name="fee" value="0"
                                class="w-full rounded-lg border-gray-300 text-sm mt-1 focus:ring-blue-600 focus:border-blue-600">
                            <small class="text-gray-500 text-xs">Optional network fee</small>
                        </div>
                    </div>

                    {{-- Status & Hash --}}
                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Status *</label>
                            <select name="status" id="status"
                                class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:ring-blue-600 focus:border-blue-600"
                                required>
                                <option value="">Select Status</option>
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="completed">Completed</option>
                                <option value="failed">Failed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Transaction Hash</label>
                            <input type="text" name="tx_hash" id="tx_hash"
                                class="w-full rounded-lg border-gray-300 text-sm mt-1 focus:ring-blue-600 focus:border-blue-600">
                            <small class="text-gray-500 text-xs">Required if completed</small>
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="mb-4">
                        <label class="text-sm font-medium text-gray-600">Admin Notes</label>
                        <textarea name="notes" rows="3"
                            class="w-full rounded-lg border-gray-300 text-sm mt-1 focus:ring-blue-600 focus:border-blue-600"></textarea>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.withdrawals.index') }}"
                            class="px-4 py-2 text-sm rounded bg-gray-200 hover:bg-gray-300 text-gray-700">
                            Cancel
                        </a>
                        <button class="px-4 py-2 text-sm rounded bg-green-600 text-white hover:bg-green-700">
                            Create Withdrawal
                        </button>
                    </div>
                </form>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">

                {{-- Wallet Balance --}}
                <div class="bg-white shadow-md rounded-lg p-4 border">
                    <h3 class="font-semibold text-gray-800 mb-2">Wallet Balance</h3>
                    <div id="wallet-balance" class="text-sm text-gray-500">
                        Select user & crypto to view balance
                    </div>
                </div>

                {{-- Recent Withdrawals --}}
                <div class="bg-white shadow-md rounded-lg p-4 border">
                    <h3 class="font-semibold text-gray-800 mb-2">Recent Withdrawals</h3>
                    <div id="recent-withdrawals" class="text-sm text-gray-500">
                        Select user to view history
                    </div>
                </div>

                {{-- Calculation --}}
                <div class="bg-white shadow-md rounded-lg p-4 border">
                    <h3 class="font-semibold text-gray-800 mb-2">Calculation</h3>

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span>Amount:</span> <span id="calc-amount">0.00000000</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Fee:</span> <span id="calc-fee">0.00000000</span>
                        </div>
                        <hr>
                        <div class="flex justify-between font-semibold">
                            <span>Total:</span> <span id="calc-total">0.00000000</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection


@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {

    const user = document.getElementById("user_id");
    const crypto = document.getElementById("cryptocurrency_id");
    const amount = document.getElementById("amount");
    const fee = document.getElementById("fee");

    const calcAmount = document.getElementById("calc-amount");
    const calcFee = document.getElementById("calc-fee");
    const calcTotal = document.getElementById("calc-total");

    function calculate() {
        const amt = parseFloat(amount.value) || 0;
        const f = parseFloat(fee.value) || 0;
        const total = amt + f;

        const symbol = crypto.selectedOptions[0]?.dataset.symbol || "";

        calcAmount.textContent = amt.toFixed(8) + " " + symbol;
        calcFee.textContent = f.toFixed(8) + " " + symbol;
        calcTotal.textContent = total.toFixed(8) + " " + symbol;
    }

    async function fetchInfo() {
        const userId = user.value;
        const cryptoId = crypto.value;

        if (!userId || !cryptoId) return;

        // Wallet balance
        fetch(`/admin/wallet/${userId}/${cryptoId}`)
            .then(r => r.json())
            .then(data => {
                document.getElementById("wallet-balance").innerHTML =
                    data.success ? `${data.balance} ${data.symbol}` : "N/A";
            });

        // Recent withdrawals
        fetch(`/admin/withdrawals/user/${userId}`)
            .then(r => r.json())
            .then(data => {
                if (!data.withdrawals?.length) {
                    document.getElementById("recent-withdrawals").textContent = "No withdrawals found";
                    return;
                }

                let html = "<ul class='space-y-1'>";
                data.withdrawals.forEach(w => {
                    html += `<li class="flex justify-between text-sm">
                        <span>${w.amount} ${w.symbol}</span>
                        <span class="text-gray-500">${w.status}</span>
                    </li>`;
                });
                html += "</ul>";

                document.getElementById("recent-withdrawals").innerHTML = html;
            });
    }

    amount.addEventListener("input", calculate);
    fee.addEventListener("input", calculate);
    crypto.addEventListener("change", () => { calculate(); fetchInfo(); });
    user.addEventListener("change", fetchInfo);

    calculate();
});
</script>
@endsection
