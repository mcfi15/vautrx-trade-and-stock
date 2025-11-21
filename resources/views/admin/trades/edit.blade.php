@extends('admin.layouts.app')

@section('title', 'Edit Trade')

@section('content')
<div class="container mx-auto py-5">
    <h1 class="text-2xl font-bold mb-6">Edit Trade #{{ $trade->trade_number }}</h1>

    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.trades.update', $trade) }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="price" class="block text-gray-700 font-medium mb-1">Price</label>
            <input type="number" name="price" id="price" step="0.00000001" class="w-full border px-3 py-2 rounded" value="{{ old('price', $trade->price) }}" required>
        </div>

        <div class="mb-4">
            <label for="quantity" class="block text-gray-700 font-medium mb-1">Quantity</label>
            <input type="number" name="quantity" id="quantity" step="0.000000000000000001" class="w-full border px-3 py-2 rounded" value="{{ old('quantity', $trade->quantity) }}" required>
        </div>

        <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">Update Trade</button>
        <a href="{{ route('admin.trades.index') }}" class="ml-3 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection
