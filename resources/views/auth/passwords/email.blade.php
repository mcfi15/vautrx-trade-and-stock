@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center bg-transparent text-light py-5 px-3">
    <div class="card bg-dark text-light shadow-lg" style="max-width: 500px; width: 100%;">
        <div class="card-body p-5 text-center">

            {{-- Header Icon --}}
            <div class="mb-4">
                <div class="mx-auto d-flex align-items-center justify-content-center bg-primary rounded-circle" style="width: 60px; height: 60px;">
                    <i class="fa fa-unlock-alt text-white fa-lg"></i>
                </div>
            </div>

            <h2 class="h4 font-weight-bold mb-2">Forgot your password?</h2>
            <p class="text-muted mb-4">
                No problem. Just let us know your email address and we’ll send you a password reset link.
            </p>

            {{-- Success Message --}}
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show text-left" role="alert">
                    <i class="fa fa-check-circle mr-2"></i> {{ session('status') }}
                    <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- Forgot Password Form --}}
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group text-left">
                    <label for="email" class="font-weight-bold">Email address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           value="{{ old('email') }}"
                           class="form-control bg-dark text-light border-secondary @error('email') is-invalid @enderror"
                           placeholder="Enter your email">

                    @error('email')
                        <div class="invalid-feedback d-block mt-1">
                            <i class="fa fa-exclamation-circle mr-1 text-danger"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-block mt-4">
                    <i class="fa fa-envelope mr-1"></i> Email Password Reset Link
                </button>

                <div class="mt-4 text-center">
                    <a href="{{ route('login') }}" class="text-info small">
                        <i class="fa fa-arrow-left mr-1"></i> Back to Login
                    </a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
