@extends('admin.layouts.app')

@section('content')
<div class="p-6">
  <h1 class="text-2xl font-semibold mb-4">Faucet Logs</h1>

  <div class="bg-white rounded shadow overflow-hidden">
    <table class="min-w-full">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-2">User</th>
          <th class="px-4 py-2">Faucet</th>
          <th class="px-4 py-2">Amount</th>
          <th class="px-4 py-2">Coin</th>
          <th class="px-4 py-2">Status</th>
          <th class="px-4 py-2">IP</th>
          <th class="px-4 py-2">Time</th>
        </tr>
      </thead>
      <tbody>
        @foreach($logs as $l)
        <tr class="border-t">
          <td class="px-4 py-2">{{ $l->user->name }} ({{ $l->user->email }})</td>
          <td class="px-4 py-2">{{ $l->faucet->title }}</td>
          <td class="px-4 py-2">{{ $l->amount }}</td>
          <td class="px-4 py-2">{{ $l->faucet->coin?->symbol }}</td>
          <td class="px-4 py-2">{{ ucfirst($l->status) }}</td>
          <td class="px-4 py-2">{{ $l->ip_address }}</td>
          <td class="px-4 py-2">{{ $l->claimed_at->format('Y-m-d H:i') }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div class="p-4">{{ $logs->links() }}</div>
  </div>
</div>
@endsection
