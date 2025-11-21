@extends('admin.layouts.app')

@section('title', 'User Details - ' . $user->name)

@section('content')
<div class="mb-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
            <i class="fas fa-user"></i> User Details
        </h1>
        <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>

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

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- User Profile Card -->
    <div class="lg:col-span-1">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="text-center">
                @if($user->avatar)
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="h-24 w-24 rounded-full mx-auto mb-4">
                @else
                    <div class="h-24 w-24 rounded-full bg-indigo-100 flex items-center justify-center mx-auto mb-4">
                        <span class="text-indigo-600 font-bold text-3xl">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                @endif
                <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                
                <div class="mt-4">
                    @if($user->is_active)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i> Active
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-1"></i> Inactive
                        </span>
                    @endif
                </div>
            </div>

            <hr class="my-6">

            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Auth Provider:</span>
                    <span class="font-medium">
                        @if($user->auth_provider === 'google')
                            <i class="fab fa-google text-red-500"></i> Google
                        @else
                            <i class="fas fa-envelope text-blue-500"></i> Email
                        @endif
                    </span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Member Since:</span>
                    <span class="font-medium">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
                @if($user->last_login_at)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Last Login:</span>
                    <span class="font-medium">{{ $user->last_login_at->diffForHumans() }}</span>
                </div>
                @endif
                @if($user->email_verified_at)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Email Verified:</span>
                    <span class="font-medium text-green-600"><i class="fas fa-check"></i> Yes</span>
                </div>
                @endif
            </div>

            <hr class="my-6">

            <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full {{ $user->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded-md transition">
                    <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i> 
                    {{ $user->is_active ? 'Deactivate User' : 'Activate User' }}
                </button>
            </form>
        </div>
    </div>

    <!-- User Activity -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Statistics -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-white shadow rounded-lg p-4">
                <div class="text-center">
                    <i class="fas fa-wallet text-blue-500 text-2xl mb-2"></i>
                    <div class="text-2xl font-bold text-gray-900">{{ $user->wallets->count() }}</div>
                    <div class="text-xs text-gray-500">Wallets</div>
                </div>
            </div>
            <div class="bg-white shadow rounded-lg p-4">
                <div class="text-center">
                    <i class="fas fa-list text-green-500 text-2xl mb-2"></i>
                    <div class="text-2xl font-bold text-gray-900">{{ $user->orders->count() }}</div>
                    <div class="text-xs text-gray-500">Orders</div>
                </div>
            </div>
            <div class="bg-white shadow rounded-lg p-4">
                <div class="text-center">
                    <i class="fas fa-exchange-alt text-purple-500 text-2xl mb-2"></i>
                    <div class="text-2xl font-bold text-gray-900">{{ $user->transactions->count() }}</div>
                    <div class="text-xs text-gray-500">Transactions</div>
                </div>
            </div>
            <div class="bg-white shadow rounded-lg p-4">
                <div class="text-center">
                    <i class="fas fa-dollar-sign text-yellow-500 text-2xl mb-2"></i>
                    <div class="text-1xl font-bold text-gray-900">
                        ${{ number_format($user->wallets->sum('balance') * 100, 2) }}
                    </div>
                    <div class="text-xs text-gray-500">Total Balance</div>
                </div>
            </div>
        </div>

        <!-- Wallets -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-wallet"></i> Wallets
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cryptocurrency</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Balance</th>
                        <th class="hidden sm:table-cell px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">USD Value</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($user->wallets as $wallet)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    @if($wallet->cryptocurrency->logo_url)
                                        <img src="{{ $wallet->cryptocurrency->logo_url }}" alt="{{ $wallet->cryptocurrency->symbol }}" class="h-6 w-6 mr-2">
                                    @endif
                                    <span class="font-medium">{{ $wallet->cryptocurrency->symbol }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right font-mono">{{ number_format($wallet->balance, 8) }}</td>
                            <td class="hidden sm:table-cell px-4 py-3 text-right text-gray-500">
                                ${{ number_format($wallet->balance * $wallet->cryptocurrency->current_price, 2) }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('admin.users.wallets.edit', [$user->id, $wallet->id]) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Credit/Debit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">No wallets found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

        <!-- Recent Orders -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-list"></i> Recent Orders
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Side</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="hidden sm:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="hidden lg:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($user->orders->take(10) as $order)
                            <tr>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded {{ $order->side === 'buy' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ strtoupper($order->side) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-mono text-sm">{{ number_format($order->amount, 4) }}</td>
                                <td class="hidden sm:table-cell px-4 py-3 font-mono text-sm">${{ number_format($order->price, 2) }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded
                                        @if($order->status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="hidden lg:table-cell px-4 py-3 text-sm text-gray-500">
                                    {{ $order->created_at->format('M d, H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">No orders found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection