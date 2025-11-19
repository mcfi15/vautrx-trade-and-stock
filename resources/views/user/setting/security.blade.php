@extends('layouts.app')

@section('title', 'KYC Verification')

@section('content')

<!-- Page content -->
<section class="generic">
  <div class="container">
    <div class="row mt-3 mb-3">
      <div class="col-12 col-md-6 order-2 order-md-1">
  <div class="page-title-content d-flex align-items-start mt-2">
    <span
      >Welcome, <span> {{ auth()->user()->name }}!</span> <br
    /></span>  </div>
</div>

      <div class="col-12 col-md-6 order-1 order-md-2 float-right">
        <ul class="text-right breadcrumbs list-unstyle">
          <li>
            <a class="btn btn-primary btn-sm" href="/">Home</a>
          </li>
          <li>
            <a href="{{ url('user-center') }}" class="btn btn-primary btn-sm"
              >User</a
            >
          </li>
          <li class="btn btn-primary btn-sm active">Security</li>
        </ul>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="alert alert-primary p-t-30 p-b-30" role="alert">
          <h3><span style="color:black;">Current account risk level: Medium</span></h3>
          <p class="m-b-0">
            It is recommended that you enable Google Authenticator to protect
            the security of your assets.
          </p>
        </div>
      </div>
      <div class="col-xl-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Account Security</h5>
          </div>
          <div class="card-body">
            <div class="settings-security">
              <ul>
                <li>
                  <div class="security-info">
                    <p>Login Password</p>
                    <span
                      >Increasing the strength of your password helps to
                      increase the security of your money</span
                    >
                  </div>
                  <a href="{{ url('change-password') }}" class="btn-2">Change</a>
                </li>
                {{-- <li>
                  <div class="security-info">
                    <p>Fund Password</p>
                    <span
                      >You can change fund password for withdrawal
                      confirmation</span
                    >
                  </div>
                  <a href="/User/paypassword" class="btn-2">Change</a>
                </li>
                <li>
                  <div class="security-info">
                    <p>Cell phone verification</p>
                    <span
                      >You can use it to verify the SMS code when withdrawing
                      cash, changing passwords and setting security.</span
                    >
                  </div>
                  <a href="/User/cellphone" class="btn-2">Verify</a>
                </li>                <li> --}}
                  {{-- <div class="security-info">
                    <p>Google Authenticator</p>
                    <span
                      >Google Authentication code required when withdrawing cash
                      and changing security settings</span
                    >
                  </div>
                  <a href="/User/ga" class="btn-2">Settings</a>
                </li>
                <li>
                  <div class="security-info">
                    <p>Anti-phishing code</p>
                    <span
                      >The email sent will contain the anti-phishing code you
                      set to prevent fraudulent emails.</span
                    >
                  </div>
                  <a href="/User/antiphishing" class="btn-2">Settings</a>
                </li> --}}
              </ul>
            </div>
          </div>
        </div>
        {{-- <div class="card">
          <div class="card-header">
            <h5 class="card-title">Other Settings</h5>
          </div>
          <div class="card-body">
            <div class="settings-security">
              <ul>
                <li>
                  <div class="security-info">
                    <p>Frozen Account</p>
                    <span
                      >Increasing the strength of your password helps to
                      increase the security of your money</span
                    >
                  </div>
                  <a href="/User/freeze" class="btn account-freeze-btn"
                    >Freeze Account!</a
                  >
                </li>
              </ul>
            </div>
          </div>
        </div> --}}
      </div>
    </div>
  </div>
</section>

@endsection