@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center bg-transparent text-light py-5 px-3">
    <div class="card bg-dark text-light shadow-lg" style="max-width: 500px; width: 100%;">
        <div class="card-body p-5 text-center">

            {{-- Header Icon --}}
            <div class="mb-4">
                <div class="mx-auto d-flex align-items-center justify-content-center bg-success rounded-circle" style="width: 60px; height: 60px;">
                    <i class="fa fa-lock text-white fa-lg"></i>
                </div>
            </div>

            <h2 class="h4 font-weight-bold mb-2">Reset your password</h2>
            <p class="text-muted mb-4">
                Enter your new password below
            </p>

            {{-- Error: Email --}}
            @error('email')
                <div class="alert alert-danger alert-dismissible fade show text-left" role="alert">
                    <i class="fa fa-exclamation-circle mr-2"></i> {{ $message }}
                    <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @enderror

            {{-- Error: Password --}}
            @error('password')
                <div class="alert alert-danger alert-dismissible fade show text-left" role="alert">
                    <i class="fa fa-exclamation-circle mr-2"></i> {{ $message }}
                    <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @enderror

            {{-- Success Message --}}
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show text-left" role="alert">
                    <i class="fa fa-check-circle mr-2"></i> {{ session('status') }}
                    <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- Reset Password Form --}}
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

                {{-- Email Field --}}
                <div class="form-group text-left">
                    <label for="email" class="font-weight-bold">Email address</label>
                    <input id="email" name="email" type="email" readonly required
                           value="{{ $email ?? old('email') }}"
                           class="form-control bg-dark text-light border-secondary"
                           placeholder="Email">
                </div>

                {{-- New Password Field --}}
                <div class="form-group text-left">
                    <label for="password" class="font-weight-bold">New Password</label>
                    <input id="password" name="password" type="password" required
                           class="form-control bg-dark text-light border-secondary @error('password') is-invalid @enderror"
                           placeholder="Enter new password">
                    @error('password')
                        <div class="invalid-feedback d-block mt-1">
                            <i class="fa fa-exclamation-circle mr-1 text-danger"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Confirm Password Field --}}
                <div class="form-group text-left">
                    <label for="password_confirmation" class="font-weight-bold">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="form-control bg-dark text-light border-secondary"
                           placeholder="Confirm new password">
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="btn btn-success btn-block mt-4">
                    <i class="fa fa-refresh mr-1"></i> Reset Password
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
