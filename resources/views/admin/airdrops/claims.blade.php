@extends('admin.layouts.app')

@section('content')
<div class="p-6">
  <h1 class="text-2xl font-semibold mb-4">Airdrop Claims</h1>

  @if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>@endif
  @if(session('error'))<div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>@endif

  <div class="bg-white rounded shadow overflow-hidden">
    <table class="min-w-full">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-2">User</th>
          <th class="px-4 py-2">Airdrop</th>
          <th class="px-4 py-2">Amount</th>
          <th class="px-4 py-2">Status</th>
          <th class="px-4 py-2">Requested</th>
          <th class="px-4 py-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($claims as $c)
        <tr class="border-t">
          <td class="px-4 py-2">{{ $c->user->name }} ({{ $c->user->email }})</td>
          <td class="px-4 py-2">{{ $c->airdrop->title }}</td>
          <td class="px-4 py-2">{{ $c->claim_amount }} {{ $c->airdrop->airdropCurrency->symbol }}</td>
          <td class="px-4 py-2">{{ ucfirst($c->status) }}</td>
          <td class="px-4 py-2">{{ $c->created_at->format('Y-m-d H:i') }}</td>
          <td class="px-4 py-2">
            @if($c->status==='pending')
              <form action="{{ route('admin.airdrops.claims.approve', $c->id) }}" method="POST" class="inline">
                @csrf
                <button class="px-3 py-1 bg-green-600 text-white rounded">Approve</button>
              </form>

              <form action="{{ route('admin.airdrops.claims.reject', $c->id) }}" method="POST" class="inline">
                @csrf
                <input name="reason" placeholder="Reason" required class="border p-1 rounded">
                <button class="px-3 py-1 bg-red-600 text-white rounded">Reject</button>
              </form>
            @else
              <span class="text-gray-600">No actions</span>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div class="p-4">{{ $claims->links() }}</div>
  </div>
</div>


@endsection
