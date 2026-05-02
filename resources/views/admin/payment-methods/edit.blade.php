@extends('admin.layouts.app')

@section('content')
<div class="max-w-lg mx-auto py-10">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.payment-methods') }}" class="text-blue-600 hover:underline">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <h1 class="text-xl font-bold">Edit Payment Method</h1>
    </div>

    <form method="POST" action="{{ route('admin.payment-methods.update', $method) }}"
          class="bg-white shadow rounded p-6 space-y-4">
        @csrf 
        @method('PUT')

        {{-- Method Type (Locked for consistency, or selectable) --}}
        <div>
            <label class="block font-semibold">Method Type</label>
            <select name="type" id="methodType" class="w-full border rounded p-2 bg-gray-50" onchange="toggleFields()">
                <option value="crypto" {{ $method->type == 'crypto' ? 'selected' : '' }}>Cryptocurrency Address</option>
                <option value="bank" {{ $method->type == 'bank' ? 'selected' : '' }}>Bank Transfer Details</option>
            </select>
        </div>

        {{-- Display Label --}}
        <div>
            <label class="block font-semibold">Display Label (Internal Name)</label>
            <input type="text" name="name" value="{{ old('name', $method->name) }}" class="w-full border rounded p-2" required>
        </div>

        {{-- Crypto Only Fields --}}
        <div id="cryptoFields" style="display: {{ $method->type == 'crypto' ? 'block' : 'none' }};">
            <div class="mb-4">
                <label class="block font-semibold">Associated Cryptocurrency</label>
                <select name="cryptocurrency_id" class="w-full border rounded p-2">
                    <option value="">Select Coin</option>
                    @foreach($cryptos as $c)
                        <option value="{{ $c->id }}" {{ $method->cryptocurrency_id == $c->id ? 'selected' : '' }}>
                            {{ $c->name }} ({{ $c->symbol }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-semibold">Wallet Address</label>
                <input type="text" name="address" value="{{ old('address', $method->address) }}" class="w-full border rounded p-2">
            </div>
        </div>

        {{-- Bank Only Fields --}}
        <div id="bankFields" style="display: {{ $method->type == 'bank' ? 'block' : 'none' }};">
            <div class="mb-4">
                <label class="block font-semibold">Bank Name</label>
                <input type="text" name="bank_name" value="{{ old('bank_name', $method->bank_name) }}" class="w-full border rounded p-2">
            </div>
            <div class="mb-4">
                <label class="block font-semibold">Account Holder Name</label>
                <input type="text" name="account_name" value="{{ old('account_name', $method->account_name) }}" class="w-full border rounded p-2">
            </div>
            <div class="mb-4">
                <label class="block font-semibold">Account Number / IBAN</label>
                <input type="text" name="account_number" value="{{ old('account_number', $method->account_number) }}" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="block font-semibold">SWIFT / BIC Code (Optional)</label>
                <input type="text" name="swift_code" value="{{ old('swift_code', $method->swift_code) }}" class="w-full border rounded p-2">
            </div>
        </div>

        <div class="pt-4">
            <button class="w-full px-4 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 transition">
                Update Changes
            </button>
        </div>
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

// Optional: Initialize on load just to be safe
window.onload = toggleFields;
</script>
@endsection