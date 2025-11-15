@extends('layouts.app')

@section('title', 'User Center')

@section('content')

<!-- Page content -->
<section class="generic mt-3">
  <div class="col-12 col-md-6 order-2 order-md-1">
  <div class="page-title-content d-flex align-items-start mt-2">
    <span
      >Welcome, <span> {{ auth()->user()->name }}!</span> <br
    /></span>  </div>
</div>


  <!-- Page container -->
  <div class="container">
    <div class="row">
      <div class="col-xl-12">
        <div class="row">
          <div class="col-xl-6 col-md-6">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">KYC Authentication</h4>
              </div>
              <div class="card-body m-h">
                <p>
                  Verify to improve your security level and withdrawal limit
                </p>
              </div>
              <div class="card-footer">
                <a href="{{ url('kyc') }}" class="btn-2">Settings</a>
              </div>
            </div>
          </div>
          <div class="col-xl-6 col-md-6">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Account Security</h4>
              </div>
              <div class="card-body m-h">
                <!--
								security-status 
								poor
								average
								strong
								-->
                <div class="security-status poor">
                  <p>Current Account Risk Level:</p>
                  <span>High school</span>
                  
                </div>
              </div>
              <div class="card-footer">
                <a href="{{ url('security') }}" class="btn-2">Settings</a>
              </div>
            </div>
          </div>
          
          <div class="col-xl-6 col-md-6">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">User Actions</h4>
              </div>
              <div class="card-body m-h">
                <p>You can track all user movements you have made.</p>
              </div>
              <div class="card-footer">
                <a href="{{ url('login-history') }}" class="btn-2">Settings</a>
              </div>
            </div>
          </div>
         

          <div class="col-xl-6 col-md-6">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Transaction fee level</h4>
              </div>
              <div class="card-body m-h">
                <div class="security-status poor">
                  <p>Check your transaction fee</p>
                </div>
              </div>
              <div class="card-footer">
                <a href="{{ url('fees') }}" class="btn-2">Settings</a>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

@endsection