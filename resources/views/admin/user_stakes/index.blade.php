@extends('admin.layouts.app')

@section('content')
<div class="p-6">
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
    <h1 class="text-2xl font-semibold mb-4">User Stakes</h1>
    <div class="bg-white rounded shadow p-4 overflow-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2">User</th>
                    <th class="px-4 py-2">Plan</th>
                    <th class="px-4 py-2">Coin</th>
                    <th class="px-4 py-2">Amount</th>
                    <th class="px-4 py-2">Duration</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stakes as $s)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $s->user->name }}</td>
                    <td class="px-4 py-2">{{ $s->plan->name }}</td>
                    <td class="px-4 py-2">{{ $s->cryptocurrency->symbol }}</td>
                    <td class="px-4 py-2">{{ $s->amount }}</td>
                    <td class="px-4 py-2">{{ $s->duration }} days</td>
                    <td class="px-4 py-2">{{ ucfirst($s->status) }}</td>
                    <td class="px-4 py-2">
                        @if($s->status === 'pending')
                        <form action="{{ route('admin.user-stakes.approve', $s->id) }}" method="POST" class="inline">
                            @csrf
                            <button class="px-3 py-1 bg-green-600 text-white rounded">Approve</button>
                        </form>
                        <form action="{{ route('admin.user-stakes.reject', $s->id) }}" method="POST" class="inline">
                            @csrf
                            <input name="reason" placeholder="Reason" required class="border p-1 rounded">
                            <button class="px-3 py-1 bg-red-600 text-white rounded">Reject</button>
                        </form>
                        @elseif($s->status === 'approved')
                        <form action="{{ route('admin.user-stakes.complete', $s->id) }}" method="POST">
                            @csrf
                            <button class="px-3 py-1 bg-blue-600 text-white rounded">Complete</button>
                        </form>

                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $stakes->links() }}</div>
    </div>
</div>
@endsection
