@extends('admin.layouts.app')

@section('content')
<div class="p-6">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold">Faucets</h1>
    <a href="{{ route('admin.faucets.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">Create Faucet</a>
  </div>

  @if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>@endif

  <div class="bg-white rounded shadow overflow-hidden">
    <table class="min-w-full">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-2">Title</th>
          <th class="px-4 py-2">Coin</th>
          <th class="px-4 py-2">Amount</th>
          <th class="px-4 py-2">Active</th>
          <th class="px-4 py-2">Cooldown</th>
          <th class="px-4 py-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($faucets as $f)
        <tr class="border-t">
          <td class="px-4 py-2">{{ $f->title }}</td>
          <td class="px-4 py-2">{{ $f->coin?->symbol ?? '-' }}</td>
          <td class="px-4 py-2">{{ $f->amount }}</td>
          <td class="px-4 py-2">{{ $f->is_active ? 'Yes' : 'No' }}</td>
          <td class="px-4 py-2">{{ gmdate('H:i:s',$f->cooldown_seconds) }}</td>
          <td class="px-4 py-2">
            <a href="{{ route('admin.faucets.edit', $f->id) }}" class="text-indigo-600">Edit</a>
            <form action="{{ route('admin.faucets.destroy', $f->id) }}" method="POST" class="inline">
              @csrf @method('DELETE')
              <button onclick="return confirm('Delete?')" class="text-red-600 ml-2">Delete</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div class="p-4">{{ $faucets->links() }}</div>
  </div>
</div>
@endsection
