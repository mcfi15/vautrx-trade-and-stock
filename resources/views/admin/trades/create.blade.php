@extends('admin.layouts.app')

@section('title', 'Create Trade')

@section('content')
<div class="container mx-auto py-5">
    <h1 class="text-2xl font-bold mb-6">Create Trade</h1>

    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.trades.store') }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf

        <div class="mb-4">
            <label for="order_id" class="block text-gray-700 font-medium mb-1">Order ID</label>
            <input type="number" name="order_id" id="order_id" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="trading_pair_id" class="block text-gray-700 font-medium mb-1">Trading Pair</label>
            <select name="trading_pair_id" id="trading_pair_id" class="w-full border px-3 py-2 rounded" required>
                @foreach(\App\Models\TradingPair::all() as $pair)
                    <option value="{{ $pair->id }}">{{ $pair->symbol }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="buyer_id" class="block text-gray-700 font-medium mb-1">Buyer</label>
            <select name="buyer_id" id="buyer_id" class="w-full border px-3 py-2 rounded" required>
                @foreach(\App\Models\User::all() as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="seller_id" class="block text-gray-700 font-medium mb-1">Seller</label>
            <select name="seller_id" id="seller_id" class="w-full border px-3 py-2 rounded" required>
                @foreach(\App\Models\User::all() as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="price" class="block text-gray-700 font-medium mb-1">Price</label>
            <input type="number" name="price" id="price" step="0.00000001" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="quantity" class="block text-gray-700 font-medium mb-1">Quantity</label>
            <input type="number" name="quantity" id="quantity" step="0.000000000000000001" class="w-full border px-3 py-2 rounded" required>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create Trade</button>
        <a href="{{ route('admin.trades.index') }}" class="ml-3 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection
