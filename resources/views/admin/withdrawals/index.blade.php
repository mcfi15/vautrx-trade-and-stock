@extends('admin.layouts.app')

@section('title', 'Withdrawals')

@section('content')
<div class="w-full bg-gray-50 min-h-screen py-6">
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
    <div class="max-w-7xl mx-auto px-6">


        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Withdrawal Management</h1>
            <nav class="text-sm text-gray-500 mt-2 md:mt-0">
                <ol class="flex gap-2">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a> /</li>
                    <li class="font-medium text-gray-800">Withdrawals</li>
                </ol>
            </nav>
        </div>

        {{-- Card --}}
        <div class="bg-white shadow rounded-lg border">

            {{-- Header --}}
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h2 class="font-semibold text-gray-800 text-lg">All Withdrawals</h2>
                <a href="{{ route('admin.withdrawals.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md text-sm font-medium">
                    <i class="fa fa-plus mr-1"></i> Create Manual Withdrawal
                </a>
            </div>

            {{-- Filters --}}
            <div class="px-6 py-4">
                <form class="space-y-4" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status"
                                class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All</option>
                                @foreach(['pending','processing','completed','failed','cancelled'] as $status)
                                    <option value="{{ $status }}" {{ request('status')==$status ? 'selected':'' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cryptocurrency</label>
                            <select name="cryptocurrency_id"
                                class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All</option>
                                @foreach($cryptocurrencies as $crypto)
                                <option value="{{ $crypto->id }}" {{ request('cryptocurrency_id')==$crypto->id ? 'selected':'' }}>
                                    {{ $crypto->symbol }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Search --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Search</label>
                            <input type="text" name="search"
                                class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="User email, wallet, or transaction hash"
                                value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-md text-sm font-medium">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                @if($withdrawals->count())
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">User</th>
                            <th class="px-4 py-2 text-left">Crypto</th>
                            <th class="px-4 py-2 text-left">Amount</th>
                            <th class="px-4 py-2 text-left">Fee</th>
                            <th class="px-4 py-2 text-left">Address</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Date</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">

                        @foreach($withdrawals as $withdrawal)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.withdrawals.show', $withdrawal) }}"
                                   class="text-blue-600 hover:underline">#{{ $withdrawal->id }}</a>
                            </td>

                            <td class="px-4 py-3">
                                <p class="font-medium">{{ $withdrawal->user->email }}</p>
                                <span class="text-xs text-gray-500">{{ $withdrawal->user->name }}</span>
                            </td>

                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs bg-gray-200 rounded">{{ $withdrawal->cryptocurrency->symbol }}</span>
                            </td>

                            <td class="px-4 py-3">{{ number_format($withdrawal->amount, 8) }}</td>
                            <td class="px-4 py-3">{{ number_format($withdrawal->fee, 8) }}</td>

                            <td class="px-4 py-3 font-mono text-xs">{{ Str::limit($withdrawal->withdrawal_address, 20) }}</td>

                            {{-- Status Badge --}}
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    @if($withdrawal->status=='pending') bg-yellow-100 text-yellow-700
                                    @elseif($withdrawal->status=='completed') bg-green-100 text-green-700
                                    @elseif($withdrawal->status=='failed') bg-red-100 text-red-700
                                    @elseif($withdrawal->status=='processing') bg-blue-100 text-blue-700
                                    @else bg-gray-200 text-gray-700 @endif">
                                    {{ ucfirst($withdrawal->status) }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-xs">
                                {{ $withdrawal->created_at->format('M d, Y') }}<br>
                                <span class="text-gray-500">{{ $withdrawal->created_at->format('H:i') }}</span>
                            </td>

                            {{-- Actions Dropdown --}}
                            <td class="px-4 py-3">
                                <div x-data="{open:false}" class="relative">
                                    <button @click="open=!open"
                                        class="px-2 py-1 text-xs border rounded hover:bg-gray-100">
                                        Actions ▾
                                    </button>

                                    <div x-show="open" @click.away="open=false"
                                         class="absolute right-0 mt-1 bg-white border rounded shadow-md text-sm w-40 z-20">

                                        <a href="{{ route('admin.withdrawals.show', $withdrawal) }}"
                                           class="block px-4 py-2 hover:bg-gray-100">View</a>

                                        <a href="{{ route('admin.withdrawals.edit', $withdrawal) }}"
                                           class="block px-4 py-2 hover:bg-gray-100">Edit</a>

                                        @if($withdrawal->status=='pending')
                                            <form method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal) }}">
                                                @csrf
                                                <button onclick="return confirm('Approve withdrawal?')"
                                                        class="w-full text-left px-4 py-2 text-green-600 hover:bg-gray-100">
                                                    Approve
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal) }}">
                                                @csrf
                                                <button onclick="return confirm('Reject withdrawal?')"
                                                        class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                                    Reject
                                                </button>
                                            </form>
                                        @endif

                                        <form method="POST" action="{{ route('admin.withdrawals.destroy', $withdrawal) }}">
                                            @csrf @method('DELETE')
                                            <button onclick="return confirm('Delete withdrawal?')"
                                                    class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                                Delete
                                            </button>
                                        </form>

                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                @else
                <div class="text-center py-10">
                    <i class="fa fa-wallet text-gray-300 text-5xl mb-3"></i>
                    <p class="text-lg text-gray-500 font-medium">No Withdrawals Found</p>
                </div>
                @endif
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4">
                {{ $withdrawals->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
