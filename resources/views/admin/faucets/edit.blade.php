@extends('admin.layouts.app')

@section('content')
<div class="p-6 max-w-2xl">
  <h1 class="text-xl font-semibold mb-4">{{ isset($faucet)? 'Edit' : 'Create' }} Faucet</h1>

  @if($errors->any())
    <div class="mb-4 p-2 bg-red-100 text-red-800 rounded"><ul>@foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach</ul></div>
  @endif

  <form action="{{ isset($faucet) ? route('admin.faucets.update', $faucet->id) : route('admin.faucets.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($faucet)) @method('PUT') @endif

    <label class="block">Title</label>
    <input name="title" value="{{ old('title',$faucet->title ?? '') }}" class="w-full border p-2 rounded mb-2">

    <label class="block">Coin</label>
    <select name="cryptocurrency_id" class="w-full border p-2 rounded mb-2">
      @foreach($coins as $c)
        <option value="{{ $c->id }}" {{ (old('cryptocurrency_id',$faucet->cryptocurrency_id ?? '') == $c->id) ? 'selected' : '' }}>
          {{ $c->symbol }} - {{ $c->name }}
        </option>
      @endforeach
    </select>

    <label class="block">Amount</label>
    <input name="amount" value="{{ old('amount',$faucet->amount ?? '') }}" class="w-full border p-2 rounded mb-2">

    <label class="block">Start At</label>
    <input type="datetime-local" name="start_at" value="{{ old('start_at', isset($faucet) && $faucet->start_at ? $faucet->start_at->format('Y-m-d\TH:i') : '') }}" class="w-full border p-2 rounded mb-2">

    <label class="block">End At</label>
    <input type="datetime-local" name="end_at" value="{{ old('end_at', isset($faucet) && $faucet->end_at ? $faucet->end_at->format('Y-m-d\TH:i') : '') }}" class="w-full border p-2 rounded mb-2">

    <label class="block">Cooldown seconds</label>
    <input name="cooldown_seconds" value="{{ old('cooldown_seconds',$faucet->cooldown_seconds ?? 86400) }}" class="w-full border p-2 rounded mb-2">

    <label class="block">Max claims per user</label>
    <input name="max_claims_per_user" value="{{ old('max_claims_per_user',$faucet->max_claims_per_user ?? 1) }}" class="w-full border p-2 rounded mb-2">

    <label class="block">Description</label>
    <textarea name="description" class="w-full border p-2 rounded mb-2">{{ old('description',$faucet->description ?? '') }}</textarea>

    <label class="block">Image</label>
    <input type="file" name="image" class="w-full mb-2">
    @if(isset($faucet) && $faucet->image)
      <img src="{{ asset($faucet->image) }}" class="w-32 h-32 object-cover mb-2" />
    @endif

    <label class="inline-flex items-center mt-2">
      <input type="hidden" name="is_active" value="0">
      <input type="checkbox" name="is_active" value="1" {{ old('is_active', $faucet->is_active ?? true) ? 'checked' : '' }}>
      <span class="ml-2">Active</span>
    </label>

    <div class="mt-4">
      <button class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
    </div>
  </form>
</div>
@endsection
