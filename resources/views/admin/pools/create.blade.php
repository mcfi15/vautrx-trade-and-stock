@extends('admin.layouts.app')

@section('title', isset($pool) ? 'Edit Mining Pool' : 'Create Mining Pool')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">{{ isset($pool) ? 'Edit Mining Pool' : 'Create New Mining Pool' }}</h3>

        <form action="{{ isset($pool) ? route('admin.pools.update', $pool) : route('admin.pools.store') }}" method="POST">
            @csrf
            @if(isset($pool)) @method('PUT') @endif

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Pool Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $pool->name ?? '') }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="symbol" class="block text-sm font-medium text-gray-700">Symbol</label>
                    <input type="text" name="symbol" id="symbol" value="{{ old('symbol', $pool->symbol ?? '') }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Price (LTC)</label>
                    <input type="number" step="0.00000001" name="price" id="price" value="{{ old('price', $pool->price ?? '') }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="total" class="block text-sm font-medium text-gray-700">Total Machines</label>
                    <input type="number" name="total" id="total" value="{{ old('total', $pool->total ?? '') }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="daily_reward" class="block text-sm font-medium text-gray-700">Daily Reward (LTC)</label>
                    <input type="number" step="0.00000001" name="daily_reward" id="daily_reward" value="{{ old('daily_reward', $pool->daily_reward ?? '') }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="duration_days" class="block text-sm font-medium text-gray-700">Duration (Days)</label>
                    <input type="number" name="duration_days" id="duration_days" value="{{ old('duration_days', $pool->duration_days ?? '') }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="user_limit" class="block text-sm font-medium text-gray-700">User Limit (0 = unlimited)</label>
                    <input type="number" name="user_limit" id="user_limit" value="{{ old('user_limit', $pool->user_limit ?? 0) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="power" class="block text-sm font-medium text-gray-700">Power</label>
                    <input type="text" name="power" id="power" value="{{ old('power', $pool->power ?? '') }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('description', $pool->description ?? '') }}</textarea>
            </div>

            @if(isset($pool))
            <div class="mt-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ $pool->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-600">Active Pool</span>
                </label>
            </div>
            @endif

            <div class="mt-6">
                <button type="submit" class="bg-blue-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ isset($pool) ? 'Update Pool' : 'Create Pool' }}
                </button>
                <a href="{{ route('admin.pools.index') }}" class="ml-3 bg-white border border-gray-300 rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection