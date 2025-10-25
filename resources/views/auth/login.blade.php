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
                        <div class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="bg-green-50 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

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
                      {{-- <div class="tab-pane fade" role="tabpanel" id="mobile-login">
                        <div class="form-group">
                          <label for="phone" class="mb-24">Phone number</label>
                          <div class="input-group">

                            <input type="text" id="cellphones" class="texts" style="display: none;">
                            <input class="pl-5 form-control" placeholder="Phone number" type="text" id="cellphone"
                              required />
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="enterPW1">Password</label>
                          <input class="form-control" placeholder="Password" type="password" id="enterPW1"
                           required />
                          <span class="input-inner-right"><i class="fa fa-eye" id="eye-1"
                              onclick="hideShowPass('enterPW1','eye-1')"></i></span>
                          <div class="invalid-feedback">
                            Your password must contain at least 6 characters, 1
                            uppercase letter, 1 lowercase letter and numbers.
                          </div>
                        </div>
                        <link rel="stylesheet" href="../Public/template/epsilon/addons/intltelinput.css">

                        <script src="../Public/template/epsilon/addons/intlTelInput.js"></script>
                        <script>
                          $("#cellphones").intlTelInput({
                            autoHideDialCode: false,
                            defaultCountry: "us",
                            nationalMode: false,
                            preferredCountries: ['us', 'uk', 'in', 'cn', 'hk', 'tw', 'mo', 'it'],
                          });
                        </script>
                      </div> --}}
                      <div class="tab-pane fade  show active" role="tabpanel" id="email-login">
                        <div class="form-group">
                          <label for="email">E-mail</label>
                          <input class="form-control" id="email" placeholder="Email" name="email" type="email"
                            required />
                        </div>
                        <div class="form-group">
                          <label for="password">Password</label>
                          <input class="form-control" type="password" id="password" name="password" placeholder="Enter password"
                            autocomplete="no" required />
                          <span class="input-inner-right"><i class="fa fa-eye" id="eye-2"
                              onclick="hideShowPass('password','eye-2')"></i></span>
                          
                        </div>
                        @error('email')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                        {{-- <div class="form-group">
                          <label for="enterPW1">Code</label>

                          <div class="invalid-feedback">
                            Code must not be empty
                          </div>
                          <div class="justify-content-between">
                            <div class="row">
                              <span class="col-8"><input id="login_verify" type="text" class="form-control has-border"
                                  placeholder="Enter Code" autocomplete="off" /></span>
                              <span class="col-4">
                                <img id="login_verify_up" class="img-responsive codeImg reloadverify"
                                  src="../Verify/code.png" title="Refresh"
                                  onclick="this.src=this.src+'?t='+Math.random()" /></span>
                            </div>
                          </div>
                        </div> --}}
                      </div>
                    </div>
                    <button type="submit" class="btn btn-2 d-block w-100 m-t-30">Sign In</button>
                    <div class="other-link">
                      <a href="">I forgot my password</a><a href="{{ url('register') }}" class="float-right">Sign
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
  <script type="text/javascript" src="../Public/Home/js/jquery.qrcode.min.js"></script>
  {{-- <script>
    function footer_user_login() {

      var username = $("#username").val();
      var password = $("#login_password").val();
      var verify = $("#login_verify").val(); if (username == "" || username == null) {
        layer.tips("Username", '#username', { tips: 2 });
        return false;
      }
      if (password == "" || password == null) {
        layer.tips("Enter password", '#login_password', { tips: 2 });
        return false;
      }

      if (verify == "" || verify == null) {
        layer.tips("Captcha", '.recaptcha', { tips: 2 });
        return false;

      }
      $.post("/Login/submit", {
        username: username,
        password: password,
        verify: verify,
        //login_token: "",
      }, function (data) {

        if (data.status == 1) {
          $.cookies.set('username', username);
          layer.msg(data.info, { icon: 1 });
          if (data.url) {
            window.location = data.url;
          } else {
            window.location = "../index.html";
          }
        } else {
          layer.msg(data.info, { icon: 2 });

          $("#login_verify_up").click(); if (data.url) {
            window.location = data.url;
          }
        }
      }, "json");
    }

    function choose_lang(lang) {
      $.cookies.set("lang", lang);
      window.location.reload();
    }
    function onSignIn(googleUser) {
      // Get user's ID token and basic profile information
      var id_token = googleUser.getAuthResponse().id_token;
      var profile = googleUser.getBasicProfile();

      // Send the ID token to your server for verification
      $.post("/verify-google-token", { id_token: id_token }, function (result) {
        if (result.success) {
          // Update UI to show user is signed in
          // ...
        } else {
          // Show error message
          // ...
        }
      });
    }
    $('#qrcode1').qrcode({
      render: "table", //table
      size: 150,
      text: '{"desktop_ip":"102.90.118.236","qr_secure":"55757282f6ee0d98e8d5cb844bc9b0c8"}', //Any content
      background: "#ffffff",
      class: "img-fluid"
    });
    let imgcontent = $('#qrcode1').html();
    function checkme() {
      $.get("/Login/checkQr", function (data) {
        console.log(302, data);
        if (data.status == 1) {
          console.log(data)
          layer.msg(data.info, { icon: 1 });
          if (data.url) {
            window.location = data.url;
          } else {
            window.location = "../index.html";
          }
        } else {

        }
      }, "json");
    }

  </script> --}}
@endsection
