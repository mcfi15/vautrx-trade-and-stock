@extends('admin.layouts.app')

@section('content')
<div class="p-6">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold">Airdrops</h1>
    <a href="{{ route('admin.airdrops.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">Create Airdrop</a>
  </div>

  @if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>@endif
  @if(session('error'))<div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>@endif

  <div class="bg-white rounded shadow overflow-hidden">
    <table class="min-w-full">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-2">Title</th>
          <th class="px-4 py-2">Holding</th>
          <th class="px-4 py-2">Airdrop</th>
          <th class="px-4 py-2">Start</th>
          <th class="px-4 py-2">End</th>
          <th class="px-4 py-2">Active</th>
          <th class="px-4 py-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($airdrops as $a)
        <tr class="border-t">
          <td class="px-4 py-2">{{ $a->title }}</td>
          <td class="px-4 py-2">{{ $a->holdingCurrency?->symbol ?? '-' }} (min {{ $a->min_hold_amount }})</td>
          <td class="px-4 py-2">{{ $a->airdrop_amount }} {{ $a->airdropCurrency->symbol }}</td>
          <td class="px-4 py-2">{{ $a->start_at?->format('Y-m-d') }}</td>
          <td class="px-4 py-2">{{ $a->end_at?->format('Y-m-d') }}</td>
          <td class="px-4 py-2">{{ $a->is_active ? 'Yes' : 'No' }}</td>
          <td class="px-4 py-2">
            <a href="{{ route('admin.airdrops.edit', $a->id) }}" class="text-indigo-600">Edit</a>
            <form action="{{ route('admin.airdrops.destroy', $a->id) }}" method="POST" class="inline">
              @csrf @method('DELETE')
              <button onclick="return confirm('Delete?')" class="text-red-600 ml-2">Delete</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div class="p-4">{{ $airdrops->links() }}</div>
  </div>
</div>
@endsection
