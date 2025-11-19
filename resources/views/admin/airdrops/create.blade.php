@extends('admin.layouts.app')

@section('content')
<div class="p-6 max-w-2xl">
  <h1 class="text-xl font-semibold mb-4">{{ isset($airdrop) ? 'Edit' : 'Create' }} Airdrop</h1>

  @if($errors->any())
    <div class="mb-4 p-2 bg-red-100 text-red-800 rounded"><ul>@foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach</ul></div>
  @endif

  <form action="{{ isset($airdrop) ? route('admin.airdrops.update', $airdrop->id) : route('admin.airdrops.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($airdrop)) @method('PUT') @endif

    <label class="block">Title</label>
    <input name="title" value="{{ old('title',$airdrop->title ?? '') }}" class="w-full border p-2 rounded mb-2">

    <label class="block">Holding Currency (optional)</label>
    <select name="holding_currency_id" class="w-full border p-2 rounded mb-2">
      <option value="">-- none --</option>
      @foreach($coins as $c)
        <option value="{{ $c->id }}" @if(old('holding_currency_id',$airdrop->holding_currency_id ?? '') == $c->id) selected @endif>
          {{ $c->symbol }} - {{ $c->name }}
        </option>
      @endforeach
    </select>

    <label class="block">Min Hold Amount</label>
    <input name="min_hold_amount" value="{{ old('min_hold_amount',$airdrop->min_hold_amount ?? '0') }}" class="w-full border p-2 rounded mb-2">

    <label class="block">Airdrop Currency</label>
    <select name="airdrop_currency_id" class="w-full border p-2 rounded mb-2">
      @foreach($coins as $c)
        <option value="{{ $c->id }}" @if(old('airdrop_currency_id',$airdrop->airdrop_currency_id ?? '') == $c->id) selected @endif>
          {{ $c->symbol }} - {{ $c->name }}
        </option>
      @endforeach
    </select>

    <label class="block">Airdrop Amount</label>
    <input name="airdrop_amount" value="{{ old('airdrop_amount',$airdrop->airdrop_amount ?? '') }}" class="w-full border p-2 rounded mb-2">

    <label class="block">Start At</label>
    <input type="datetime-local" name="start_at" value="{{ old('start_at', isset($airdrop) && $airdrop->start_at ? $airdrop->start_at->format('Y-m-d\TH:i') : '') }}" class="w-full border p-2 rounded mb-2">

    <label class="block">End At</label>
    <input type="datetime-local" name="end_at" value="{{ old('end_at', isset($airdrop) && $airdrop->end_at ? $airdrop->end_at->format('Y-m-d\TH:i') : '') }}" class="w-full border p-2 rounded mb-2">

    <label class="block">Description</label>
    <textarea name="description" class="w-full border p-2 rounded mb-2">{{ old('description',$airdrop->description ?? '') }}</textarea>

    <label class="block">Image</label>
    <input type="file" name="image" class="w-full mb-2">
    @if(isset($airdrop) && $airdrop->image)
      <img src="{{ asset($airdrop->image) }}" class="w-32 h-32 object-cover mb-2" />
    @endif

    <label class="inline-flex items-center mt-2">
        <input type="hidden" name="is_active" value="0">
      <input type="checkbox" name="is_active" {{ old('is_active', $airdrop->is_active ?? true) ? 'checked' : '' }}>
      <span class="ml-2">Active</span>
    </label>

    <div class="mt-4">
      <button class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
    </div>
  </form>
</div>
@endsection
