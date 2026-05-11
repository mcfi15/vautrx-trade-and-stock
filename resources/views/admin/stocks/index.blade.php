@extends('admin.layouts.app')

@section('title', 'Stocks Management')

@push('styles')
    @livewireStyles
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Real-Time Stock Management</h1>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Inserting the Livewire Ticker -->
                @livewire('stock-price-ticker')
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @livewireScripts
@endpush