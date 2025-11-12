@extends('layouts.app')

@section('title', 'Stock Market')

@section('content')


<section class="home-market">
		<div class="container">
            <h2 class="text-center mb-4 pt-4 text-white">Top Performing Stocks</h2>
			<div class="row">
				<div class="col-md-12">
					<div class="home-markets">

						{{-- <div class="d-flex justify-content-between align-items-center" id="market-tab-box">
							<ul class="nav nav-pills trade_qu_list" role="tablist">

								<li class=" nav-link trade_moshi trade_qu_pai current" data="0.html"
									onclick="switchMarket('USDT')">
									<a href="#highlighted-justified-tab1" class="active" data-toggle="tab">USDT</a>
								</li>
								<li class=" nav-link trade_moshi trade_qu_pai " data="1.html" onclick="switchMarket('BTC')">
									<a href="#highlighted-justified-tab1" class="" data-toggle="tab">BTC</a>
								</li>
								<li class=" nav-link trade_moshi trade_qu_pai " data="2.html" onclick="switchMarket('ETH')">
									<a href="#highlighted-justified-tab1" class="" data-toggle="tab">ETH</a>
								</li>
								<li class=" nav-link trade_moshi trade_qu_pai " data="3.html" onclick="switchMarket('EUR')">
									<a href="#highlighted-justified-tab1" class="" data-toggle="tab">EUR</a>
								</li>
							</ul>

						</div> --}}
						<div class="tab-content">
							<div class="tab-pane fade show active" style="min-height: 200px !important;"
								id="daily-top-winners" role="tabpanel">
                                @if(isset($stocks) && $stocks->count() > 0)
								<div class="table-responsive">
                                   
									<table class="table coin-list">
										<thead class="price_today_ull">
											<tr>
												<th scope="col" data-sort="0" style="cursor: default;">Symbol</th>
												<th scope="col" class="click-sort" data-sort="1" data-flaglist="0"
													data-toggle="0">Company</th>

												<th scope="col" class="click-sort" data-sort="2" data-flaglist="0"
													data-toggle="0">Price</th>
												<th scope="col" class="click-sort" data-sort="3" data-flaglist="0"
													data-toggle="0">Change</th>
												<th scope="col" class="click-sort" data-sort="6" data-flaglist="0"
													data-toggle="0">%</th>
												<th scope="col" class="click-sort" data-sort="4" data-flaglist="0"
													data-toggle="0">Volume</th>
												<th scope="col" class="click-sort" data-sort="7" data-flaglist="0"
													data-toggle="0">Market Cap</th>
												<th scope="col" class="click-sort" data-sort="7" data-flaglist="0"
													data-toggle="0">Sector</th>
                                                @auth <th scope="col">Action</th> @endauth
												
											</tr>
										</thead>
										<tbody id="market-table-body">

											{{-- Default load USDT --}}
											@foreach($stocks as $stock)
												@include('frontend.partials.stock-row')
											@endforeach

										</tbody>



									</table>
								</div>
                                @else
                                <div class="text-center text-muted py-5">
                                <i class="fas fa-chart-line fa-4x mb-3"></i>
                                <p>No stocks available at the moment. Please check back later.</p>
                                </div>
                                @endif
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</section>

@endsection