@extends('layouts.app')

@section('title', 'User Center')

@section('content')

<section class="generic">
<!-- Page container -->
	<div class="container">
		<div class="row mb-3 mt-3">
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
				<li class="btn btn-primary btn-sm active">Password</li>
			  </ul>
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

  @if($errors->any())
      <div class="alert alert-danger alert-dismissible p-3 fade show" role="alert">
          <i class="fa fa-exclamation-triangle-fill mr-2"></i>
          <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>
  @endif
    


		<!-- Main content -->
			<div class="card">
			<div class="card-header">
				<h1 class="card-title">Login password</h1>
				
			</div>
			<div id="hints" class="mytips" style="display:none;">
				
						
			</div>
			<div class="card-body ">
			
			<div class="form-horizontal">
				<fieldset class="content-group">
				
				<form method="POST" action="{{ url('change-password') }}">
    @csrf

    <div class="form-horizontal">
        <fieldset class="content-group">

            <div class="form-group">
                <label class="control-label col-lg-2">Old password</label>
                <div class="col-lg-5">
                    <input type="password" name="oldpassword" id="oldpassword" class="form-control" tabindex="1">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-2">New login password:</label>
                <div class="col-lg-5">
                    <input type="password" name="newpassword" id="newpassword" class="form-control" tabindex="2">
                </div>
                <span class="help-block"></span>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-2">Confirm password</label>
                <div class="col-lg-5">
                    <input type="password" name="repassword" id="repassword" class="form-control" tabindex="3">
                </div>
                <span class="help-block"></span>
            </div>

            <div class="form-group col-lg-5">
                <button tabindex="4" type="submit" class="btn btn-primary form-control">
                    Submit <i class="icon-arrow-right14 position-right"></i>
                </button>
            </div>

        </fieldset>
    </div>
</form>
		
	</div>
</div>
</div>

</section>

@endsection