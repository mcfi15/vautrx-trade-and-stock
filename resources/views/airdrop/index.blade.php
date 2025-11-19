@extends('layouts.app')

@section('title', 'Airdrop')

@section('content')

<main class="wrapper grey-bg airdrop-page">
  <div class="page-top-banner">
    <div class="filter" style="background-image: url('{{ asset('Public/template/epsilon/img/redesign/slider/filter2-min.png') }}');">
      <div class="container">
        <div class="row align-items-center justify-content-between">
          <div class="col-md-8 col-lg-7 col-xl-7">
            <h1>Gift Rain</h1>
            <h2></h2>
          </div>
          <div class="col-8 col-sm-6 col-md-4 col-lg-4 col-xl-5">
            <img src="{{ asset('Public/template/epsilon/img/redesign/airdrop-banner.png') }}" class="img-fluid" />
          </div>
        </div>
      </div>
    </div>
  </div>

  <section class="page-inner">
    <div class="container">
      <div class="row m-t-30 m-b-40 justify-content-center">
        <div class="col-xl-11">
          <div class="card">
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

              @if(isset($processing) && $processing->count())
                @foreach($processing as $airdrop)
                <div class="airdrop-single-item airdrop-item arrow-black mb-4">
                  <div class="row align-items-center">
                    <div class="col-lg-6">
                      @if($airdrop->image)
                        <img src="{{ asset($airdrop->image) }}" alt="" class="img-fluid" />
                      @endif
                    </div>
                    <div class="col-lg-6">
                      <h3 class="title">{{ $airdrop->title }}</h3>
                      <ul>
                        <li><b>Hold - </b> {{ $airdrop->holdingCurrency ? $airdrop->holdingCurrency->symbol : '—' }}</li>
                        <li><b>Airdrop - </b> {{ $airdrop->airdrop_amount }} {{ $airdrop->airdropCurrency->symbol }}</li>
                        <li><b>Start Time </b> {{ $airdrop->start_at?->format('Y-m-d H:i:s') ?? 'N/A' }}</li>
                        <li><b>End Time </b> {{ $airdrop->end_at?->format('Y-m-d H:i:s') ?? 'N/A' }}</li>
                      </ul>

                      @auth
                        @php
                          $already = $airdrop->claims()->where('user_id', auth()->id())->exists();
                          $eligible = true;
                          if ($airdrop->holding_currency_id) {
                              $wallet = \App\Models\Wallet::where('user_id', auth()->id())->where('cryptocurrency_id', $airdrop->holding_currency_id)->first();
                              $available = $wallet ? $wallet->available_balance : 0;
                              $eligible = bccomp((string)$available, (string)$airdrop->min_hold_amount, 18) >= 0;
                          }
                        @endphp

                        @if($already)
                          <button class="btn btn-secondary" disabled>Already claimed</button>
                        @elseif(!$eligible)
                          <button class="btn btn-secondary" disabled>Not eligible</button>
                        @else
                          <form action="{{ route('airdrops.claim', $airdrop->id) }}" method="POST" style="display:inline">
                            @csrf
                            <button class="btn btn-primary">Claim</button>
                          </form>
                        @endif
                      @else
                        <a href="{{ route('login') }}" class="btn btn-primary">Login to claim</a>
                      @endauth

                    </div>
                  </div>
                </div>
                @endforeach
              @else
                <p>No active airdrops.</p>
              @endif

            </div>
          </div>
        </div>

        <!-- Tabs for processing/upcoming/ended -->
        <div class="col-xl-11 m-t-30 airdrop-list">
          <div class="card">
            <div class="card-header">
              <ul class="nav nav-pills" role="tablist">
                <li class="nav-item">
                  <a aria-selected="true" class="nav-link active" data-toggle="pill" href="#processing" role="tab">Processing</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" data-toggle="pill" href="#upcoming" role="tab">Upcoming</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" data-toggle="pill" href="#ended" role="tab">Ended</a>
                </li>
              </ul>
            </div>
            <div class="card-body">
              <div class="tab-content">
                <div class="tab-pane fade show active" id="processing" role="tabpanel">
                  <div class="row">
                    @foreach($processing as $airdrop)
                      <div class="col-sm-6 col-md-4 col-lg-4">
                        <div class="thumb" style="min-height:200px">
                          <img src="{{ $airdrop->image ? asset($airdrop->image) : '/Public/template/epsilon/img/no-img.png' }}" class="img-fluid" style="width:120px; height:120px;">
                        </div>
                        <div class="caption">
                          <h6 class="title">{{ $airdrop->title }}</h6>
                          <ul>
                            <li>Holding:<span>{{ $airdrop->holdingCurrency?->symbol ?? '—' }}</span></li>
                            <li>Airdrop:<span>{{ $airdrop->airdrop_amount }} {{ $airdrop->airdropCurrency->symbol }}</span></li>
                            <li>Start Date:<span>{{ $airdrop->start_at?->format('Y-m-d H:i:s') }}</span></li>
                            <li>End Date:<span>{{ $airdrop->end_at?->format('Y-m-d H:i:s') }}</span></li>
                          </ul>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>

                <div class="tab-pane fade" id="upcoming" role="tabpanel">
                  <div class="row">
                    @foreach($upcoming as $airdrop)
                      <div class="col-sm-6 col-md-4 col-lg-4">
                        <div class="thumb" style="min-height:200px">
                          <img src="{{ $airdrop->image ? asset($airdrop->image) : '/Public/template/epsilon/img/no-img.png' }}" class="img-fluid" style="width:120px; height:120px;">
                        </div>
                        <div class="caption">
                          <h6 class="title">{{ $airdrop->title }}</h6>
                          <ul>
                            <li>Holding:<span>{{ $airdrop->holdingCurrency?->symbol ?? '—' }}</span></li>
                            <li>Airdrop:<span>{{ $airdrop->airdrop_amount }} {{ $airdrop->airdropCurrency->symbol }}</span></li>
                            <li>Start Date:<span>{{ $airdrop->start_at?->format('Y-m-d H:i:s') }}</span></li>
                            <li>End Date:<span>{{ $airdrop->end_at?->format('Y-m-d H:i:s') }}</span></li>
                          </ul>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>

                <div class="tab-pane fade" id="ended" role="tabpanel">
                  <div class="row">
                    @foreach($ended as $airdrop)
                      <div class="col-sm-6 col-md-4 col-lg-4">
                        <div class="thumb" style="min-height:200px">
                          <img src="{{ $airdrop->image ? asset($airdrop->image) : '/Public/template/epsilon/img/no-img.png' }}" class="img-fluid" style="width:120px; height:120px;">
                        </div>
                        <div class="caption">
                          <h6 class="title">{{ $airdrop->title }}</h6>
                          <ul>
                            <li>Holding:<span>{{ $airdrop->holdingCurrency?->symbol ?? '—' }}</span></li>
                            <li>Airdrop:<span>{{ $airdrop->airdrop_amount }} {{ $airdrop->airdropCurrency->symbol }}</span></li>
                            <li>Start Date:<span>{{ $airdrop->start_at?->format('Y-m-d H:i:s') }}</span></li>
                            <li>End Date:<span>{{ $airdrop->end_at?->format('Y-m-d H:i:s') }}</span></li>
                          </ul>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
</main>

@endsection