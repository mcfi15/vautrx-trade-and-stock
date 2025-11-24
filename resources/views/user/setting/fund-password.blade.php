@extends('layouts.app')

@section('title', 'User Center')

@section('content')

<main class="wrapper">
  <section class="swap-page">
    <div class="container">
      <div class="row">
        <div class="col-12 col-sm-8 col-md-7 col-lg-6 card p-20">
          <h1 class="f-s-24">Update Fund Password</h1>
          <p>Please fill out the application form to update fund password.</p>

          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          @if($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach($errors->all() as $err)
                  <li>{{ $err }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('fund.password.update') }}">
            @csrf

            <div class="uncredited-deposit-form">
              {{-- <div class="form-group">
                <div class="reg_input_box reg-fb" id="email_reg">
                  <label data-toggle="tooltip" title="CAPTCHA">Captcha:</label>
                  <div class="input-group mb-3">
                    <input name="captcha" id="verify" value="" class="form-control col-3 mr-3" />
                    <img id="verify_up" class="codeImg reloadverify"
                      src="/Verify/code"
                      title="Refresh"
                      onclick="this.src=this.src+'?t='+Math.random()"
                      width="100" height="34" />
                  </div>
                </div>
              </div> --}}

              <label for="txid" tabindex="0" data-toggle="tooltip" title="CODE">Security Code</label>
              <div class="clearfix"></div>

              <div class="input-group mb-3">
                <input name="otp" id="real_verify" class="form-control" placeholder="Enter Code" />

                <div class="input-group-append">
                  <button type="button" class="btn btn-2" onclick="emailsend();">
                    Send OTP
                  </button>
                </div>
              </div>

              <div class="form-group">
                <label for="txid" tabindex="0" data-toggle="tooltip" title="CODE">
                  Fund password
                </label>
                <div class="clearfix"></div>
                <input name="password" id="paypassword" class="form-control" placeholder="Enter New Fund Password" />
              </div>

              <button type="submit" class="btn-2">Update</button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </section>
</main>

<script>
    function emailsend() {
    fetch("{{ route('fund.password.otp') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        }
    })
    .then(r => r.json())
    .then(res => {
        alert(res.message);
    });
}

</script>

@endsection