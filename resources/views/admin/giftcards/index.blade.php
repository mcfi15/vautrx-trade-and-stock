@extends('admin.layouts.app')

@section('title', 'Gift Cards Management')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Gift Cards Management</h3>
            <a href="{{ route('admin.giftcards.transactions') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                View Transactions
            </a>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-5 mb-6">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Gift Cards</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['total'] }}</dd>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">Active</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['active'] }}</dd>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">Redeemed</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['redeemed'] }}</dd>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">Expired</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['expired'] }}</dd>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Amount</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($stats['total_amount'], 8) }}</dd>
                </div>
            </div>
        </div>

        <!-- Gift Cards Table -->
        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Card Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Creator</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Redeemed By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($giftCards as $giftCard)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $giftCard->title }}</div>
                            <div class="text-sm text-gray-500">Public: {{ $giftCard->public_code }}</div>
                            <div class="text-sm text-gray-500">Secret: {{ $giftCard->secret_code }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $giftCard->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $giftCard->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($giftCard->amount, 8) }} {{ $giftCard->cryptocurrency->symbol }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($giftCard->status === 'active')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @elseif($giftCard->status === 'redeemed')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Redeemed
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Expired
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($giftCard->redeemedBy)
                                <div class="text-sm font-medium text-gray-900">{{ $giftCard->redeemedBy->name }}</div>
                                <div class="text-sm text-gray-500">{{ $giftCard->redeemed_at->format('M j, Y H:i') }}</div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $giftCard->created_at->format('M j, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($giftCard->status === 'active')
                            <form action="{{ route('admin.giftcards.destroy', $giftCard) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this gift card?')">
                                    Delete
                                </button>
                            </form>
                            @else
                            <span class="text-gray-400">No actions</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                            No gift cards found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($giftCards->hasPages())
        <div class="mt-6">
            {{ $giftCards->links() }}
        </div>
        @endif
    </div>
</div>
@endsection