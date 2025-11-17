@extends('admin.layouts.app')

@section('content')

<div class="p-6 max-w-2xl">
    <h1 class="text-xl font-semibold mb-4">Create Stake Plan</h1>

    <form action="{{ route('admin.stake-plans.store') }}" method="POST">
        @csrf

        @if ($errors->any())
            <div class="mb-4 p-2 bg-red-100 text-red-700 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <label class="block">Name</label>
        <input name="name" class="w-full border p-2 rounded mb-2" value="{{ old('name') }}">

        <label class="block">Cryptocurrency</label>
        <select name="cryptocurrency_id" class="w-full border p-2 rounded mb-2">
            @foreach($coins as $c)
                <option value="{{ $c->id }}" @if(old('cryptocurrency_id') == $c->id) selected @endif>
                    {{ $c->symbol }} - {{ $c->name }}
                </option>
            @endforeach
        </select>

        <label>Percent (%)</label>
        <input name="percent" class="w-full border p-2 rounded mb-2" value="{{ old('percent') }}">

        <label>Lock Periods (comma separated, e.g. 30,60,90)</label>
        <input name="lock_periods_raw" class="w-full border p-2 rounded mb-2" placeholder="30,60,90" value="{{ old('lock_periods_raw') }}">

        <label>Minimum Amount</label>
        <input name="min_amount" class="w-full border p-2 rounded mb-2" value="{{ old('min_amount') }}">

        <label class="inline-flex items-center mt-2">
            <input type="checkbox" name="is_active" checked>
            <span class="ml-2">Active</span>
        </label>

        <div class="mt-4">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
        </div>
    </form>
</div>


@endsection
