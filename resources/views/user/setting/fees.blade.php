@extends('layouts.app')

@section('title', 'User Center')

@section('content')

<div class="page-top-banner">
  <div
    class="filter"
    style="
      background-image: url('/Public/template/epsilon/img/redesign/slider/filter2-min.png');
    "
  >
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-8 mt-3">
          <h1>{{ \App\Models\Setting::get('site_name', 'Website Name') }}</h1>
          <h2></h2>
        </div>
      </div>
    </div>
  </div>
</div>
<section class="container">
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
            <a href="{{ url('user-center') }}" class="btn btn-primary btn-sm">User</a>
          </li>
          <li class="btn btn-primary btn-sm active">Fees</li>
        </ul>
      </div>
    </div>
  </div>

  <div class="card timeline-content">
    <div class="card-header">
        <h6 class="card-title text-semibold">Trading Fees</h6>
        <div class="heading-elements"></div>
    </div>

    <div class="card-body">
        <div class="row table-responsive">
            <table class="table text-nowrap text-center">
                <thead>
                    <tr>
                        <th class="col-md-2">Market</th>
                        <th class="col-md-2">Trading Status</th>
                        <th class="col-md-2">Buy Fees</th>
                        <th class="col-md-2">Sell Fees</th>
                        <th class="col-md-2">Coin</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($pairs as $pair)
                    <tr>
                        <td onclick="window.location='/trade/index/market/{{ strtolower($pair->baseCurrency->symbol . '_' . $pair->quoteCurrency->symbol) }}'">

                            <div class="media-left media-middle">
                                <a href="{{ url('trade/'.$pair->id) }}">
                                    <img src="{{ $pair->baseCurrency->logo_url }}" class="img-circle img-xs" alt="">
                                </a>
                            </div>

                            <div class="media-left">
                                <div>
                                    <a href="{{ url('trade/'.$pair->id) }}" class="text-default text-semibold">
                                        {{ $pair->baseCurrency->name }} ({{ $pair->symbol }})
                                    </a>
                                </div>
                            </div>
                        </td>

                        <td>
                            @if($pair->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>

                        <td>{{ number_format($pair->trading_fee, 2) }}%</td>
                        <td>{{ number_format($pair->trading_fee, 2) }}%</td>

                        <td>
                            <strong>{{ $pair->baseCurrency->symbol }}</strong>
                        </td>

                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>

</section>

@endsection