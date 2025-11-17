@extends('admin.layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Stake Plans</h1>
        <a href="{{ route('admin.stake-plans.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">Create Plan</a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-800 rounded">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Coin</th>
                    <th class="px-4 py-2">Percent</th>
                    <th class="px-4 py-2">Periods</th>
                    <th class="px-4 py-2">Min Amount</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($plans as $p)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $p->name }}</td>
                    <td class="px-4 py-2">{{ $p->cryptocurrency->symbol }}</td>
                    <td class="px-4 py-2">{{ $p->percent }}%</td>
                    <td class="px-4 py-2">{{ implode(', ', $p->durations) }}</td>
                    <td class="px-4 py-2">{{ $p->min_amount }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('admin.stake-plans.edit', $p->id) }}" class="text-indigo-600">Edit</a>
                        <form action="{{ route('admin.stake-plans.destroy', $p->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Delete?')" class="text-red-600 ml-2">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
