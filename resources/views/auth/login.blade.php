@extends('layouts.app')

@section('title', 'Login - Crypto Trading Platform')

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


                  
                  <form action="{{ route('login') }}" class="needs-validation" method="post" >
                    @csrf
                    <h1 class="title text-center m-b-30">
                      <span class="h1 m-r-5 text-white font-bold">Sign in to Dectrx</span>
                    </h1>

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible p-3 fade show" role="alert">
                            <i class="fa fa-exclamation-triangle-fill mr-2"></i>
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible p-3 fade show" role="alert">
                            <i class="fa fa-check-circle-fill mr-2"></i>
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif


                        

                        @error('password')
                            <div class="invalid-feedback d-block mt-1 p-3">
                                <i class="fa fa-exclamation-circle mr-1 text-danger"></i>
                                {{ $message }}
                            </div>
                        @enderror

                        @error('g-recaptcha-response')
                            <div class="invalid-feedback d-block mt-1 p-3">
                                <i class="fa fa-exclamation-circle mr-1 text-danger"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <br>

                    @php
                        $googleOAuthEnabled = \App\Models\Setting::get('google_oauth_enabled', false);
                    @endphp
                    @if($googleOAuthEnabled)
                    <a href="{{ route('auth.google') }}"
                      class="btn white-bg btn-block btn-lg common-text" data-onsuccess="onSignIn">

                      <span><img src='../Public/template/epsilon/img/redesign/google-icon.svg' /> Signin with
                        Google</span></a>
                    @endif
                    <br />

                    <ul id="login-tabs" class="nav nav-pills nav-fill" role="tablist">
                      {{-- <li class="nav-item">
                        <a aria-selected="true" class="nav-link " data-toggle="pill" href="#mobile-login"
                          data-target="#mobile-login" role="tab">
                          <span>Phone Login</span></a>
                      </li> --}}
                      <li class="nav-item">
                        <a aria-selected="false" class="nav-link active" data-toggle="pill" href="#email-login"
                          data-target="#email-login" role="tab">
                          <span>E-mail Login </span></a>
                      </li>
                    </ul>

                    <div class="tab-content">
                        
                      <div class="tab-pane fade  show active" role="tabpanel" id="email-login">
                        
                        <div class="form-group">
                          <label for="email">E-mail</label>
                          <input class="form-control" id="email" placeholder="Email" name="email" type="email"
                            required />
                            @error('email')
                                <div class="invalid-feedback d-block mt-1 p-3">
                                    <i class="fa fa-exclamation-circle mr-1 text-danger"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                          <label for="password">Password</label>
                          <input class="form-control" type="password" id="password" name="password" placeholder="Enter password"
                            autocomplete="no" required />
                          <span class="input-inner-right"><i class="fa fa-eye" id="eye-2"
                              onclick="hideShowPass('password','eye-2')"></i>
                          </span>
                        </div>
                        
                        <div class="form-group">
                          <div class="mt-4">
                              <div class="g-recaptcha" 
                                  data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}" 
                                  data-callback="enableLoginButton"
                                  data-expired-callback="disableLoginButton">
                              </div>
                          </div>
                          
                        </div> 
                      </div>
                    </div>
                    <button type="submit" style="background-color: #1e90ff; border-color:white;" class="btn btn-2 d-block w-100 m-t-30">Sign In</button>
                    <div class="other-link">
                      <a href="{{ route('password.request') }}">I forgot my password</a><a href="{{ url('register') }}" class="float-right text-white">Sign
                        up</a>
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
  <script type="text/javascript" src="{{ asset('Public/Home/js/jquery.qrcode.min.js') }}"></script>
  
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script>
      function enableLoginButton() {
          document.getElementById('login-button').disabled = false;
      }
      
      function disableLoginButton() {
          document.getElementById('login-button').disabled = true;
      }
      
      // Initialize on page load
      document.addEventListener('DOMContentLoaded', function() {
          disableLoginButton();
      });
  </script>
@endsection
