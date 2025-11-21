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
    <h1 class="text-xl font-bold mb-6">Add Payment Method</h1>

    <form method="POST" action="{{ route('admin.payment-methods.store') }}"
          class="bg-white shadow rounded p-6 space-y-4">
        @csrf

        <div>
            <label class="block">Cryptocurrency</label>
            <select name="cryptocurrency_id" class="w-full border rounded p-2">
                @foreach($cryptos as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block">Method Name</label>
            <input type="text" name="name" class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block">Address</label>
            <input type="text" name="address" class="w-full border rounded p-2">
        </div>

        <button class="px-4 py-2 bg-blue-600 text-white rounded">
            Create
        </button>
    </form>
</div>
@endsection
