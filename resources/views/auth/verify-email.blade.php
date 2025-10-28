@extends('layouts.app')

@section('title', 'Verify Email Address')

@section('content')
@php
    $email = $user->email ?? (session('user_email') ?? 'Unknown');

    // Secure (mask) the email — e.g. john.doe@gmail.com → joh***@gm***.com
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        [$name, $domain] = explode('@', $email);

        // Mask the name (keep first 3 characters)
        $maskedName = substr($name, 0, 3) . str_repeat('*', max(strlen($name) - 3, 0));

        // Mask the domain but keep first 2 letters before the first dot
        $domainParts = explode('.', $domain);
        $domainMain = $domainParts[0];
        $maskedDomain = substr($domainMain, 0, 2) . str_repeat('*', max(strlen($domainMain) - 2, 0));
        $domainExtension = isset($domainParts[1]) ? '.' . $domainParts[1] : '';

        $email = $maskedName . '@' . $maskedDomain . $domainExtension;
    }
@endphp

<div class="min-vh-100 d-flex align-items-center justify-content-center bg-transparent text-light py-5 px-3">
    <div class="card bg-dark text-light shadow-lg" style="max-width: 500px; width: 100%;">
        <div class="card-body text-center p-5">

            <div class="mb-4">
                <div class="mx-auto d-flex align-items-center justify-content-center bg-warning rounded-circle" style="width: 60px; height: 60px;">
                    <i class="fa fa-exclamation-triangle text-dark fa-lg"></i>
                </div>
            </div>

            <h2 class="h4 font-weight-bold mb-2">Verify Your Email Address</h2>
            <p class="text-muted mb-4">Please check your email for a verification link.</p>

            {{-- Email Display --}}
            <div class="bg-dark border border-info rounded p-3 mb-4">
                <p class="mb-0 font-weight-bold text-info">
                    {{ $email }}
                </p>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show text-left" role="alert">
                    <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
                    <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- Error Message --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show text-left" role="alert">
                    <i class="fa fa-times-circle mr-2"></i> {{ session('error') }}
                    <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <p class="text-muted small mb-4">
                Click the link in the email to verify your address. The link will expire in 24 hours.
            </p>

            {{-- Resend Verification --}}
            <form method="POST" action="{{ route('verification.resend') }}" class="mb-3">
                @csrf
                <input type="hidden" name="email" value="{{ $user->email ?? (session('user_email') ?? '') }}">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fa fa-envelope mr-1"></i> Resend Verification Email
                </button>
            </form>

            {{-- Sign Out --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-block">
                    <i class="fa fa-sign-out-alt mr-1"></i> Sign Out
                </button>
            </form>

        </div>
    </div>
</div>
@endsection
