@extends('layouts.app')

@section('title', 'KYC Verification')

@section('content')
<main class="wrapper">

    {{-- @include('user.components.page-title', [
        'title' => 'Authentication',
        'breadcrumbs' => [
            ['url' => '/', 'label' => 'Home'],
            ['url' => '/user-center', 'label' => 'User'],
            ['label' => 'Authentication']
        ]
    ]) --}}

    <div class="page-title my-account">
    <div class="container">
      <div class="row">
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
            <li class="btn btn-primary btn-sm active">Authentication</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

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

    <div class="container my-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">KYC Verification</h4>
            </div>

            <div class="card-body">

                @if($user->kyc_status === 'approved')
                    <div class="alert alert-success">Your KYC is approved.</div>
                @elseif($user->kyc_status === 'pending')
                    <div class="alert alert-warning">Your KYC is pending. Please wait for verification.</div>
                @elseif($user->kyc_status === 'rejected')
                    <div class="alert alert-danger">
                        Your KYC was rejected.<br>
                        <strong>Reason:</strong> {{ $user->kyc_rejection_reason }}
                    </div>
                @endif

                @if($user->kyc_status !== 'approved')
                <form action="{{ route('kyc.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">

                        <div class="col-md-6">

                            <div class="form-group mb-3">
                                <label>Full Name</label>
                                <input type="text" name="kyc_full_name" class="form-control" required>
                            </div>

                            <div class="form-group mb-3">
                                <label>Document Type</label>
                                <select name="kyc_document_type" class="form-control" required>
                                    <option value="nationalid">National ID</option>
                                    <option value="passport">Passport</option>
                                    <option value="drivers_license">Driver's License</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label>ID Number</label>
                                <input type="text" name="kyc_document_number" class="form-control" required>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="alert alert-info">
                                Submit the following documents:<br>
                                • Document Front<br>
                                • Document Back<br>
                                {{-- • Selfie holding document<br> --}}
                                • Proof of Address<br>
                            </div>

                        </div>

                        <div class="col-md-12 mt-3">

                            <div class="form-group mb-3">
                                <label>Front of Document</label>
                                <input type="file" name="kyc_front" class="form-control" required>
                            </div>

                            <div class="form-group mb-3">
                                <label>Back of Document</label>
                                <input type="file" name="kyc_back" class="form-control" required>
                            </div>

                            {{-- <div class="form-group mb-3">
                                <label>Selfie with Document</label>
                                <input type="file" name="kyc_selfie" class="form-control" required>
                            </div> --}}

                            <div class="form-group mb-4">
                                <label>Proof of Address</label>
                                <input type="file" name="kyc_proof" class="form-control" required>
                            </div>

                            <button class="btn btn-primary btn-block">Submit KYC</button>

                        </div>

                    </div>
                </form>
                @endif

            </div>
        </div>
    </div>
</main>
@endsection
