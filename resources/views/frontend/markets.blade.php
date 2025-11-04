@extends('layouts.app')

{{-- @section('title', 'Dashboard') --}}

@section('content')

	<style>
		.graph-main-section {
			background-color: #131723;
			padding: 10px 0px;
			margin: 60px 0 20px;
		}

		.graph-item .graph-heading .rate {
			margin-top: -7px !important;
		}

		.read-more.ml-auto {
			position: absolute;
			right: 0;
			min-width: auto;
		}

		.container {
			width: 100%;
			max-width: 1260px;
			padding: 0 30px;
			margin: 0 auto;
		}

		#dark .home-markets .nav-pills .nav-link,
		#dark .swap-form-inner,
		#dark .swap-from label,
		#dark .announcements-block,
		#dark thead th,
		#dark .markets-pair-list .nav-link {
			color: rgba(197, 197, 197, 1);
			background: transparent;
			box-shadow: none;
			font-size: 18px;
		}

		#dark .table thead th {
			font-size: 14px;
			color: rgba(197, 197, 197, 1);
		}

		div#market-tab-box {
			border-bottom: 1px solid rgba(78, 78, 78, 1);
		}

		#dark,
		#dark .modal-content,
		#dark section.guide,
		#dark section.apps,
		#dark section.blog,
		#dark .swap-form-wrapper .swap-from,
		#dark .swap-form-inner,
		#dark .announcements-block,
		#dark .easytrade-form-wrapper .easytrade-form-inner {
			border: 0;
		}

		.announcements-block .icon {
			background-color: transparent;
			color: inherit;
		}

		i.ion.ion-md-megaphone:before {
			color: rgba(197, 197, 197, 1);
		}

		.read-more.ml-auto a {
			color: #ffbe40 !important;
		}

		.slick-dots li button:before {
			color: #ffbe40;
		}

		#dark .slip-limit-container .limit-input,
		#dark .slip-limit-container .limit-button,
		#dark .card-header,
		#dark .card,
		#dark .home-markets>.tab-content {
			background-color: transparent;
			color: #fff;
			border: 0;
			box-shadow: none;
		}

		#dark .home-markets .nav.nav-pills .nav-link.current {
			background-color: transparent;
			border-bottom: 2px solid #ffbe40;
			color: #fff !important;
			box-shadow: none;
		}

		#dark .coin-pnl-modal-summary ul li,
		#dark .table td,
		#dark .table th {
			border: 0;
		}

		#dark #daily-top-winners .coin-list .btn-2,
		.codono-distribution-table.table .btn-2,
		.p2p-list-table .btn-2 {
			background-color: transparent;
			border-color: #ffbe40;
			color: #fff !important;
			border: 1px solid rgba(78, 78, 78, 1);
			border-radius: 6px;
		}
	</style>

	<!--div class="container">
	  <div class="row">
		<div class="col-12 col-md-6 order-2 order-md-1">
	  <div class="page-title-content d-flex align-items-start mt-2">
		  </div>
	</div>

		<div class="col-12 col-md-6 order-1 order-md-2 float-right">
		  <ul class="text-right breadcrumbs list-unstyle">
			<li>
			  <a class="btn btn-warning btn-sm" href="/">Home</a>
			</li>
			<li>
			  <a href="/" class="btn btn-warning btn-sm">Content</a>
			</li>
			<li class="btn btn-warning btn-sm active">Market</li>
		  </ul>
		</div>
	  </div>
	</div-->
	<!-- Home Market Start -->
	<section class="home-market">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="home-markets">

						<div class="d-flex justify-content-between align-items-center" id="market-tab-box">
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

						</div>
						<div class="tab-content">
							<div class="tab-pane fade show active" style="min-height: 200px !important;"
								id="daily-top-winners" role="tabpanel">
								<div class="table-responsive">

									<table class="table coin-list">
										<thead class="price_today_ull">
											<tr>
												<th scope="col" data-sort="0" style="cursor: default;">PAIR</th>
												<th scope="col" class="click-sort" data-sort="1" data-flaglist="0"
													data-toggle="0">Price </th>

												<th scope="col" class="click-sort" data-sort="2" data-flaglist="0"
													data-toggle="0">Buy</th>
												<th scope="col" class="click-sort" data-sort="3" data-flaglist="0"
													data-toggle="0">Sell</th>
												<th scope="col" class="click-sort" data-sort="6" data-flaglist="0"
													data-toggle="0">24H Vol</th>
												<th scope="col" class="click-sort" data-sort="4" data-flaglist="0"
													data-toggle="0">24H Total</th>
												<th scope="col" class="click-sort" data-sort="7" data-flaglist="0"
													data-toggle="0">Change</th>
												<th scope="col">Action</th>
											</tr>
										</thead>
										<tbody id="market-table-body">

											{{-- Default load USDT --}}
											@foreach($markets['USDT'] as $pair)
												@include('frontend.partials.market-row', ['pair' => $pair])
											@endforeach

										</tbody>



									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</section>
	<!-- Home Market End -->

	<!-- info modal start -->
	<div class="modal fade infoModal left" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="Info Modal"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="infoPair"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
					<p>Symbol: <b id="infoSymbol"></b></p>
					<p>Name: <b id="infoName"></b></p>
					<p>Release Date: <b id="infoRelease"></b></p>
					<p>Block Reward: <b id="infoReward"></b></p>
					<p>Supply: <b id="infoSupply"></b></p>
					<p>Withdrawal: <b id="infoWithdrawal"></b></p>
					<p>Deposit: <b id="infoDeposit"></b></p>
					<p>Official Link: <a id="infoLink" href="#" target="_blank"></a></p>

					<h4>Description</h4>
					<div id="infoDescription"></div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn-1" data-dismiss="modal">Close</button>
					<a href="#" class="btn-2" id="tradelink">Trade</a>
				</div>
			</div>
		</div>
	</div>

	<script>
		$(document).on("click", ".infobutton", function () {
			let btn = $(this);

			$("#infoPair").text(btn.data("symbol") + " Info");
			$("#infoSymbol").text(btn.data("symbol"));
			$("#infoName").text(btn.data("name"));
			$("#infoRelease").text(btn.data("release") || "N/A");
			$("#infoReward").text(btn.data("reward") || "N/A");
			$("#infoSupply").text(btn.data("supply") || "N/A");
			$("#infoWithdrawal").text(btn.data("withdrawal") || "N/A");
			$("#infoDeposit").text(btn.data("deposit") || "N/A");

			let link = btn.data("link") || "#";
			$("#infoLink").text(link).attr("href", link);

			$("#infoDescription").text(btn.data("description") || "No description available.");
			$("#tradelink").attr("href", btn.data("trade-url"));

			$("#infoModal").modal("show");
		});
	</script>

	<!-- info modal end -->
	<script>
		const markets = @json($markets);

		function switchMarket(symbol) {
			let tableBody = document.getElementById("market-table-body");
			tableBody.innerHTML = "";

			markets[symbol].forEach(pair => {
				tableBody.innerHTML += `
			<tr>
				<td>
					<div class="d-flex align-items-center">
						<img src="${pair.base_currency.logo_url}" style="width:30px;height:30px;border-radius:50%;margin-right:10px;">
						<div>
							<strong>${pair.base_currency.symbol}/${pair.quote_currency.symbol}</strong>
							<div class="text-muted small">${pair.base_currency.name}</div>
						</div>
					</div>
				</td>

				<td>${Number(pair.base_currency.current_price).toFixed(6)}</td>
				<td>${Number(pair.base_currency.current_price).toFixed(6)}</td>
				<td>${Number(pair.base_currency.current_price).toFixed(6)}</td>

				<td>${Number(pair.base_currency.volume_24h).toLocaleString()}</td>
				<td>${Number(pair.base_currency.market_cap).toLocaleString()}</td>

				<td>
					<span style="color:${pair.base_currency.price_change_24h >= 0 ? 'green' : 'red'}">
						${Number(pair.base_currency.price_change_24h).toFixed(2)}%
					</span>
				</td>

				<td class="text-right">

		<button 
			type="button" 
			class="btn-2 yellow-bg infobutton"
			data-symbol="${pair.base_currency.symbol}"
			data-name="${pair.base_currency.name}"
			data-release="${pair.base_currency.release_date ?? '-'}"
			data-reward="${pair.base_currency.block_reward ?? '-'}"
			data-supply="${pair.base_currency.max_supply ?? '-'}"
			data-withdrawal="${pair.base_currency.withdrawal_fee ?? '-'}"
			data-deposit="${pair.base_currency.deposit_enabled ? 'Enabled' : 'Disabled'}"
			data-link="${pair.base_currency.official_website ?? '#'}"
			data-description="${pair.base_currency.description ?? 'No description available'}"
			data-trade-url="/trade/${pair.symbol.replace('/', '-')}"
		>
			Info
		</button>

		<a href="/trade/${pair.symbol.replace('/', '-')}" class="btn-2 yellow-bg">Trade</a>
	</td>
			</tr>`;
			});
		}
	</script>



	<!-- Footer -->


@endsection