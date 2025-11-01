@extends('admin.layouts.app')

@section('title', 'Edit Deposit')

@section('content')
<div class="px-6 py-6">
    
    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <h4 class="text-xl font-semibold text-white">Edit Deposit #{{ $deposit->id }}</h4>
        <nav class="text-sm text-gray-400">
            <ol class="flex space-x-2">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-white">Dashboard</a></li>
                <li>/</li>
                <li><a href="{{ route('admin.deposits.index') }}" class="hover:text-white">Deposits</a></li>
                <li>/ Edit</li>
            </ol>
        </nav>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Main Form --}}
        <div class="lg:col-span-2">
            <div class="bg-gray-800 border border-gray-700 rounded-xl">
                <div class="px-5 py-3 border-b border-gray-700">
                    <h4 class="text-lg font-semibold text-amber-400">Deposit Information</h4>
                </div>

                <div class="p-5">
                    <form action="{{ route('admin.deposits.update', $deposit) }}" method="POST">
                        @csrf @method('PUT')

                        {{-- User --}}
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-gray-300">User</label>
                                <input class="w-full bg-gray-900 border border-gray-700 rounded p-2 text-gray-300" 
                                       value="{{ $deposit->user->email }} ({{ $deposit->user->name }})" readonly>
                                <p class="text-xs text-gray-500 mt-1">User cannot be changed</p>
                            </div>

                            {{-- Crypto --}}
                            <div>
                                <label class="text-sm text-gray-300">Cryptocurrency *</label>
                                <select name="cryptocurrency_id"
                                        class="w-full bg-gray-900 border border-gray-700 rounded p-2 text-gray-300 @error('cryptocurrency_id') border-red-500 @enderror"
                                        required>
                                    @foreach($cryptocurrencies as $crypto)
                                        <option value="{{ $crypto->id }}"
                                            {{ $deposit->cryptocurrency_id == $crypto->id ? 'selected' : '' }}>
                                            {{ $crypto->name }} ({{ $crypto->symbol }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('cryptocurrency_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Amount & Fee --}}
                        <div class="grid md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="text-sm text-gray-300">Amount *</label>
                                <input type="number" name="amount"
                                       class="w-full bg-gray-900 border border-gray-700 rounded p-2 text-gray-300 @error('amount') border-red-500 @enderror"
                                       value="{{ old('amount', $deposit->amount) }}" step="0.00000001" required>
                                @error('amount')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="text-sm text-gray-300">Fee</label>
                                <input type="number" name="fee"
                                       class="w-full bg-gray-900 border border-gray-700 rounded p-2 text-gray-300 @error('fee') border-red-500 @enderror"
                                       value="{{ old('fee', $deposit->fee) }}" step="0.00000001">
                                <p class="text-xs text-gray-500 mt-1">Network fee (optional)</p>
                            </div>
                        </div>

                        {{-- Hash & Status --}}
                        <div class="grid md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="text-sm text-gray-300">Transaction Hash *</label>
                                <input type="text" name="transaction_hash"
                                       class="w-full bg-gray-900 border border-gray-700 rounded p-2 text-gray-300 @error('transaction_hash') border-red-500 @enderror"
                                       value="{{ old('transaction_hash', $deposit->transaction_hash) }}" required>
                                @error('transaction_hash')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="text-sm text-gray-300">Status *</label>
                                <select name="status"
                                        class="w-full bg-gray-900 border border-gray-700 rounded p-2 text-gray-300 @error('status') border-red-500 @enderror"
                                        required>
                                    @foreach(['pending','confirmed','completed','failed'] as $status)
                                        <option value="{{ $status }}" {{ old('status',$deposit->status)==$status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Confirmations --}}
                        <div class="grid md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="text-sm text-gray-300">Confirmations</label>
                                <input type="number" name="confirmations"
                                       class="w-full bg-gray-900 border border-gray-700 rounded p-2 text-gray-300"
                                       value="{{ old('confirmations', $deposit->confirmations) }}">
                                <p class="text-xs text-gray-500 mt-1">Blockchain confirmations</p>
                            </div>

                            <div>
                                <label class="text-sm text-gray-300">Required Confirmations</label>
                                <input type="number"
                                       class="w-full bg-gray-800 border border-gray-700 rounded p-2 text-gray-500"
                                       value="{{ $deposit->required_confirmations ?? 3 }}" disabled>
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div class="mt-4">
                            <label class="text-sm text-gray-300">Admin Notes</label>
                            <textarea name="notes"
                                      class="w-full bg-gray-900 border border-gray-700 rounded p-2 text-gray-300 h-24">{{ old('notes', $deposit->admin_notes) }}</textarea>
                        </div>

                        {{-- Buttons --}}
                        <div class="mt-5 flex gap-3 justify-end">
                            <a href="{{ route('admin.deposits.show',$deposit) }}"
                               class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-600">
                                Cancel
                            </a>
                            <button class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                                Update Deposit
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Status Card --}}
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-5">
                <h4 class="text-lg font-semibold text-gray-300 mb-3">Current Status</h4>
                
                <span class="text-sm px-3 py-1 rounded bg-gray-700 text-white">
                    {{ ucfirst($deposit->status) }}
                </span>
                <p class="text-xs text-gray-500 mt-2">{{ $deposit->updated_at->diffForHumans() }}</p>
            </div>

            {{-- Transaction Info --}}
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-5">
                <h4 class="text-lg font-semibold text-gray-300 mb-3">Transaction Info</h4>

                <p class="text-gray-400 text-sm">Amount:
                    <span class="text-white font-semibold">{{ $deposit->amount }} {{ $deposit->cryptocurrency->symbol }}</span>
                </p>
                <p class="text-gray-400 text-sm">Fee:
                    <span class="text-white font-semibold">{{ $deposit->fee }}</span>
                </p>

                <p class="text-xs mt-2 text-gray-500 break-all">
                    Hash: {{ $deposit->transaction_hash }}
                </p>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-5">
                <h4 class="text-lg font-semibold text-gray-300 mb-3">Quick Actions</h4>

                @if($deposit->status === 'pending')
                <form action="{{ route('admin.deposits.confirm',$deposit) }}" method="POST">
                    @csrf
                    <button class="w-full px-3 py-2 text-sm bg-green-500 text-white rounded hover:bg-green-600">
                        Confirm Deposit
                    </button>
                </form>
                @endif

                <a href="{{ route('admin.deposits.show',$deposit) }}"
                   class="block w-full mt-2 text-center px-3 py-2 text-sm border border-blue-400 text-blue-400 rounded hover:bg-blue-500 hover:text-white">
                    View Details
                </a>
            </div>

        </div>

    </div>
</div>
@endsection
