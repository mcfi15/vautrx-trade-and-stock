@extends('admin.layouts.app')

@section('title', 'Update Wallet Balance')

@section('content')
<div class="container mx-auto py-5">
    <h1 class="text-2xl font-bold mb-6">Update Wallet: {{ $wallet->cryptocurrency->symbol }} for {{ $user->name }}</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.users.wallets.update', [$user->id, $wallet->id]) }}" method="POST" class="bg-white p-6 rounded shadow w-full max-w-md">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="type" class="block text-gray-700 font-medium mb-1">Action</label>
            <select name="type" id="type" class="w-full border px-3 py-2 rounded" required>
                <option value="">-- Select --</option>
                <option value="credit">Credit</option>
                <option value="debit">Debit</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="amount" class="block text-gray-700 font-medium mb-1">Amount</label>
            <input type="number" name="amount" id="amount" step="0.00000001" min="0" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="note" class="block text-gray-700 font-medium mb-1">Note (optional)</label>
            <textarea name="note" id="note" rows="3" class="w-full border px-3 py-2 rounded" placeholder="Reason for credit/debit..."></textarea>
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Update Wallet</button>
        {{-- <a href="{{ route('admin.users.wallets.edit', $user->id) }}" class="ml-3 text-gray-600 hover:underline">Cancel</a> --}}
    </form>
</div>
@endsection
