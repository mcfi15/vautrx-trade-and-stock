@extends('admin.layouts.app')

@section('content')

<div class="container mx-auto py-10">

    <h1 class="text-2xl font-bold mb-4">Edit Staking Plan</h1>

    <form method="POST" action="{{ route('admin.staking.update', $plan->id) }}" class="bg-white shadow rounded-xl p-6">
        @csrf
        @method('PUT')

        <label>Percent</label>
        <input type="number" step="0.01" name="percent" value="{{ $plan->percent }}" class="form-input w-full mt-1">

        <label class="mt-4">Lock Days</label>
        <input type="number" name="lock_days" value="{{ $plan->lock_days }}" class="form-input w-full mt-1">

        <label class="mt-4">Minimum Amount</label>
        <input type="text" name="min_amount" value="{{ $plan->min_amount }}" class="form-input w-full mt-1">

        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg mt-6">
            Update
        </button>

    </form>

</div>

@endsection
