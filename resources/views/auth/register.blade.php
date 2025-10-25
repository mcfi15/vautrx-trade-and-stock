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
                                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                                            @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email">E-mail</label>
                                                        <input class="form-control" placeholder="E-mail address"
                                                            type="email" name="email" id="email" value="{{ old('email') }}" required />
                                                            @error('email')
                                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
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
                                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
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
                                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                                            @enderror
                                                        
                                                    </div>


                                                    {{-- <div class="form-group">
                                                        <label for="enterPW1">Code</label>

                                                        <div class="invalid-feedback">
                                                            Code must not be empty
                                                        </div>
                                                        <div class="d-flex field-item small">
                                                            <div class="row">
                                                                <span class="col-8"><input id="login_verify" type="text"
                                                                        class="form-control has-border"
                                                                        placeholder="Enter Code"
                                                                        autocomplete="off" /></span>
                                                                <span class="col-4">
                                                                    <img id="login_verify_up"
                                                                        class="img-responsive codeImg reloadverify"
                                                                        src="../Verify/code.png" title="Refresh"
                                                                        onclick="this.src=this.src+'?t='+Math.random()" /></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email" id="inviteCodeLabelArrowDown" class=""
                                                            style="cursor: pointer;" onclick="inviteCode();">Invite Code
                                                            (Optional) &#x2193;</label>
                                                        <label for="email" id="inviteCodeLabelArrowUp"
                                                            class="hideInviteCode" style="cursor: pointer;"
                                                            onclick="inviteCode();">Invite Code (Optional) &#x2191;</label>
                                                    </div>
                                                    <div class="form-group hideInviteCode" id="email_invit">
                                                        <input class="form-control" placeholder="Invite code" type="email"
                                                            id="email_invit" required />
                                                    </div> --}}
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

        <script>
            $(function () {
                var inviteurl = new URL(window.location.html);
                var invitecode = inviteurl.searchParams.get("invite");
                document.getElementById("email_invit").value = invitecode;
                var height = $(document).height();
                if (height < 1000) {
                    height = 1000;
                }
                $('#step2').height(height);
                $('#email_step2').height(height);
                $("#cellphone").focus();
            });

            function inviteCode() {
                $("#email_invit").toggleClass("hideInviteCode");
                $("#inviteCodeLabelArrowUp").toggleClass("hideInviteCode");
                $("#inviteCodeLabelArrowDown").toggleClass("hideInviteCode");
            }
            function regWeb() {
                layer.open({
                    type: 2,
                    skin: 'layui-layer-rim', //frame
                    area: ['800px', '600px'], // width height
                    title: 'User Agreement', // title
                    content: "/Login/webreg"
                });
            }

            function showB() {
                $("#step1").hide();
                $("#step2").hide();
                $("#verify").focus();
            }
            function showE() {
                $("#email_step1").hide();
                $("#email_step2").hide();
                $("#everify").focus();
            }
            function check_username() {
                var username = $('#username').val();
                if (username == "" || username == null) {
                    layer.tips("Please choose username", '#username', { tips: 2 });
                    return false;
                }
                $.post("/Login/check_username", {
                    username: username,
                    token: ""
                }, function (data) {
                    if (data.status == 1) {
                        layer.tips("Good!!", '#username', { tips: 2 });
                        return true;
                    } else {
                        layer.tips(data.info, '#username', { tips: 2 });
                        return false;
                    }
                }, 'json');
            }
            function check_email() {
                var email = $('#email').val();
                if (email == "" || email == null) {
                    layer.tips("Please enter email", '#email', { tips: 2 });
                    return false;
                }
                $.post("/Login/check_email", {
                    email: email,
                    token: ""
                }, function (data) {
                    if (data.status == 1) {
                        $("#email_step1").show();
                        $("#email_step2").show();
                        $("#everify").focus();
                        layer.tips("Good!", '#email', { tips: 2 });
                    } else {
                        layer.tips(data.info, '#email', { tips: 2 });
                        return false;
                    }
                }, 'json');
            }

            function verify_ups() {
                $('#verify_up').attr('src', "/Ajax/verify?t=" + Math.random());
            }
            function new_send() {
                $("#step1").show();
                $("#step2").show();
                $("#verify").focus();
            }
            function new_send_email() {
                $("#email_step1").show();
                $("#email__step2").show();
                $("#everify").focus();
            }


            function validateEmail(sEmail) {
                var reEmail = /^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{|}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{|}\~]+@(?:(?:(?:[a-zA-Z0-9](?:[a-zA-Z0-9\-](?!\.)){0,61}[a-zA-Z0-9]?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9\-](?!$)){0,61}[a-zA-Z0-9]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/;

                if (!sEmail.match(reEmail)) {
                    alert("Invalid email address");
                    return false;
                }

                return true;

            }
            function reg_email() {
                var username = $("#username").val();
                var email = $("#email").val();
                var verify = $("#login_verify").val(); var password = $("#email_password").val();
                var invit = $("#email_invit").val();
                // $("input[name='accounttype']:checked").val()
                var accounttype = "1";
                if (email == "" || email == null || validateEmail(email) == false) {
                    layer.tips("PLEASE_ENTER_VALID_EMAIL", '#email', { tips: 2 });
                    return false;
                }
                if (username == "" || username == null) {
                    layer.tips("Please enter username", '#username', { tips: 2 });
                    return false;
                }

                if (password == "" || password == null) {
                    layer.tips("Enter password", '#email_password', { tips: 2 });
                    return false;
                }
                if (verify == "" || verify == null) {
                    layer.tips("Please enter Captcha", '#login_verify', { tips: 2 });
                    return false;
                }
                if (accounttype == "" || accounttype == null) {
                    layer.tips("Please Choose acount type", '#account_type', { tips: 2 });
                    return false;
                }
                // if (!$("#emailregweb").is(':checked')) {
                // 	layer.tips("Check User Registration Agreement", '#emailregweb', {tips: 3});
                // //	return false;
                // }
                $.post("/Login/emailsignup", {
                    email: email,
                    username: username,
                    verify: verify,
                    password: password,
                    invit: invit,
                    accounttype: accounttype,
                    token: ""
                }, function (data) {
                    if (data.status == 1) {
                        layer.msg(data.info, { icon: 1 });
                        $.cookies.set('exchange_email', email);
                        window.location = 'thankyou.html';
                    } else {
                        layer.msg(data.info, { icon: 2 });
                        if (data.url) {
                            window.location = data.url;
                        }
                    }
                }, "json");
            }
            $('#qrcode1').qrcode({
                render: "table", //table
                size: 150,
                text: '{"desktop_ip":"102.90.118.236","qr_secure":"7991c5a10a16cdcd2ce048285217e998"}', //Any content
                background: "#ffffff",
                class: "img-fluid"
            });
            let imgcontent = $('#qrcode1').html();
        </script>
        <style>
            #rc-imageselect {
                transform: scale(0.77);
                -webkit-transform: scale(0.77);
                transform-origin: 0 0;
                -webkit-transform-origin: 0 0;
            }
        </style>
@endsection