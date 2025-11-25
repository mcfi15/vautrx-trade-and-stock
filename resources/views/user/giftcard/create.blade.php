@extends('layouts.app')

@section('title', 'Create Gift Card')

@section('content')

<main class="wrapper grey-bg launchpad-page">
    <div class="page-top-banner">
        <div class="filter" style="background-image: url('/Public/template/epsilon/img/redesign/slider/filter2-min.png');">
            <div class="container">
                <div class="row align-items-center justify-content-between">
                    <div class="col-md-8 col-lg-7 col-xl-7">
                        <h1>Create Gift Card</h1>
                        <h2>Send cryptocurrency as a gift to your friends and family</h2>
                    </div>
                    <div class="col-8 col-sm-6 col-md-4 col-lg-4 col-xl-5">
                        <img src="{{ asset('Public/template/epsilon/img/launchpad-banner-img.png') }}" alt="" class="img-fluid" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="page-inner">
        <div class="container mt-3">
            <div class="row m-b-40 justify-content-center">
                <div class="col-xl-8">
                    <div class="row">
                        <div class="col-12 col-md-6 order-2 order-md-1">
                            <div class="page-title-content d-flex align-items-start mt-2">
                                <h3>Create New Gift Card</h3>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 order-1 order-md-2 float-right">
                            <ul class="text-right breadcrumbs list-unstyle">
                                <li>
                                    <a href="{{ route('giftcard.index') }}" class="btn btn-primary btn-sm">Gift Card Home</a>
                                </li>
                                <li class="btn btn-primary btn-sm active">Create Gift Card</li>
                            </ul>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Gift Card Details</h4>
                        </div>
                        <div class="card-body">
                            @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @endif

                            <form action="{{ route('giftcard.store') }}" method="POST">
                                @csrf
                                
                                <div class="form-group">
                                    <label for="title">Gift Card Title *</label>
                                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required placeholder="e.g., Birthday Gift, Christmas Present">
                                    @error('title')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="cryptocurrency_id">Cryptocurrency *</label>
                                    <select class="form-control" id="cryptocurrency_id" name="cryptocurrency_id" required>
                                        <option value="">Select Cryptocurrency</option>
                                        @foreach($cryptocurrencies as $crypto)
                                        <option value="{{ $crypto->id }}" {{ old('cryptocurrency_id') == $crypto->id ? 'selected' : '' }}>
                                            {{ $crypto->name }} ({{ $crypto->symbol }})
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('cryptocurrency_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="amount">Amount *</label>
                                    <input type="number" step="0.00000001" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" required placeholder="0.00000000">
                                    @error('amount')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="message">Personal Message (Optional)</label>
                                    <textarea class="form-control" id="message" name="message" rows="3" placeholder="Add a personal message for the recipient">{{ old('message') }}</textarea>
                                    @error('message')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="expires_at">Expiration Date (Optional)</label>
                                    <input type="datetime-local" class="form-control" id="expires_at" name="expires_at" value="{{ old('expires_at') }}">
                                    <small class="form-text text-muted">If not set, the gift card will never expire.</small>
                                    @error('expires_at')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="alert alert-info">
                                    <h6><i class="fa fa-info-circle"></i> Important Information</h6>
                                    <ul class="mb-0">
                                        <li>The amount will be deducted from your wallet immediately</li>
                                        <li>You will receive a unique gift card code to share with the recipient</li>
                                        <li>The recipient can redeem the gift card to receive the cryptocurrency</li>
                                        <li>Gift cards cannot be canceled once created</li>
                                    </ul>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fa fa-gift"></i> Create Gift Card
                                    </button>
                                    <a href="{{ route('giftcard.index') }}" class="btn btn-secondary btn-lg">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    // Add any additional JavaScript if needed
    document.addEventListener('DOMContentLoaded', function() {
        // You can add form validation or dynamic behavior here
    });
</script>

<style>
    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        border: 1px solid #e3e6f0;
    }
    
    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
    }
    
    .breadcrumbs {
        display: flex;
        justify-content: flex-end;
        gap: 5px;
    }
    
    .breadcrumbs li {
        display: inline-block;
    }
</style>

@endsection