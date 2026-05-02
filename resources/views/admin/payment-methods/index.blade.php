@extends('admin.layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10">
    {{-- Success & Error Messages --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-4 flex justify-between">
            <span><i class="fas fa-check-circle"></i> {{ session('success') }}</span>
            <button onclick="this.parentNode.remove()" class="text-green-800">&times;</button>
        </div>
    @endif

    <div class="flex justify-between mb-6">
        <h1 class="text-2xl font-bold">Payment Methods</h1>
        <a href="{{ route('admin.payment-methods.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded">Add Method</a>
    </div>

    <div class="bg-white shadow rounded p-6">
        @forelse($methods as $method)
            <div class="flex items-center justify-between border-b py-4 last:border-0">
                <div>
                    <div class="flex items-center gap-2">
                        <p class="font-semibold text-lg">{{ $method->name }}</p>
                        {{-- Type Badge --}}
                        <span class="px-2 py-0.5 text-xs font-bold uppercase rounded {{ $method->type === 'crypto' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $method->type }}
                        </span>
                    </div>

                    <div class="text-gray-500 text-sm mt-1">
                        @if($method->type === 'crypto')
                            {{-- Crypto Display --}}
                            <span class="font-medium text-gray-700">{{ $method->cryptocurrency->name ?? 'Unknown Coin' }}</span> 
                            <br>
                            <span class="font-mono bg-gray-100 px-1 rounded">{{ $method->address }}</span>
                        @else
                            {{-- Bank Display --}}
                            <p><span class="font-medium text-gray-700">Bank:</span> {{ $method->bank_name }}</p>
                            <p><span class="font-medium text-gray-700">Acc:</span> {{ $method->account_number }} ({{ $method->account_name }})</p>
                            @if($method->swift_code)
                                <p><span class="font-medium text-gray-700">SWIFT:</span> {{ $method->swift_code }}</p>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="flex space-x-2">
                    <a href="{{ route('admin.payment-methods.edit', $method) }}"
                       class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition">Edit</a>

                    <form method="POST"
                          action="{{ route('admin.payment-methods.destroy', $method) }}"
                          class="inline"
                          onsubmit="return confirm('Are you sure you want to delete this method?')">
                        @csrf @method('DELETE')
                        <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-10 text-gray-500">
                No payment methods found. Click "Add Method" to create one.
            </div>
        @endforelse
    </div>
</div>
@endsection