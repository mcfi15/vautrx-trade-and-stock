@extends('admin.layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10">
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
    <div class="flex justify-between mb-6">
        <h1 class="text-2xl font-bold">Payment Methods</h1>
        <a href="{{ route('admin.payment-methods.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded">Add Method</a>
    </div>

    <div class="bg-white shadow rounded p-6">
        @foreach($methods as $method)
            <div class="flex items-center justify-between border-b py-4">
                <div>
                    <p class="font-semibold">{{ $method->name }}</p>
                    <p class="text-gray-500 text-sm">
                        {{ $method->cryptocurrency->name }} — {{ $method->address }}
                    </p>
                </div>

                <div class="space-x-2">
                    <a href="{{ route('admin.payment-methods.edit', $method) }}"
                       class="px-3 py-1 bg-yellow-500 text-white rounded">Edit</a>

                    <form method="POST"
                          action="{{ route('admin.payment-methods.destroy', $method) }}"
                          class="inline">
                        @csrf @method('DELETE')
                        <button class="px-3 py-1 bg-red-600 text-white rounded">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
