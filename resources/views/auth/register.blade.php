@extends('layouts.app')

@section('title', 'Register - Crypto Trading Platform')

@section('content')

    <body class="nk-body body-wider bg-light-alt">

        <div class="nk-wrap">

            <main class="wrapper dark-bg h-100-vh login">
                <div class="container">
                    <div class="row justify-content-center inner-wrapper">
                        <div class="col-md-12 col-lg-12 col-xl-12">
                            <div class="form-wrapper">
                                <div class="row align-items-center me-5">
                                    <div class="col-sm-3 col-md-3 col-lg-3"></div>
                                    <div class="col-sm-5 col-md-5 col-lg-5">

                                        <form action="{{ route('register') }}" class="needs-validation" method="post">
                                            @csrf
                                            <h1 class="title">
                                                <span class="h1 m-r-5 text-white font-bold">Create an account</span>
                                            </h1>
                                            @if(session('error'))
                                                <div class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                                    <span class="block sm:inline">{{ session('error') }}</span>
                                                </div>
                                            @endif
                                            @php
                                                $googleOAuthEnabled = \App\Models\Setting::get('google_oauth_enabled', false);
                                            @endphp

                                            @if($googleOAuthEnabled)
                                            <a href="{{ route('auth.google') }}"
                                                class="btn white-bg btn-block btn-lg common-text common-bg"
                                                data-onsuccess="onSignIn">

                                                <span><img src='../Public/template/epsilon/img/redesign/google-icon.svg' />
                                                    Signup with Google</span></a>
                                            @endif
                                            <br>
                                            <div class="tab-content">
                                                <div class="tab-pane fade show active" role="tabpanel" id="email-login">
                                                    <div class="form-group">
                                                        <label>Full Name</label>
                                                        <input class="form-control" placeholder="Full Name" name="name" type="text" value="{{ old('name') }}"
                                                            id="name" required/>
                                                            @error('name')
                                                                <div class="invalid-feedback d-block mt-1">
                                                                    <i class="fa fa-exclamation-circle me-1"></i> {{ $message }}
                                                                </div>
                                                            @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email">E-mail</label>
                                                        <input class="form-control" placeholder="E-mail address"
                                                            type="email" name="email" id="email" value="{{ old('email') }}" required />
                                                            @error('email')
                                                                <div class="invalid-feedback d-block mt-1">
                                                                    <i class="fa fa-exclamation-circle me-1"></i> {{ $message }}
                                                                </div>
                                                            @enderror

                                                            @error('g-recaptcha-response')
                                                                <div class="invalid-feedback d-block mt-1">
                                                                    <i class="fa fa-exclamation-circle me-1"></i> {{ $message }}
                                                                </div>
                                                            @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="enterPW1">Password</label>
                                                        <input class="form-control" placeholder="Password" type="password" name="password"
                                                            id="password"
                                                            autocomplete="no" required />
                                                        <span class="input-inner-right"><i class="fa fa-eye" id="eye1"
                                                                onclick="hideShowPass('password','eye1')"></i></span>
                                                        
                                                            @error('password')
                                                            <div class="invalid-feedback d-block mt-1">
                                                                    <i class="fa fa-exclamation-circle me-1"></i> {{ $message }}
                                                                </div>
                                                            @enderror
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="password_confirmation">Confirm Password</label>
                                                        <input class="form-control" placeholder="Confirm Password" type="password" name="password_confirmation"
                                                            id="password_confirmation"
                                                            autocomplete="no" required />
                                                        <span class="input-inner-right"><i class="fa fa-eye" id="eye1"
                                                                onclick="hideShowPass('password_confirmation','eye1')"></i></span>

                                                        @error('password_confirmation')
                                                            <div class="invalid-feedback d-block mt-1">
                                                                    <i class="fa fa-exclamation-circle me-1"></i> {{ $message }}
                                                                </div>
                                                            @enderror
                                                        
                                                    </div>


                                                     <div class="form-group">
                                                        <div class="mt-4">
                                                            <div class="g-recaptcha" 
                                                                data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}" 
                                                                data-callback="enableRegisterButton"
                                                                data-expired-callback="disableRegisterButton">
                                                            </div>
                                                            @error('recaptcha')
                                                                <div class="invalid-feedback d-block mt-1">
                                                                    <i class="fa fa-exclamation-circle me-1"></i> {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-4 d-block w-100 m-t-30">Sign
                                                Up</button>
                                            <div class="other-link">
                                                <p class="text-white">Already registered? <a href="{{ url('login') }}"
                                                        class="">Sign In</a></p>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-1"></div>

                                </div>
                            </div>
                            <!-- </div> -->
                        </div>
                        <!--/row-->
                    </div>
            </main>

        </div>
        <script type="text/javascript" src="../Public/Home/js/jquery.qrcode.min.js"></script>

       

<!-- reCAPTCHA JavaScript -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
    function enableRegisterButton() {
        document.getElementById('register-button').disabled = false;
    }
    
    function disableRegisterButton() {
        document.getElementById('register-button').disabled = true;
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        disableRegisterButton();
    });
</script>
@endsection