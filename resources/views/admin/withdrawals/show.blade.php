@extends('admin.layouts.app')
@section('title', 'Withdrawal Details')

@section('content')
<div x-data="{ approveModal:false, completeModal:false, rejectModal:false }" class="w-full min-h-screen bg-gray-50 px-6 py-6">

    @if(session('success'))
    <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-4 flex justify-between">
        <span><i class="fas fa-check-circle"></i> {{ session('success') }}</span>
        <button onclick="this.parentNode.remove()" class="text-green-800">&times;</button>
    </div>
    @endif

    {{-- Error --}}
    @if(session('error'))
    <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-4 flex justify-between">
        <span><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</span>
        <button onclick="this.parentNode.remove()" class="text-red-800">&times;</button>
    </div>
    @endif
    {{-- Page Header --}}
    <div class="max-w-7xl mx-auto mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Withdrawal #{{ $withdrawal->id }}</h1>
            <nav class="text-sm text-gray-500 mt-2 md:mt-0">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a> /
                <a href="{{ route('admin.withdrawals.index') }}" class="hover:text-blue-600">Withdrawals</a> /
                <span class="text-gray-700 font-semibold">Details</span>
            </nav>
        </div>
    </div>

    {{-- Main Layout --}}
    <div class="max-w-7xl mx-auto grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Main Card --}}
        <div class="xl:col-span-2 bg-white rounded-lg shadow border">
            
            {{-- Header --}}
            <div class="flex items-center justify-between border-b px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Withdrawal Information</h2>

                <div class="flex items-center gap-2">
                    {{-- Status Badge --}}
                    @php
                        $color = match($withdrawal->status) {
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'processing' => 'bg-blue-100 text-blue-700',
                            'completed' => 'bg-green-100 text-green-700',
                            'cancelled', 'failed' => 'bg-red-100 text-red-600',
                            default => 'bg-gray-200 text-gray-700'
                        };
                    @endphp
                    <span class="px-3 py-1 rounded text-xs font-bold {{ $color }}">
                        {{ ucfirst($withdrawal->status) }}
                    </span>

                    <a href="{{ route('admin.withdrawals.edit', $withdrawal) }}"
                        class="px-3 py-1 rounded border text-blue-600 border-blue-500 text-sm hover:bg-blue-600 hover:text-white transition">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                </div>
            </div>

            {{-- Body --}}
            <div class="px-6 py-6 space-y-6">

                {{-- User & Crypto --}}
                <div class="grid md:grid-cols-2 gap-6">
                    
                    <div>
                        <h3 class="text-gray-500 font-semibold text-sm mb-2">User Information</h3>
                        <p><b>Email:</b> {{ $withdrawal->user->email }}</p>
                        <p><b>Name:</b> {{ $withdrawal->user->name }}</p>
                        <p><b>User ID:</b> #{{ $withdrawal->user->id }}</p>
                    </div>

                    <div>
                        <h3 class="text-gray-500 font-semibold text-sm mb-2">Cryptocurrency</h3>
                        <p><b>Symbol:</b> {{ $withdrawal->cryptocurrency->symbol }}</p>
                        <p><b>Name:</b> {{ $withdrawal->cryptocurrency->name }}</p>
                        <p><b>Network:</b> {{ $withdrawal->cryptocurrency->network ?? 'N/A' }}</p>
                    </div>

                </div>

                <hr>

                {{-- Amount --}}
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-gray-500 font-semibold text-sm mb-2">Amount Details</h3>
                        <p><b>Amount:</b> {{ number_format($withdrawal->amount, 8) }} {{ $withdrawal->cryptocurrency->symbol }}</p>
                        <p><b>Fee:</b> {{ number_format($withdrawal->fee, 8) }} {{ $withdrawal->cryptocurrency->symbol }}</p>
                        <p><b>Total:</b> {{ number_format($withdrawal->amount + $withdrawal->fee, 8) }} {{ $withdrawal->cryptocurrency->symbol }}</p>
                    </div>
                    <div>
                        <h3 class="text-gray-500 font-semibold text-sm mb-2">Addresses & Hash</h3>
                        <b>Withdrawal Address:</b>
                        <code class="block bg-gray-100 p-2 rounded text-xs break-all">{{ $withdrawal->withdrawal_address }}</code>

                        @if($withdrawal->tx_hash)
                            <b class="mt-2 block">Transaction Hash:</b>
                            <code class="block bg-gray-100 p-2 rounded text-xs break-all">{{ $withdrawal->tx_hash }}</code>
                        @else
                            <p><b>Transaction Hash:</b> <span class="text-gray-500">Not provided</span></p>
                        @endif
                    </div>
                </div>

                <hr>

                <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Timeline</h6>
                            <p class="mb-1"><strong>Created:</strong> {{ $withdrawal->created_at->format('Y-m-d H:i:s') }}</p>
                            <p class="mb-1"><strong>Updated:</strong> {{ $withdrawal->updated_at->format('Y-m-d H:i:s') }}</p>
                            @if($withdrawal->processed_at)
                                <p class="mb-3"><strong>Processed:</strong> {{ $withdrawal->processed_at->format('Y-m-d H:i:s') }}</p>
                            @else
                                <p class="mb-3"><strong>Processed:</strong> <span class="text-muted">Not processed</span></p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Processing</h6>
                            @if($withdrawal->processed_at)
                                <p class="mb-1"><strong>Processed at:</strong> {{ $withdrawal->processed_at->format('Y-m-d H:i:s') }}</p>
                            @else
                                <p class="mb-1"><strong>Processed at:</strong> <span class="text-muted">Not processed</span></p>
                            @endif
                            <p class="mb-3"><strong>Status:</strong> <span class="badge badge-{{ $withdrawal->status == 'completed' ? 'success' : ($withdrawal->status == 'failed' ? 'danger' : 'warning') }}">{{ ucfirst($withdrawal->status) }}</span></p>
                        </div>
                    </div>

                {{-- Notes --}}
                @if($withdrawal->admin_notes)
                    <hr>
                    <div>
                        <h3 class="text-gray-500 font-semibold text-sm mb-2">Admin Notes</h3>
                        <div class="bg-gray-100 p-3 rounded text-sm">{{ $withdrawal->admin_notes }}</div>
                    </div>
                @endif

            </div>
        </div>

        {{-- Side Column --}}
        <div class="space-y-6">

            {{-- Action Buttons --}}
            <div class="bg-white rounded-lg shadow border p-5">
                <h3 class="font-semibold text-gray-800 text-lg mb-4">Quick Actions</h3>

                @if($withdrawal->status === 'pending')
                    <button @click="approveModal=true" class="btn-primary w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded mb-2">
                        Approve Withdrawal
                    </button>

                    <button @click="completeModal=true" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mb-2">
                        Mark as Completed
                    </button>

                    <button @click="rejectModal=true" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded mb-2">
                        Reject Withdrawal
                    </button>
                @endif

                @if($withdrawal->status === 'processing')
                    <button @click="completeModal=true" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mb-2">
                        Mark as Completed
                    </button>
                    <button @click="rejectModal=true" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded mb-2">
                        Cancel Withdrawal
                    </button>
                @endif

                <a href="{{ route('admin.withdrawals.edit', $withdrawal) }}"
                   class="block w-full text-center border text-blue-600 border-blue-500 px-4 py-2 rounded hover:bg-blue-600 hover:text-white transition">
                    Edit Details
                </a>
            </div>

            {{-- Transaction --}}
            @if($withdrawal->transaction)
            <div class="bg-white rounded-lg shadow border p-5">
                <h3 class="font-semibold text-gray-800 text-lg mb-4">Transaction Details</h3>
                <p><b>ID:</b> #{{ $withdrawal->transaction->id }}</p>
                <p><b>Type:</b> {{ ucfirst($withdrawal->transaction->type) }}</p>
                <p><b>Status:</b> {{ $withdrawal->transaction->status }}</p>
                <p><b>Before:</b> {{ $withdrawal->transaction->balance_before }}</p>
                <p><b>After:</b> {{ $withdrawal->transaction->balance_after }}</p>
            </div>
            @endif

            {{-- User --}}
            <div class="bg-white rounded-lg shadow border p-5 text-center">
                <div class="w-20 h-20 mx-auto rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-3xl">
                    {{ strtoupper(substr($withdrawal->user->name, 0, 1)) }}
                </div>
                <p class="mt-3 font-semibold text-gray-800">{{ $withdrawal->user->name }}</p>
                <p class="text-gray-500">{{ $withdrawal->user->email }}</p>
                <p class="text-xs text-gray-400 mt-1">Joined: {{ $withdrawal->user->created_at->format('M d, Y') }}</p>
            </div>

        </div>
    </div>

    {{-- ✅ Modals --}}
    
    {{-- Approve Modal --}}
    @if($withdrawal->status === 'pending')
    <div x-show="approveModal" x-transition class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg w-full max-w-md">
            <h3 class="text-lg font-semibold mb-3">Approve Withdrawal</h3>
            <form action="{{ route('admin.withdrawals.approve', $withdrawal) }}" method="POST">
                @csrf

                <label class="block font-medium text-sm mb-1">Admin Notes</label>
                <textarea name="admin_notes" rows="3" class="w-full border rounded p-2 mb-3"></textarea>

                <label class="block font-medium text-sm mb-1">Transaction Hash (optional)</label>
                <input name="tx_hash" class="border rounded w-full p-2 mb-3">

                <div class="flex justify-end gap-2">
                    <button type="button" @click="approveModal=false" class="px-4 py-2 bg-gray-200 rounded">Cancel</button>
                    <button class="px-4 py-2 bg-green-600 text-white rounded">Approve</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Complete Modal --}}
    @if(in_array($withdrawal->status,['pending','processing']))
    <div x-show="completeModal" x-transition class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg w-full max-w-md">
            <h3 class="text-lg font-semibold mb-3">Complete Withdrawal</h3>
            <form action="{{ route('admin.withdrawals.complete', $withdrawal) }}" method="POST">
                @csrf

                <label class="block font-medium text-sm mb-1">Transaction Hash *</label>
                <input required name="tx_hash" class="border rounded w-full p-2 mb-3">

                <label class="block font-medium text-sm mb-1">Admin Notes (optional)</label>
                <textarea name="admin_notes" rows="3" class="w-full border rounded p-2 mb-3"></textarea>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="completeModal=false" class="px-4 py-2 bg-gray-200 rounded">Cancel</button>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded">Complete</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Reject Modal --}}
    @if(in_array($withdrawal->status,['pending','processing']))
    <div x-show="rejectModal" x-transition class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg w-full max-w-md">
            <h3 class="text-lg font-semibold mb-3">Reject Withdrawal</h3>
            <form action="{{ route('admin.withdrawals.reject', $withdrawal) }}" method="POST">
                @csrf

                <label class="block font-medium text-sm mb-1">Reason *</label>
                <textarea required name="admin_notes" rows="3" class="w-full border rounded p-2 mb-3"></textarea>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="rejectModal=false" class="px-4 py-2 bg-gray-200 rounded">Cancel</button>
                    <button class="px-4 py-2 bg-red-600 text-white rounded">Reject</button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
@endsection


@section('scripts')
<script>
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