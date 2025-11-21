@extends('admin.layouts.app')

@section('content')
<div class="max-w-lg mx-auto py-10">
    <h1 class="text-xl font-bold mb-6">Edit Payment Method</h1>

    <form method="POST" action="{{ route('admin.payment-methods.update', $method) }}"
          class="bg-white shadow rounded p-6 space-y-4">
        @csrf @method('PUT')

        <div>
            <label class="block">Cryptocurrency</label>
            <select name="cryptocurrency_id" class="w-full border rounded p-2">
                @foreach($cryptos as $c)
                    <option value="{{ $c->id }}"
                        {{ $method->cryptocurrency_id == $c->id ? 'selected' : '' }}>
                        {{ $c->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block">Method Name</label>
            <input type="text" name="name"
                value="{{ $method->name }}"
                class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block">Address</label>
            <input type="text" name="address"
                value="{{ $method->address }}"
                class="w-full border rounded p-2">
        </div>

        <button class="px-4 py-2 bg-blue-600 text-white rounded">
            Update
        </button>
    </form>
</div>
@endsection
