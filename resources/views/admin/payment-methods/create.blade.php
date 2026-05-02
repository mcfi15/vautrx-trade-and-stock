@extends('admin.layouts.app')

@section('content')
<div class="max-w-lg mx-auto py-10">
    {{-- Success --}}
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
    <h1 class="text-xl font-bold mb-6">Add Deposit Method</h1>

    <form method="POST" action="{{ route('admin.payment-methods.store') }}" class="bg-white shadow rounded p-6 space-y-4">
        @csrf

        {{-- Toggle between Crypto and Bank --}}
        <div>
            <label class="block font-semibold">Method Type</label>
            <select name="type" id="methodType" class="w-full border rounded p-2" onchange="toggleFields()">
                <option value="crypto">Cryptocurrency Address</option>
                <option value="bank">Bank Transfer Details</option>
            </select>
        </div>

        {{-- Crypto Only Fields --}}
        <div id="cryptoFields">
            <div class="mb-4">
                <label class="block">Associated Cryptocurrency</label>
                <select name="cryptocurrency_id" class="w-full border rounded p-2">
                    <option value="">None (For Bank)</option>
                    @foreach($cryptos as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->symbol }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block">Wallet Address</label>
                <input type="text" name="address" class="w-full border rounded p-2" placeholder="e.g. 0x123... or bc1q...">
            </div>
        </div>

        {{-- Bank Only Fields --}}
        <div id="bankFields" style="display: none;">
            <div class="mb-4">
                <label class="block">Bank Name</label>
                <input type="text" name="bank_name" class="w-full border rounded p-2" placeholder="e.g. Chase Bank">
            </div>
            <div class="mb-4">
                <label class="block">Account Holder Name</label>
                <input type="text" name="account_name" class="w-full border rounded p-2">
            </div>
            <div class="mb-4">
                <label class="block">Account Number / IBAN</label>
                <input type="text" name="account_number" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="block">SWIFT / BIC Code (Optional)</label>
                <input type="text" name="swift_code" class="w-full border rounded p-2">
            </div>
        </div>

        <div>
            <label class="block">Display Label (Internal Name)</label>
            <input type="text" name="name" class="w-full border rounded p-2" placeholder="e.g. Company Main BTC or USD Bank Wire" required>
        </div>

        <button class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Create Method
        </button>
    </form>
</div>

<script>
function toggleFields() {
    const type = document.getElementById('methodType').value;
    const cryptoFields = document.getElementById('cryptoFields');
    const bankFields = document.getElementById('bankFields');

    if (type === 'crypto') {
        cryptoFields.style.display = 'block';
        bankFields.style.display = 'none';
    } else {
        cryptoFields.style.display = 'none';
        bankFields.style.display = 'block';
    }
}
</script>
@endsection
