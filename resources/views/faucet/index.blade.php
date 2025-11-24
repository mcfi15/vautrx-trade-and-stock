@extends('layouts.app')

@section('title', 'Faucet')

@section('content')

<div class="container">
  <div class="row mt-3">
    <div class="col-12 col-md-6 order-2 order-md-1">
  <div class="page-title-content d-flex align-items-start mt-2">
      </div>
</div>

    <div class="col-9 col-md-6 order-1 order-md-2 float-right">
      <ul class="text-right breadcrumbs list-unstyle">
        <li class="btn btn-primary btn-sm active">Faucet Home</li>
        <li>
          <a class="btn btn-primary btn-sm" 
            >Faucet Logs</a
          >
        </li>
        <li>
          <a class="btn btn-primary btn-sm" 
            >Faucet All Logs</a
          >
        </li>
      </ul>
    </div>
  </div>

  
  <div class="row p-t-30">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Faucets</h4>
                </div>
                <div class="card-body">

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

                    <div class="codono-distribution">
                        <div class="row p-t-40 p-b-40">
                            <div class="col-12">
                                <ul class="nav nav-pills nav-fill" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="pill" href="#on-process" role="tab">Processing</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="pill" href="#upcoming" role="tab">Upcoming</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="pill" href="#ended" role="tab">Ended</a>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-12">
                                <div id="crowdfundingcontent" class="tab-content">

                                    <!-- ================= PROCESSING ================= -->
                                    <div class="tab-pane fade p-t-15 show active" id="on-process">
                                        @if($processing->count() == 0)
                                            <div class="no-data">
                                                <i style="color:#1e90ff;" class="fa fa-file"></i>
                                                <p style="color: white;">No records</p>
                                            </div>
                                        @else
                                            @foreach($processing as $faucet)
                                                <div class="card p-3 mb-3 border">
                                                    <h5>{{ $faucet->title }}</h5>
                                                    <p>Amount: {{ $faucet->amount }} {{ $faucet->coin->symbol ?? '' }}</p>
                                                    <p>Start: {{ $faucet->start_at }}</p>
                                                    <p>End: {{ $faucet->end_at }}</p>

                                                    @auth
                                                    @php
                                                        $already = $faucet->logs()->where('user_id', auth()->id())->exists();
                                                        $eligible = true;

                                                        // optional: if faucet has holding requirement
                                                        if ($faucet->holding_currency_id ?? false) {
                                                            $wallet = \App\Models\Wallet::where('user_id', auth()->id())
                                                                        ->where('cryptocurrency_id', $faucet->holding_currency_id)
                                                                        ->first();
                                                            $available = $wallet ? $wallet->balance : 0;
                                                            $eligible = bccomp((string)$available, (string)$faucet->min_hold_amount, 18) >= 0;
                                                        }
                                                    @endphp

                                                    @if($already)
                                                        <button class="btn btn-secondary" disabled>Already claimed</button>
                                                    @elseif(!$eligible)
                                                        <button class="btn btn-secondary" disabled>Not eligible</button>
                                                    @else
                                                        <form action="{{ route('faucets.claim', $faucet->id) }}" method="POST" style="display:inline">
                                                            @csrf
                                                            <button class="btn btn-success btn-sm">Claim</button>
                                                        </form>
                                                    @endif
                                                @else
                                                    <a href="{{ route('login') }}" class="btn btn-success btn-sm">Login to claim</a>
                                                @endauth

                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    <!-- ================= UPCOMING ================= -->
                                    <div class="tab-pane fade p-t-15" id="upcoming">
                                        @if($upcoming->count() == 0)
                                            <div class="no-data">
                                                <i style="color:#1e90ff;" class="fa fa-file"></i>
                                                <p style="color:white;">No records</p>
                                            </div>
                                        @else
                                            @foreach($upcoming as $faucet)
                                                <div class="card p-3 mb-3 border">
                                                    <h5>{{ $faucet->title }}</h5>
                                                    <p>Amount: {{ $faucet->amount }} {{ $faucet->coin->symbol ?? '' }}</p>
                                                    <p>Starts at: {{ $faucet->start_at }}</p>
                                                    <span class="badge badge-primary">Upcoming</span>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    <!-- ================= ENDED ================= -->
                                    <div class="tab-pane fade p-t-15" id="ended">
                                        @if($ended->count() == 0)
                                            <div class="no-data">
                                                <i style="color:#1e90ff;" class="fa fa-file"></i>
                                                <p style="color:white;">No records</p>
                                            </div>
                                        @else
                                            @foreach($ended as $faucet)
                                                <div class="card p-3 mb-3 border">
                                                    <h5>{{ $faucet->title }}</h5>
                                                    <p>Amount: {{ $faucet->amount }} {{ $faucet->coin->symbol ?? '' }}</p>
                                                    <p>Ended at: {{ $faucet->end_at }}</p>
                                                    <span class="badge badge-danger">Ended</span>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-body">
          <table
            class="table last-login-table table-striped table-hover"
            id="orderlist"
          ></table>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  				var hints = "0";
  				if(hints == 1){
  					$('#hints').show();
  				}
  				$('#Faucet_box').addClass('active');

  				$(".static").click(function() {
  					$(".tab-pane").removeClass("in");
  					$(".tab-pane").removeClass("active");
  					$(".static").removeClass("active");
  					$(this).addClass("active");
  					$("#" + $(this).attr("role")).addClass("active in");
  				});



  	function buy_up(id) {
  	  	layer.msg("Please login first", {icon: 2});
  	  	}
  function getUselog() {
          $.getJSON("/Faucet/getUselog?t=" + Math.random(), function (data) {
              if (data) {
                  if (data['consumelog']) {
                      var list = '<thead><tr><th>Time</th><th>Nickname</th><th>Coin</th><th>Amount</th></tr></thead><tbody>';
                      var type = '';
                      var typename = '';
                      for (var i in data['consumelog']) {
  					var xdate = new Date(data['consumelog'][i]['addtime'] * 1000);
  					var iso = xdate.toISOString().match(/(\d{4}\-\d{2}\-\d{2})T(\d{2}:\d{2}:\d{2})/)
  					var truedate=iso[1] + ' ' + iso[2];
                              list += '<tr><td >' + truedate + '</td><td >' + data['consumelog'][i]['username'] + '</td><td >' + data['consumelog'][i]['coinname'] + '</td><td >' + data['consumelog'][i]['price'] + '</td></tr>';
  							}
  							list +='</tbody>'
                      $("#orderlist").html(list);
                  }
              }
          });
          setTimeout('getUselog()', 5000);
      }
  	$(function () {
          getUselog();
      });
</script>

@endsection