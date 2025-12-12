@extends('layouts.app')

{{-- @section('title', 'Dashboard') --}}

@section('content')
<!-- Home Slider Start -->
<section class="banner-section">
    <div class="container">
        <div class="banner-inner-section">
            <div class="banner-left-section">
                <div class="index_banner-title__Ueyv2" data-wow-delay="0.2s">
                    <h1 class="Main-heading" data-translate="welcome-title">The Gold Standard in Cryptocurrency Trading</h1>
                    <p>Seamlessly Buy, Sell, Trade, and Hold Cryptocurrencies on
                        {{ \App\Models\Setting::get('site_name', 'Website Name') }}.com
                    </p>
                </div>


                <div class="banner-email">
                    <div class="index_quick-reg-wrapper__AGLkO">
                        @auth
                        <div class="easytrade-form-wrapper">
                            <div class="easytrade-form-inner">
                                <h1 class="text-center m-b-30">Buy Crypto with USDT</h1>

                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <select id="easycoin" class="bootstrap-select" data-live-search="true" data-width="200px">
                                            @foreach($cryptos as $crypto)
                                                <option value="{{ $crypto->symbol }}"
                                                    data-value="{{ $crypto->symbol }}"
                                                    data-price="{{ $crypto->current_price }}"
                                                    data-content="<img src='{{ $crypto->logo_url ?? asset('default-coin.png') }}'> {{ $crypto->symbol }}">
                                                    {{ $crypto->symbol }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <input type="number" name="amount" id="easyamount" class="form-control" placeholder="Enter USDT amount" />

                                        <button class="btn btn-primary ml-2" onclick="easytrade();">Buy</button>
                                    </div>
                                </div>

                                <div id="tradeResult" class="mt-2"></div>
                            </div>
                        </div>
                        @else
                            <div class="col-md-8 col-sm-12">
                                <a href="{{ route('auth.google') }}" class="btn white-bg btn-block btn-lg common-text"
                                    data-onsuccess="onSignIn">

                                    <span><img src='{{ asset('Public/template/epsilon/img/redesign/google-icon.svg') }}' />
                                        Signin with
                                        Google</span></a>
                                <div class="hr-box">
                                    <div class="hr-line"></div>
                                    <div data-bn-type="text" class="hr-text">or continue with </div>
                                    <div class="hr-line"></div>
                                </div>

                                <a href="{{ url('register') }}" class="btn yellow-bg btn-block btn-lg">Sign up using
                                    Email</a>

                            </div>
                        @endauth

                    </div>
                </div>
            </div>


            <img src="{{ asset('uploads/Banner-iphone-dark-image.png') }}"
                class="index_banner-image Light__mode">
            <img src="{{ asset('uploads/Banner-iphone-dark-image.png') }}"
                class="index_banner-image Dark__mode">
        </div>
    </div>
</section>

  	<!-- Graph Start -->
	<div class="graph graph-main-section graph-padding d-none d-md-block hide-mobile">
		<div class="container-fluid">
			<div class="row margin-balance">
			<div class="col-lg-2 col-6">
					<a href="{{ url('trade/24') }}t" class="graph-item-block">
						<div class="graph-item">
							<div class="graph-heading">
								<div class="icon" style="background-image: url('/Upload/coin/Upload/coin/651d44221f386.png')"></div>
								<h5>SHIB / USDT</h5>
								<div class="rate red">-1.156%</div>
							</div>
							<div class="graph-content">
								<div class="left-side">
									<div class="lastprice">
										<span class="price-change">0</span>
									</div>
									<span class="volume">Vol 1053641103590</span>
								</div>
								<div class="graph-chart">
									<span class="updating-chart-one">9,6,8,3,5,8,9,5,5,4,2,2,3,1,5,8,5,2,6,8,7</span>
								</div>
							</div>
						</div>
					</a>
				</div><div class="col-lg-2 col-6">
					<a href="{{ url('trade/16') }}" class="graph-item-block">
						<div class="graph-item">
							<div class="graph-heading">
								<div class="icon" style="background-image: url('/Upload/coin/Upload/coin/651d435be096b.png')"></div>
								<h5>DOGE / USDT</h5>
								<div class="rate red">-2.155%</div>
							</div>
							<div class="graph-content">
								<div class="left-side">
									<div class="lastprice">
										<span class="price-change">0.142</span>
									</div>
									<span class="volume">Vol 899121606</span>
								</div>
								<div class="graph-chart">
									<span class="updating-chart-two">1,5,2,3,4,5,9,6,9,8,1,2,1,1,6,7,9,3,5,1,2</span>
								</div>
							</div>
						</div>
					</a>
				</div><div class="col-lg-2 col-6">
					<a href="{{ url('trade/25') }}" class="graph-item-block">
						<div class="graph-item">
							<div class="graph-heading">
								<div class="icon" style="background-image: url('/Upload/coin/Upload/coin/652eec5cc15f1.png')"></div>
								<h5>PEPE / USDT</h5>
								<div class="rate red">-2.1%</div>
							</div>
							<div class="graph-content">
								<div class="left-side">
									<div class="lastprice">
										<span class="price-change">0.007</span>
									</div>
									<span class="volume">Vol 485786677</span>
								</div>
								<div class="graph-chart">
									<span class="updating-chart-three">8,5,6,9,5,4,9,8,1,1,3,7,9,6,6,2,2,3,9,7,2</span>
								</div>
							</div>
						</div>
					</a>
				</div><div class="col-lg-2 col-6">
					<a href="{{ url('trade/14') }}" class="graph-item-block">
						<div class="graph-item">
							<div class="graph-heading">
								<div class="icon" style="background-image: url('/Upload/coin/Upload/coin/651d46df970c6.png')"></div>
								<h5>XRP / USDT</h5>
								<div class="rate red">-2.642%</div>
							</div>
							<div class="graph-content">
								<div class="left-side">
									<div class="lastprice">
										<span class="price-change">0.012</span>
									</div>
									<span class="volume">Vol 341372239</span>
								</div>
								<div class="graph-chart">
									<span class="updating-chart-four">8,2,1,5,1,6,8,3,5,7,6,6,8,5,2,5,2,6,7,7,7</span>
								</div>
							</div>
						</div>
					</a>
				</div><div class="col-lg-2 col-6">
					<a href="{{ url('trade/15') }}" class="graph-item-block">
						<div class="graph-item">
							<div class="graph-heading">
								<div class="icon" style="background-image: url('/Upload/coin/Upload/coin/651a9f81b173f.png')"></div>
								<h5>ADA / USDT</h5>
								<div class="rate red">-7.057%</div>
							</div>
							<div class="graph-content">
								<div class="left-side">
									<div class="lastprice">
										<span class="price-change">0.4267</span>
									</div>
									<span class="volume">Vol 251911103.2</span>
								</div>
								<div class="graph-chart">
									<span class="updating-chart-five">4,6,9,3,7,3,2,9,4,5,7,7,1,6,1,2,2,8,7,1,6</span>
								</div>
							</div>
						</div>
					</a>
				</div><div class="col-lg-2 col-6">
					<a href="{{ url('trade/17') }}" class="graph-item-block">
						<div class="graph-item">
							<div class="graph-heading">
								<div class="icon" style="background-image: url('/Upload/coin/Upload/coin/651a9c7bd8f28.png')"></div>
								<h5>TRX / USDT</h5>
								<div class="rate green">0.107%</div>
							</div>
							<div class="graph-content">
								<div class="left-side">
									<div class="lastprice">
										<span class="price-change">0.2809</span>
									</div>
									<span class="volume">Vol 201590604.8</span>
								</div>
								<div class="graph-chart">
									<span class="updating-chart-six">8,2,6,3,2,3,6,2,2,8,4,6,3,1,5,7,7,4,6,2,5</span>
								</div>
							</div>
						</div>
					</a>
				</div>			</div>
		</div>
	</div>
	<!-- Graph End -->
	
<style>
.graph-main-section {
    background-color: #131723;
    padding: 10px 0px;
    margin: 60px 0 20px;
}
.graph-item .graph-heading .rate{
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
#dark .home-markets .nav-pills .nav-link, #dark .swap-form-inner, #dark .swap-from label, #dark .announcements-block, #dark thead th, #dark .markets-pair-list .nav-link {
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
#dark, #dark .modal-content, #dark section.guide, #dark section.apps, #dark section.blog, #dark .swap-form-wrapper .swap-from, #dark .swap-form-inner, #dark .announcements-block, #dark .easytrade-form-wrapper .easytrade-form-inner {
    border: 0;
}
.announcements-block .icon {
    background-color: transparent;
    color: inherit;
}
i.ion.ion-md-megaphone:before{
    color: rgba(197, 197, 197, 1);
}
.read-more.ml-auto a {
    color: #007bff !important;
}
.slick-dots li button:before{
    color: #007bff;
}
#dark .slip-limit-container .limit-input, #dark .slip-limit-container .limit-button, #dark .card-header, #dark .card, #dark .home-markets > .tab-content {
    background-color: transparent;
    color: #fff;
    border: 0;
    box-shadow: none;
}
#dark .home-markets .nav.nav-pills .nav-link.current {
    background-color: transparent;
    border-bottom: 2px solid #007bff;
    color: #fff !important;
    box-shadow: none;
}
#dark .coin-pnl-modal-summary ul li, #dark .table td, #dark .table th{
    border: 0;
}
#dark #daily-top-winners .coin-list .btn-2, .codono-distribution-table.table .btn-2, .p2p-list-table .btn-2 {
    background-color: transparent;
    border-color: #007bff;
    color: #fff!important;
    border: 1px solid rgba(78, 78, 78, 1);
    border-radius: 6px;
}
</style>




<!-- Announcements Start -->
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="announcements-block d-flex align-items-center">
					<div class="icon">
						<i class="ion ion-md-megaphone"></i>
					</div>
					<div class="announcements-single-item">
					
						<div>
									<a href="{{ url('article/13') }}">Successfully Finalizes Extensive Database Enhancement for Enhanced Exchange Performance...</a>
								</div><div>
									<a href="{{ url('article/11') }}">Notice About Unauthorized Text Message Activity...</a>
								</div><div>
									<a href="{{ url('article/10') }}">We care about you, your funds are secure with us!...</a>
								</div>						
					</div>
					<!--div class="read-more ml-auto">
						<a href="/Index/Article">More <i class="ion ion-md-arrow-forward"></i></a>
					</div-->
				</div>
			</div>
		</div>
	</div>
	<!-- Announcements End -->
	<!-- Home Banners Slider Start -->
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="home-small-banners">
				
						<div>
						<a href="{{ url('article/16') }}"><img src="{{ asset('uploads/654b76b8d14ac.png') }}" alt="Banner8" class="img-fluid m-auto"></a>
					</div><div>
						<a href="{{ url('article/21') }}"><img src="{{ asset('uploads/654b76cc3a526.png') }}" alt="Banner7" class="img-fluid m-auto"></a>
					</div><div>
						<a href="{{ url('article/15') }}"><img src="{{ asset('uploads/654b76d71ad7b.png') }}" alt="Banner6" class="img-fluid m-auto"></a>
					</div><div>
						<a href="{{ url('article/18') }}"><img src="{{ asset('uploads/6549d31e130fe.png') }}" alt="Banner5" class="img-fluid m-auto"></a>
					</div><div>
						<a href="{{ url('article/19') }}"><img src="{{asset('uploads/6549d30c00338.png')}}" alt="Banner4" class="img-fluid m-auto"></a>
					</div><div>
						<a href="{{ url('article/20') }}"><img src="{{ asset('uploads/6549d300ab7ac.png') }}" alt="Banner3" class="img-fluid m-auto"></a>
					</div><div>
						<a href="{{ url('article/17') }}"><img src="{{ asset('uploads/6549d2f6e3bca.png') }}" alt="Banner2" class="img-fluid m-auto"></a>
					</div><div>
						<a href="{{ url('article/14') }}"><img src="{{ asset('uploads/6549d2e57b4aa.png') }}" alt="Banner1" class="img-fluid m-auto"></a>
					</div>				</div>
			</div>
		</div>
	</div>
	<!-- Home Banners Slider End -->


   
    <!--Home Market Start -->
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
                                                @include('frontend.partials.home-market', ['pair' => $pair])
                                            @endforeach

                                        </tbody>



                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12 text-center">
                    <a href="{{ url('markets') }}" class="btn yellow-bg btn-lg viewall_btn">View all</a>
                </div>
            </div>
        </div>
    </section>

    <section class="currency-main-section">
    <div class="container">
        <div class="currency-inner-section">
            <div class="left-section">
                <h2 class="Main_headings">Exchange crypto for USDT and national currencies</h2>
                <p>Here’s how easy it is:</p>
                <ul>
                    <li>Choose the currencies</li>
                    <li>Specify the amount</li>
                    <li>Click “Exchange”</li>
                </ul>
            </div>
            <div class="right-section">
                <form class="currency-selector-form">
                    <!-- Give Section -->
                    <div class="select_wrap top">
                        <div class="currency-input-filed">
                            <input class="form-input from_input" value="1" type="number" placeholder="0" style="color:white;" step="0.00000001">
                            <label class="form-label">Give</label>
                        </div>
                        <ul class="default_option-top default_option">
                            <li>
                                <div class="option pizza">
                                    <div class="icon">
                                        <img src="https://cryptologos.cc/logos/bitcoin-btc-logo.png" alt="BTC" width="24" height="24">
                                    </div>
                                    <p style="color:white;">BTC</p>
                                </div>
                            </li>
                        </ul>
                        
                        <ul class="select_ul from_ul">
                            <li data-currency="BTC">
                                <div class="option pizza">
                                    <div class="icon">
                                        <img src="https://cryptologos.cc/logos/bitcoin-btc-logo.png" alt="BTC" width="24" height="24">
                                    </div>
                                    <p style="color:white;">BTC</p>
                                </div>
                            </li>
                            <li data-currency="ETH">
                                <div class="option burger">
                                    <div class="icon">
                                        <img src="https://cryptologos.cc/logos/ethereum-eth-logo.png" alt="ETH" width="24" height="24">
                                    </div>
                                    <p style="color:white;">ETH</p>
                                </div>
                            </li>
                            <li data-currency="USDT">
                                <div class="option ice">
                                    <div class="icon">
                                        <img src="https://cryptologos.cc/logos/tether-usdt-logo.png" alt="USDT" width="24" height="24">
                                    </div>
                                    <p style="color:white;">USDT</p>
                                </div>
                            </li>
                            <li data-currency="SOL">
                                <div class="option fries">
                                    <div class="icon">
                                        <img src="https://cryptologos.cc/logos/solana-sol-logo.png" alt="SOL" width="24" height="24">
                                    </div>
                                    <p style="color:white;">SOL</p>
                                </div>
                            </li>
                            <li data-currency="LTC">
                                <div class="option fries">
                                    <div class="icon">
                                        <img src="https://cryptologos.cc/logos/litecoin-ltc-logo.png" alt="LTC" width="24" height="24">
                                    </div>
                                    <p style="color:white;">LTC</p>
                                </div>
                            </li>
                            <li data-currency="XRP">
                                <div class="option fries">
                                    <div class="icon">
                                        <img src="https://cryptologos.cc/logos/xrp-xrp-logo.png" alt="XRP" width="24" height="24">
                                    </div>
                                    <p style="color:white;">XRP</p>
                                </div>
                            </li>
                            <li data-currency="DOGE">
                                <div class="option fries">
                                    <div class="icon">
                                        <img src="https://cryptologos.cc/logos/dogecoin-doge-logo.png" alt="DOGE" width="24" height="24">
                                    </div>
                                    <p style="color:white;">DOGE</p>
                                </div>
                            </li>
                            <li data-currency="BNB">
                                <div class="option fries">
                                    <div class="icon">
                                        <img src="https://cryptologos.cc/logos/bnb-bnb-logo.png" alt="BNB" width="24" height="24">
                                    </div>
                                    <p style="color:white;">BNB</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Swap Button -->
                    {{-- <div class="swap-container">
                        <button type="button" class="swap-button" id="swap-currencies">↕</button>
                    </div> --}}
                    
                    <!-- Exchange Rate & Fee Display -->
                    <ul class="border-section">
                        <li id="exchange-rate">1 BTC = 27317.397 USDT</li>
                        <li id="fee-display">Fee: 0.1%</li>
                    </ul>
                    
                    <!-- Receive Section -->
                    <div class="select_wrap bottom">
                        <div class="currency-input-filed">
                            <input class="form-input to_input" readonly type="number" placeholder="0">
                            <label class="form-label">Receive</label>
                        </div>
                        
                        <ul class="default_option default_option-bottom">
                            <li>
                                <div class="option">
                                    <div class="icon">
                                        <img src="https://cryptologos.cc/logos/tether-usdt-logo.png" alt="USDT" width="24" height="24">
                                    </div>
                                    <p style="color:white;">USDT</p>
                                </div>
                            </li>
                        </ul>
                        
                        <ul class="select_ul to_ul">
                            <li data-currency="BTC">
                                <div class="option pizza">
                                    <div class="icon">
                                        <img src="https://cryptologos.cc/logos/bitcoin-btc-logo.png" alt="BTC" width="24" height="24">
                                    </div>
                                    <p style="color:white;">BTC</p>
                                </div>
                            </li>
                            <li data-currency="ETH">
                                <div class="option burger">
                                    <div class="icon">
                                        <img src="https://cryptologos.cc/logos/ethereum-eth-logo.png" alt="ETH" width="24" height="24">
                                    </div>
                                    <p style="color:white;">ETH</p>
                                </div>
                            </li>
                            <li data-currency="USDT">
                                <div class="option ice">
                                    <div class="icon">
                                        <img src="https://cryptologos.cc/logos/tether-usdt-logo.png" alt="USDT" width="24" height="24">
                                    </div>
                                    <p style="color:white;">USDT</p>
                                </div>
                            </li>
                            <li data-currency="SOL">
                                <div class="option fries">
                                    <div class="icon">
                                        <img src="https://cryptologos.cc/logos/solana-sol-logo.png" alt="SOL" width="24" height="24">
                                    </div>
                                    <p style="color:white;">SOL</p>
                                </div>
                            </li>
                            <li data-currency="LTC">
                                <div class="option fries">
                                    <div class="icon">
                                        <img src="https://cryptologos.cc/logos/litecoin-ltc-logo.png" alt="LTC" width="24" height="24">
                                    </div>
                                    <p style="color:white;">LTC</p>
                                </div>
                            </li>
                            <li data-currency="XRP">
                                <div class="option fries">
                                    <div class="icon">
                                        <img src="https://cryptologos.cc/logos/xrp-xrp-logo.png" alt="XRP" width="24" height="24">
                                    </div>
                                    <p style="color:white;">XRP</p>
                                </div>
                            </li>
                            <li data-currency="DOGE">
                                <div class="option fries">
                                    <div class="icon">
                                        <img src="https://cryptologos.cc/logos/dogecoin-doge-logo.png" alt="DOGE" width="24" height="24">
                                    </div>
                                    <p style="color:white;">DOGE</p>
                                </div>
                            </li>
                            <li data-currency="BNB">
                                <div class="option fries">
                                    <div class="icon">
                                        <img src="https://cryptologos.cc/logos/bnb-bnb-logo.png" alt="BNB" width="24" height="24">
                                    </div>
                                    <p style="color:white;">BNB</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Exchange Button -->
                    <a href="{{ url('/easy-trade') }}" class="site-btn">Exchange</a>
                    
                    <!-- Loading Indicator -->
                    <div id="loading" style="display: none; text-align: center; padding: 10px;">
                        <span style="color: #00ff9d;">Calculating...</span>
                    </div>
                </form>
            </div>


        </div>
        </div>
    </div>
</section>
<style>
.currency-main-section .Main_headings {
    margin-bottom: 35px;
}
.Main_headings {
    font-size: 40px;
    font-family: Segoe UI;
    font-weight: 700;
    line-height: 56px;
}
.currency-main-section .Main_headings {
    margin-bottom: 35px;
}

.currency-main-section p {
    margin-bottom: 13px;
}

.currency-main-section ul li,
.currency-main-section p {
    font-size: 18px;
    font-weight: 600;
    line-height: 45px;
}

.currency-main-section .currency-box {
    border-radius: 20px;
    background: #181819;
    box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.05);
    padding: 34px;
}

.currency-inner-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    width: 100%;
    grid-gap: 30px;
}

.currency-main-section {
    padding-top: 100px;
}

.currency-inner-section ul:before {
    content: '';
    background-color: rgba(78, 78, 78, 1);
    width: 1px;
    position: absolute;
    left: 0;
    height: 100px;
    top: 50%;
    transform: translate(0, -50%);
}

.currency-inner-section ul {
    position: relative;
    padding-left: 20px;
}

.currency-main-section ul li {
    position: relative;
    display: flex;
    align-items: center;
}

.currency-main-section ul li:before {
    content: '';
    border-radius: 9px;
    background: #4E4E4E;
    width: 9px;
    height: 9px;
    position: absolute;
    left: -24px;
    top: 50%;
    transform: translate(0, -50%);
    z-index: 11;
}

.currency-main-section ul li:after {
    content: '';
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background-color: #111111;
    position: absolute;
    top: 50%;
    left: -30px;
    transform: translate(0, -50%);
    z-index: 1;
}
.currency-main-section .select-menu>.select p {
    text-align: left;
    display: flex;
    flex-direction: column;
    font-size: 12px;
    font-family: Segoe UI;
    font-weight: 600;
    margin: 0;
    line-height: 22px;
    position: absolute;
    left: 13px;
}

.currency-main-section .select-menu>.select p b {
    color: white;
    font-size: 20px;
    font-weight: 600;
}

.currency-main-section .site-btn {
    margin-top: 30px;
}

.index_list-content-ul__3ZsrS:hover {
    background-color: #241f16 !important;
    max-width: 1500px;
    cursor: pointer;
    z-index: 111;
}

.index_list-content-li-trade__uACOd:hover {
    background: #007bff;
    border: none;
    color: rgba(0, 0, 0, .8);
}

ul.index_list-content-ul__3ZsrS,
ul.index_list-content-ul__3ZsrS li {
    cursor: pointer !IMPORTANT;
}
.home-slider-section .slick-slide span img {
    border-radius: 5px;
    width: 100%;
}
.currency-main-section form.currency-selector-form ul li:after {
    background: #181819;
}
form.currency-selector-form ul.border-section {
    margin: 22px 0;
}
.select_wrap {
    position: relative;
    user-select: none;
}
.currency-input-main {
    position: relative;
}
form.currency-selector-form input, .currency-input-main .currency-input-filed {
    background-color: transparent;
    border: 1px solid #C5C5C5;
    height: 54px;
    width: 100%;
    border-radius: 6px;
    color: #fff;
    padding: 10px;
    position: absolute;
    padding-right: 160px;
    top: 50%;
    transform: translate(0, -50%);
    font-size: 14px;
}
.currency-input-filed label, .currency-input-main .currency-input-filed .give-text {
    color: #ABACB2;
    font-size: 12px!important;
    font-weight: 600;
    display: flex;
    flex-direction: column;
    top: 4px;
    position: absolute;
    left: 13px;
    line-height: 19px;
    transition: 0.2s ease all;
    -moz-transition: 0.2s ease all;
    -webkit-transition: 0.2s ease all;
}
.currency-input-filed label b, .currency-input-main .currency-input-filed .give-text b {
    color: #6B6B6B;
    font-size: 20px!important;
}
.chosen-wrapper .chosen-container .chosen-results li:last-child {
    border-bottom: 0;
}
form.currency-selector-form {
    border-radius: 20px;
    background: #181819;
    box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.05);
    padding: 34px;
}
form.currency-selector-form input {
    background-color: transparent;
    border: 1px solid #C5C5C5;
    height: 54px;
    width: 100%;
    right: 0;
    border-radius: 4px;
    color: #fff;
    padding: 10px;
    padding-right: 150px;
}
.currency-input-main .currency-input-filed .give-text b {
    font-weight: 700;
}
.currency-input-main .default_option-top {
    position: relative;
    cursor: pointer;
    border-radius: 6px;
    background-color: #525252;
    padding: 0 !important;
    width: 140px;
    margin: 3px 3px 3px auto;
}
.currency-input-main .default_option-top li {
    padding: 12px 20px;
    max-height: 48px;
}
.currency-main-section ul li {
    position: relative;
    display: flex;
    align-items: center;
}
.currency-main-section ul li, .currency-main-section p {
    font-size: 18px;
    font-weight: 600;
    line-height: 45px;
}
.select_wrap .option {
    display: flex;
    align-items: center;
}
.select_wrap .option.pizza .icon {
    background-position: 0 0;
}
.select_wrap .option .icon {
    margin-right: 10px;
}
.select_wrap .option svg {
    width: 29px;
    height: 25px;
}
form.currency-selector-form ul.border-section {
    margin: 22px 0;
}
.currency-main-section ul li:before {
    content: '';
    border-radius: 9px;
    background: #4E4E4E;
    width: 9px;
    height: 9px;
    position: absolute;
    left: -24px;
    top: 50%;
    transform: translate(0, -50%);
    z-index: 11;
}
.select_wrap {
    position: relative;
    user-select: none;
}
.currency-input-filed label {
    top: -2px;
}
.select_wrap .default_option {
    position: relative;
    cursor: pointer;
    border-radius: 4px;
    background-color: #525252;
    padding: 0 !important;
    width: 140px;
    margin: 3px 3px 3px auto;
}
.select_wrap .default_option:before {
    content: "";
    position: absolute;
    top: 50%;
    right: 14px;
    width: 16px;
    height: 10px;
    background: transparent;
    left: auto;
    background-image: url(/Public/template/epsilon/img/redesign/arrow.png);
    background-size: contain;
    background-repeat: no-repeat;
    transform: translate(0, -50%);
}
.select_wrap .default_option li {
    padding: 12px 20px;
    max-height: 48px;
}
.site-btn {
    background: #007bff;
    border-radius: 6px;
    height: 56px;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 500;
    font-size: 20px;
    line-height: 20px;
    color: rgba(13, 13, 13, 1);
}
.select_wrap .select_ul {
    position: absolute;
    top: 52px;
    left: auto;
    width: 100%;
    background: #525252;
    border-radius: 5px;
    display: none;
    z-index: 99;
    padding: 10px 0;
    width: 142px;
    margin-left: auto;
    right: 0;
}


.chosen-wrapper .chosen-container.chosen-with-drop .chosen-drop ul:before {
    display: none;
}

.chosen-wrapper .chosen-container.chosen-with-drop .chosen-single span,
.chosen-wrapper .chosen-container.chosen-with-drop .chosen-single option {
    text-transform: uppercase;
    color: #CFCFCF;
    font-size: 16px;
    font-weight: 600;
}

form.currency-selector-form ul.border-section {
    margin: 22px 0;
}

.chosen-wrapper .chosen-container .chosen-results li:last-child {
    border-bottom: 0;
}

.chosen-wrapper {
    position: relative;
}

form.currency-selector-form input::-webkit-input-placeholder {
    opacity: 1;
    color: #6B6B6B;
    font-size: 20px!important;
    font-weight: bolder;
}

form.currency-selector-form input::-moz-placeholder {
    opacity: 1;
    color: #6B6B6B;
    font-size: 20px!important;
    font-weight: bolder;
}

form.currency-selector-form input:-ms-input-placeholder {
    opacity: 1;
    color: #6B6B6B;
    font-size: 20px!important;
    font-weight: bolder;
}

form.currency-selector-form input:-moz-placeholder {
    opacity: 1;
    color: #6B6B6B;
    font-size: 20px!important;
    font-weight: bolder;
}

form.currency-selector-form textarea:-moz-placeholder {
    opacity: 1;
    color: #6B6B6B;
    font-size: 20px!important;
    font-weight: bolder;
}

form.currency-selector-form textarea::placeholder {
    opacity: 1;
    color: #6B6B6B;
    font-size: 20px!important;
    font-weight: bolder;
}

.chosen-wrapper {
    position: absolute;
    top: 0;
    right: 0;
    width: 100%;
    z-index: 99;
}

.select_wrap {
    position: relative;
    user-select: none;
}

.select_wrap .default_option {
    position: relative;
    cursor: pointer;
    border-radius: 4px;
    background-color: #525252;
    padding: 0 !important;
    width: 140px;
    margin: 3px 3px 3px auto;
}

.select_wrap .default_option li {
    padding: 12px 20px;
    max-height: 48px;
}

.select_wrap .option svg {
    width: 29px;
    height: 25px;
}


.select_wrap .select_ul {
    position: absolute;
    top: 52px;
    left: auto;
    width: 100%;
    background: #525252;
    border-radius: 5px;
    display: none;
    z-index: 99;
    padding: 10px 0;
    width: 142px;
    margin-left: auto;
    right: 0;
}

.select_wrap .select_ul li {
    padding: 10px 20px;
    cursor: pointer;
    border-bottom: 1px solid #818080;
}

.select_wrap .select_ul li:last-child {
    border: 0;
}

.select_wrap .select_ul li:first-child:hover {
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
}

.select_wrap .select_ul li:last-child:hover {
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px;
}

.select_wrap .option {
    display: flex;
    align-items: center;
}

.select_wrap .option .icon {
    margin-right: 10px;
}

.select_wrap .option.pizza .icon {
    background-position: 0 0;
}

.select_wrap .option.burger .icon {
    background-position: 0 -35px;
}

.select_wrap .option.ice .icon {
    background-position: 0 -72px;
}

.select_wrap.active .select_ul {
    display: block;
}

.select_wrap.active .default_option:before {
    transform: rotate(180deg) translate(0, -50%);
    top: 30%;
}

.select_wrap .option p {
    margin-bottom: 0;
    text-transform: uppercase;
    font-size: 16px;
    line-height: 24px;
}

form.currency-selector-form input,
.currency-input-main .currency-input-filed {
    background-color: transparent;
    border: 1px solid #C5C5C5;
    height: 54px;
    width: 100%;
    border-radius: 6px;
    color: #fff;
    padding: 10px;
    position: absolute;
    padding-right: 160px;
    top: 50%;
    transform: translate(0, -50%);
    font-size: 14px;
}

.currency-input-filed label,
.currency-input-main .currency-input-filed .give-text {
    color: #ABACB2;
    font-size: 12px!important;
    font-weight: 600;
    display: flex;
    flex-direction: column;
    top: 4px;
    position: absolute;
    left: 13px;
    line-height: 19px;
    transition: 0.2s ease all;
    -moz-transition: 0.2s ease all;
    -webkit-transition: 0.2s ease all;
}

.currency-input-filed label b,
.currency-input-main .currency-input-filed .give-text b {
    color: #6B6B6B;
    font-size: 20px!important;
}

.currency-input-main li:before,
.currency-input-main li:after,
.currency-input-main .default_option-top:before {
    display: none;
}

.currency-input-main .default_option-top .dropdown-icon svg {
    width: 9px;
    height: 6px;
}

.dropdown-icon {
    margin-left: 24px;
}

.currency-input-main .default_option-top .dropdown-icon svg {
    width: 16px;
    height: 10px;
}

.currency-input-main .default_option-top li {
    padding: 12px 20px;
    max-height: 48px;
}

.currency-input-main .default_option-top {
    position: relative;
    cursor: pointer;
    border-radius: 6px;
    background-color: #525252;
    padding: 0 !important;
    width: 140px;
    margin: 3px 3px 3px auto;
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type=number] {
    --moz-appearance: textfield;
}

form.currency-selector-form ul.default_option li:before,
form.currency-selector-form ul.default_option li:after,
.select_wrap .select_ul li:before,
.select_wrap .select_ul li:after {
    display: none;
}

.currency-input-main {
    position: relative;
}

.currency-input-filed input:not(:placeholder-shown)~label b {
    font-size: 0 !important;
}

form.currency-selector-form {
    border-radius: 20px;
    background: #181819;
    box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.05);
    padding: 34px;
}

.currency-input-filed label {
    top: -2px;
}
</style>

<script>
$(document).ready(function(){

    function calculate() {
        let from = $('.default_option-top li p').text().toUpperCase();
        let to   = $('.default_option-bottom li p').text().toUpperCase();
        let amount = $(".from_input").val() || 0;

        if (from === to || amount == 0) return;

        $("#loading").show();

        $.get(`/Ajax/coin_rate?from=${from}&to=${to}`, function(data) {

            $("#loading").hide();

            if (!data.success) {
                $('.to_input').val(0);
                return;
            }

            let feeRate = 1 - (0.1 / 100);
            let finalAmount = amount * data.rate * feeRate;

            $(".to_input").val(finalAmount.toFixed(8));

            $("#exchange-rate").text(
                `1 ${from} = ${parseFloat(data.rate).toFixed(8)} ${to}`
            );

        });
    }

    // INPUT amount change
    $(".from_input").on("input", calculate);

    // CHANGE FROM coin
    $(".from_ul li").click(function() {
        let current = $(this).html();
        $(".default_option-top li").html(current);
        $(this).closest(".select_wrap").removeClass("active");
        calculate();
    });

    // CHANGE TO coin
    $(".to_ul li").click(function() {
        let current = $(this).html();
        $(".default_option-bottom li").html(current);
        $(this).closest(".select_wrap").removeClass("active");
        calculate();
    });

    // Dropdown open/close
    $(".default_option").click(function(e) {
        e.stopPropagation();
        $(this).parent().toggleClass("active");
    });

    $(".currency-main-section").click(function() {
        $('.select_wrap').removeClass('active');
    });

    // FIRST LOAD
    calculate();

});
</script>



    <section class="explore-our-products">
        <div class="container">
            <div class="explore-our-products-inner-section">
                <h2 class="Main_headings">Start trading in 3 easy steps</h2><br />
                <div class="explore-box-main-section">
                    <div class="explore-box">
                        <p class="explore-box-top">Set up your account in less than 2 minutes with our simplified KYC
                            process</p>
                        <div class="explore-box-bottom">
                            <div class="index_action-text">
                                <svg width="14" height="8" viewBox="0 0 14 8" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M13.3536 4.60355C13.5488 4.40829 13.5488 4.09171 13.3536 3.89645L10.1716 0.714465C9.97631 0.519203 9.65973 0.519203 9.46447 0.714465C9.2692 0.909727 9.2692 1.22631 9.46447 1.42157L12.2929 4.25L9.46447 7.07843C9.2692 7.27369 9.2692 7.59027 9.46447 7.78553C9.65973 7.9808 9.97631 7.9808 10.1716 7.78553L13.3536 4.60355ZM4.37114e-08 4.75L13 4.75L13 3.75L-4.37114e-08 3.75L4.37114e-08 4.75Z"
                                        fill="#1e90ff"></path>
                                </svg>
                                @auth
                                    <a href="{{ url('dashboard') }}">Dashboard</a>
                                @else
                                    <a href="{{ url('register') }}">Register</a>
                                @endauth

                            </div><img
                                src="{{ asset('Public/template/epsilon/img/redesign/homepage/spot_dark-image-3.png') }}">
                        </div>
                    </div>
                    <div class="explore-box">
                        <p class="explore-box-top">Add funds to your account using your preferred deposit method</p>
                        <div class="explore-box-bottom">
                            <div class="index_action-text">


                                <svg width="14" height="8" viewBox="0 0 14 8" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M13.3536 4.60355C13.5488 4.40829 13.5488 4.09171 13.3536 3.89645L10.1716 0.714465C9.97631 0.519203 9.65973 0.519203 9.46447 0.714465C9.2692 0.909727 9.2692 1.22631 9.46447 1.42157L12.2929 4.25L9.46447 7.07843C9.2692 7.27369 9.2692 7.59027 9.46447 7.78553C9.65973 7.9808 9.97631 7.9808 10.1716 7.78553L13.3536 4.60355ZM4.37114e-08 4.75L13 4.75L13 3.75L-4.37114e-08 3.75L4.37114e-08 4.75Z"
                                        fill="#1e90ff"></path>
                                </svg>
                                <a href="{{ url('/login') }}">Deposit</a>
                            </div><img
                                src="{{ asset('Public/template/epsilon/img/redesign/homepage/spot_dark-image-2.png') }}">
                        </div>
                    </div>
                    <div class="explore-box">
                        <p class="explore-box-top">Buy & sell
                            100+ cryptos in second</p>
                        <div class="explore-box-bottom">
                            <div class="index_action-text">

                                <svg width="14" height="8" viewBox="0 0 14 8" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M13.3536 4.60355C13.5488 4.40829 13.5488 4.09171 13.3536 3.89645L10.1716 0.714465C9.97631 0.519203 9.65973 0.519203 9.46447 0.714465C9.2692 0.909727 9.2692 1.22631 9.46447 1.42157L12.2929 4.25L9.46447 7.07843C9.2692 7.27369 9.2692 7.59027 9.46447 7.78553C9.65973 7.9808 9.97631 7.9808 10.1716 7.78553L13.3536 4.60355ZM4.37114e-08 4.75L13 4.75L13 3.75L-4.37114e-08 3.75L4.37114e-08 4.75Z"
                                        fill="#1e90ff"></path>
                                </svg>
                                <a href="{{ url('trade/spot') }}">Trade</a>
                            </div><img
                                src="{{ asset('Public/template/epsilon/img/redesign/homepage/spot_dark-image-1.png') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="Crypto-Lending-section">
    <div class="container">
        <div class="Crypto-Lending-inner-section">
            <div class="top-section">
                <h2 class="Main_headings">Crypto Lending</h2>
                <p>Take your investment to the next level and enjoy passive income
                </p>
            </div>
            <div class="pick-plans-section">
                <div class="plans">
                    <div class="plans-svg">
                        <svg class="Light__mode" width="60" height="61" viewBox="0 0 60 61" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect y="0.290039" width="60" height="60" rx="10" fill="#181819"></rect>
                            <rect x="17" y="20.29" width="27.5" height="20" stroke="white" stroke-linejoin="round">
                            </rect>
                            <rect x="33" y="33.29" width="3" height="3" fill="white"></rect>
                            <rect x="37.5" y="33.29" width="3" height="3" fill="white"></rect>
                        </svg>

                        <svg class="Dark__mode" width="60" height="61" viewBox="0 0 60 61" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect y="0.290039" width="60" height="60" rx="10" fill="#F6F6F6" />
                            <rect x="17" y="20.29" width="27.5" height="20" stroke="black" stroke-linejoin="round" />
                            <rect x="33" y="33.29" width="3" height="3" fill="black" />
                            <rect x="37.5" y="33.29" width="3" height="3" fill="black" />
                        </svg>
                    </div>
                    <p>Deposit funds to the main balance</p>
                </div>

                <div class="plans">
                    <div class="plans-svg">
                        <svg class="Light__mode" width="60" height="61" viewBox="0 0 60 61" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect y="0.290039" width="60" height="60" rx="10" fill="#181819"></rect>
                            <path d="M15 25.04H46.5" stroke="white" stroke-linecap="round"></path>
                            <path d="M15 34.04H46.5" stroke="white" stroke-linecap="round"></path>
                            <circle cx="33.75" cy="25.04" r="3.25" fill="white" stroke="white"></circle>
                            <circle cx="26.25" cy="34.04" r="3.25" fill="white" stroke="white"></circle>
                        </svg>
                        <svg class="Dark__mode" width="60" height="61" viewBox="0 0 60 61" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect y="0.290039" width="60" height="60" rx="10" fill="#F6F6F6" />
                            <rect x="17" y="20.29" width="27.5" height="20" stroke="black" stroke-linejoin="round" />
                            <rect x="33" y="33.29" width="3" height="3" fill="black" />
                            <rect x="37.5" y="33.29" width="3" height="3" fill="black" />
                        </svg>
                    </div>
                    <p>Pick one of the plans</p>
                </div>
                <div class="plans">
                    <div class="plans-svg">
                        <svg class="Light__mode" width="60" height="61" viewBox="0 0 60 61" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect y="0.290039" width="60" height="60" rx="10" fill="#181819"></rect>
                            <path d="M18.75 18.29L18.75 42.29" stroke="white" stroke-linecap="round"></path>
                            <path
                                d="M42 42.79C42.2761 42.79 42.5 42.5662 42.5 42.29C42.5 42.0139 42.2761 41.79 42 41.79L42 42.79ZM42 41.79L18 41.79L18 42.79L42 42.79L42 41.79Z"
                                fill="white"></path>
                            <path d="M22.5 37.04C23 32.29 27.78 22.25 28.5 27.29C29.25 32.54 32.25 34.79 38.25 25.79"
                                stroke="white"></path>
                        </svg>
                        <svg class="Dark__mode" width="60" height="61" viewBox="0 0 60 61" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect y="0.290039" width="60" height="60" rx="10" fill="#F6F6F6" />
                            <path d="M15 25.04H46.5" stroke="black" stroke-linecap="round" />
                            <path d="M15 34.04H46.5" stroke="black" stroke-linecap="round" />
                            <circle cx="33.75" cy="25.04" r="3.25" fill="#F6F6F6" stroke="black" />
                            <circle cx="26.25" cy="34.04" r="3.25" fill="#F6F6F6" stroke="black" />
                        </svg>
                    </div>
                    <p>Make upto 24.85% APR</p>
                </div>
            </div>
            <div class="earning-section">
                <div class="earning">
                    <div class="earning-top-section">
                        <div class="earning-left-section">
                            <svg width="40" height="41" viewBox="0 0 40 41" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M39.9998 19.1945V21.0301C39.9525 21.0862 39.9685 21.1546 39.9681 21.2174C39.9627 22.1238 39.829 23.0163 39.6732 23.9055C39.5342 24.6991 39.337 25.4798 39.0928 26.2472C38.9331 26.7491 38.7601 27.247 38.5648 27.7366C36.3884 33.1953 31.8968 37.3736 26.402 39.2311C25.7487 39.452 25.0849 39.6334 24.4113 39.7874C23.9187 39.9 23.424 40.005 22.9267 40.0779C22.2975 40.1701 21.6612 40.2208 21.0245 40.2604C19.6556 40.3459 18.2992 40.2413 16.946 40.0474C16.4469 39.9758 15.9506 39.8834 15.4615 39.7669C14.9177 39.6375 14.3794 39.4807 13.8444 39.315C13.4827 39.203 13.1324 39.062 12.7796 38.9274C11.9768 38.6211 11.1982 38.261 10.4449 37.8484C7.40739 36.1849 4.96728 33.8841 3.10773 30.965C2.31919 29.7273 1.68957 28.4102 1.18075 27.0369C0.985665 26.5101 0.82909 25.9678 0.68268 25.4239C0.479777 24.6697 0.329458 23.9045 0.205332 23.1355C0.120496 22.6103 0.0843329 22.0759 0.0528615 21.544C0.0206083 20.9989 -0.0192685 20.4527 0.0104436 19.9054C0.0403512 19.3532 0.0624398 18.8008 0.117368 18.25C0.161741 17.8037 0.223315 17.3589 0.290949 16.9169C0.336299 16.6208 0.404324 16.3233 0.47235 16.0281C0.605272 15.4519 0.752269 14.8788 0.9237 14.3139C1.02026 13.9956 1.12699 13.6776 1.24877 13.3657C1.43076 12.8989 1.61412 12.4337 1.82269 11.9778C2.96191 9.48855 4.54369 7.32277 6.57193 5.48479C8.48797 3.74845 10.6605 2.43139 13.0853 1.53282C13.8051 1.2662 14.5387 1.04689 15.2856 0.863346C16.0216 0.682538 16.764 0.549229 17.513 0.447586C18.0289 0.377609 18.5471 0.326592 19.0684 0.319946C19.12 0.319359 19.1732 0.321314 19.2191 0.290039C19.7139 0.291016 20.2086 0.291016 20.7034 0.291016C20.7739 0.301181 20.8443 0.318577 20.9151 0.320141C21.6739 0.338906 22.4233 0.43449 23.1718 0.558221C23.6357 0.63504 24.0984 0.71479 24.5564 0.820343C25.0667 0.938014 25.5715 1.07758 26.0711 1.23493C26.595 1.3999 27.1108 1.58853 27.6187 1.79788C30.7421 3.08601 33.3738 5.04029 35.5066 7.65878C36.7489 9.18401 37.755 10.8515 38.4882 12.6835C38.7073 13.2308 38.9098 13.7826 39.0971 14.3401C39.3782 15.1767 39.566 16.0383 39.7185 16.9064C39.8456 17.6302 39.9425 18.3587 39.9623 19.0952C39.9633 19.1314 39.9509 19.1755 40 19.1947L39.9998 19.1945ZM26.0482 20.6646C26.2879 20.5843 26.4982 20.526 26.6989 20.4445C27.3784 20.1689 27.9091 19.7162 28.2823 19.0825C28.3251 19.0096 28.3394 18.9175 28.3849 18.8552C28.5456 18.6345 28.5872 18.3718 28.6736 18.1249C28.7422 17.929 28.7667 17.7234 28.8056 17.5205C28.9491 16.7709 28.8824 16.0469 28.572 15.3493C28.354 14.8594 28.0247 14.4497 27.6255 14.0967C26.757 13.3285 25.7276 12.8533 24.6449 12.4796C24.4447 12.4104 24.4285 12.4018 24.4795 12.1975C24.6494 11.5161 24.8197 10.8347 24.9893 10.1533C25.0916 9.74325 25.1932 9.33296 25.2952 8.92287C25.313 8.85152 25.3371 8.79015 25.2249 8.76376C24.5681 8.60914 23.9117 8.45336 23.259 8.28271C23.1464 8.2532 23.1208 8.28154 23.1009 8.37048C23.0952 8.39589 23.0897 8.4213 23.0835 8.44651C22.8239 9.48758 22.5641 10.5284 22.3043 11.5693C22.2568 11.7597 22.2546 11.7601 22.0533 11.7073C21.892 11.6651 21.7323 11.6125 21.5685 11.5857C21.2644 11.5359 20.9704 11.4454 20.6711 11.3768C20.5106 11.3398 20.4817 11.2853 20.5196 11.1371C20.563 10.9679 20.6164 10.8009 20.6588 10.6313C20.7815 10.1395 20.8939 9.64493 21.0239 9.15508C21.133 8.74382 21.2042 8.32239 21.3523 7.92149C21.3766 7.85581 21.3815 7.81007 21.281 7.78564C20.6414 7.63063 20.0045 7.46468 19.3667 7.30322C19.1898 7.25846 19.1884 7.25944 19.1446 7.43419C19.0123 7.9641 18.881 8.49421 18.7488 9.02432C18.6071 9.59215 18.4624 10.1592 18.3248 10.728C18.301 10.8269 18.2605 10.8576 18.1628 10.8343C17.9501 10.7835 17.7351 10.7429 17.5228 10.6905C17.254 10.624 16.9876 10.5478 16.719 10.4798C15.9629 10.2886 15.2062 10.0992 14.4497 9.90959C14.2795 9.86698 14.1092 9.82417 13.9384 9.78469C13.8866 9.77276 13.8436 9.76983 13.8275 9.8488C13.6817 10.5689 13.4902 11.2781 13.3015 11.9874C13.2706 12.1031 13.3154 12.1248 13.3946 12.144C13.5314 12.1772 13.6706 12.2013 13.8074 12.2341C14.0799 12.2996 14.3524 12.3651 14.6233 12.4366C14.8792 12.5042 15.139 12.5617 15.3687 12.7048C15.7825 12.9626 15.9815 13.4313 15.866 13.8985C15.5336 15.2418 15.2006 16.5848 14.8657 17.9275C14.5195 19.3151 14.1708 20.7019 13.8236 22.0892C13.6999 22.5839 13.5797 23.0796 13.4532 23.5736C13.3793 23.8625 13.2093 24.0779 12.9217 24.1742C12.7335 24.2374 12.5329 24.2186 12.3447 24.1735C11.8967 24.0661 11.4527 23.9416 11.007 23.8249C10.8356 23.78 10.8348 23.7808 10.7633 23.9428C10.562 24.3982 10.3532 24.8507 10.1632 25.3111C10.0477 25.5908 9.91865 25.8642 9.80058 26.1424C9.73568 26.2955 9.75015 26.3009 9.89851 26.3385C10.7961 26.5652 11.6957 26.7839 12.5943 27.007C13.1115 27.1354 13.6301 27.2581 14.138 27.4167C14.1558 27.4575 14.1428 27.4958 14.1337 27.5326C14.0776 27.7599 14.0195 27.9866 13.9628 28.2138C13.741 29.1039 13.5185 29.9939 13.2982 30.8844C13.2556 31.0567 13.2652 31.0641 13.4364 31.1059C14.023 31.2496 14.6094 31.3938 15.1959 31.5387C15.448 31.601 15.4472 31.6016 15.5104 31.3479C15.7725 30.2941 16.0348 29.2404 16.2978 28.1868C16.3427 28.0066 16.375 27.9855 16.5534 28.0488C16.693 28.0982 16.8369 28.1282 16.98 28.162C17.272 28.2308 17.5539 28.3365 17.8533 28.3797C18.0641 28.4102 18.0895 28.4722 18.0355 28.6882C17.771 29.7453 17.5066 30.8023 17.2439 31.86C17.2005 32.035 17.204 32.0367 17.3774 32.0801C17.9511 32.2236 18.5252 32.3661 19.0987 32.5098C19.1853 32.5315 19.2813 32.5956 19.3536 32.5745C19.4656 32.5418 19.4111 32.4046 19.4361 32.3157C19.5112 32.049 19.5866 31.7822 19.6562 31.5142C19.8589 30.7339 20.0493 29.9503 20.2448 29.168C20.2824 29.0173 20.3757 28.9563 20.5245 28.9851C20.747 29.0277 20.9702 29.0816 21.1928 29.1055C21.7226 29.1622 22.2513 29.2339 22.7856 29.2499C23.6269 29.2752 24.4545 29.2191 25.2614 28.9501C25.9624 28.7165 26.5504 28.3238 27.042 27.7763C27.3788 27.401 27.6128 26.9628 27.8276 26.5134C27.9381 26.2824 28.0075 26.0343 28.0925 25.7927C28.3108 25.1733 28.425 24.54 28.3998 23.8834C28.3736 23.2008 28.1599 22.5827 27.749 22.0352C27.3673 21.5266 26.8755 21.1468 26.3311 20.829C26.2478 20.7803 26.1643 20.7322 26.0478 20.6646H26.0482Z"
                                    fill="#F7931A"></path>
                                <path
                                    d="M26.0476 20.6649C26.1639 20.7325 26.2476 20.7806 26.3308 20.8292C26.8752 21.1471 27.3671 21.5269 27.7488 22.0355C28.1597 22.583 28.3734 23.201 28.3996 23.8836C28.4248 24.5402 28.3106 25.1735 28.0923 25.7929C28.0072 26.0345 27.9378 26.2826 27.8274 26.5136C27.6126 26.963 27.3786 27.4013 27.0418 27.7766C26.5504 28.3241 25.9622 28.7168 25.2612 28.9503C24.4543 29.2193 23.6267 29.2754 22.7853 29.2502C22.2511 29.2342 21.7223 29.1626 21.1926 29.1057C20.97 29.0819 20.7467 29.0279 20.5243 28.9853C20.3757 28.9569 20.2825 29.0179 20.2446 29.1683C20.0491 29.9505 19.8587 30.7342 19.656 31.5145C19.5864 31.7825 19.5109 32.0493 19.4359 32.3159C19.4109 32.4048 19.4654 32.5421 19.3534 32.5747C19.2811 32.5958 19.1849 32.5317 19.0985 32.51C18.5248 32.3661 17.9509 32.2238 17.3771 32.0804C17.2036 32.037 17.2 32.0352 17.2436 31.8603C17.5064 30.8028 17.7708 29.7457 18.0353 28.6884C18.0893 28.4726 18.0638 28.4105 17.8531 28.38C17.5537 28.3368 17.2716 28.231 16.9797 28.1622C16.8367 28.1284 16.6928 28.0987 16.5532 28.049C16.3748 27.9857 16.3425 28.0068 16.2975 28.187C16.0346 29.2406 15.7723 30.2944 15.5102 31.3481C15.447 31.6019 15.448 31.6013 15.1957 31.5389C14.6092 31.3941 14.0228 31.2496 13.4362 31.1062C13.265 31.0643 13.2554 31.0567 13.298 30.8847C13.5183 29.9941 13.7407 29.1042 13.9626 28.214C14.0193 27.9869 14.0773 27.7601 14.1334 27.5328C14.1424 27.4961 14.1553 27.4579 14.1377 27.4169C13.6299 27.2584 13.1113 27.1356 12.5941 27.0072C11.6955 26.7842 10.7959 26.5654 9.8983 26.3387C9.74993 26.3012 9.73547 26.2957 9.80036 26.1426C9.91863 25.8643 10.0476 25.591 10.163 25.3113C10.353 24.851 10.5617 24.3987 10.7631 23.943C10.8346 23.781 10.8356 23.7804 11.0068 23.8252C11.4525 23.9419 11.8964 24.0664 12.3445 24.1737C12.5327 24.2188 12.7335 24.2374 12.9215 24.1745C13.2092 24.0781 13.3791 23.8627 13.453 23.5738C13.5795 23.0799 13.6997 22.5842 13.8234 22.0894C14.1706 20.7022 14.5195 19.3153 14.8655 17.9277C15.2003 16.5851 15.5334 15.242 15.8657 13.8987C15.9813 13.4316 15.7823 12.963 15.3685 12.705C15.1388 12.5619 14.879 12.5043 14.6231 12.4368C14.3522 12.3653 14.0797 12.2998 13.8072 12.2343C13.6702 12.2015 13.5312 12.1775 13.3944 12.1442C13.3152 12.1251 13.2706 12.1034 13.3013 11.9877C13.4899 11.2781 13.6815 10.569 13.8273 9.84904C13.8434 9.76988 13.8864 9.77281 13.9382 9.78493C14.1092 9.82442 14.2793 9.86722 14.4495 9.90983C15.206 10.0994 15.9627 10.2888 16.7188 10.48C16.9874 10.5478 17.2536 10.6243 17.5226 10.6907C17.7349 10.7431 17.9499 10.7838 18.1626 10.8346C18.2603 10.8579 18.3008 10.8272 18.3246 10.7283C18.4622 10.1594 18.6069 9.59239 18.7486 9.02456C18.8809 8.49465 19.0121 7.96434 19.1444 7.43443C19.1882 7.25949 19.1894 7.25851 19.3665 7.30347C20.0045 7.46492 20.6414 7.63088 21.2808 7.78588C21.3814 7.81032 21.3764 7.85606 21.3521 7.92173C21.2038 8.32264 21.1328 8.74406 21.0237 9.15533C20.8937 9.64517 20.7813 10.1397 20.6586 10.6315C20.6162 10.8012 20.563 10.9679 20.5194 11.1374C20.4815 11.2855 20.5104 11.3401 20.6709 11.377C20.9702 11.4458 21.2642 11.5361 21.5683 11.586C21.7321 11.6129 21.8918 11.6653 22.0531 11.7076C22.2544 11.7603 22.2566 11.7599 22.3041 11.5696C22.5641 10.5287 22.8236 9.48762 23.0832 8.44676C23.0895 8.42154 23.095 8.39613 23.1006 8.37072C23.1206 8.28178 23.1462 8.25344 23.2588 8.28296C23.9115 8.4534 24.5681 8.60939 25.2247 8.764C25.3371 8.79039 25.3128 8.85177 25.295 8.92311C25.193 9.3332 25.0912 9.74349 24.9891 10.1536C24.8192 10.835 24.6492 11.5164 24.4793 12.1978C24.4283 12.402 24.4445 12.4106 24.6447 12.4798C25.7274 12.8538 26.7568 13.329 27.6253 14.0969C28.0242 14.45 28.3536 14.8597 28.5718 15.3495C28.8822 16.0473 28.9488 16.7713 28.8054 17.5208C28.7665 17.7238 28.742 17.9293 28.6734 18.1251C28.5868 18.3718 28.5454 18.6347 28.3847 18.8554C28.3392 18.9178 28.3249 19.0098 28.2821 19.0827C27.9089 19.7164 27.3782 20.1692 26.6987 20.4448C26.4978 20.5263 26.2876 20.5847 26.048 20.6649H26.0476ZM24.5706 16.7342C24.5583 16.6705 24.5464 16.6066 24.5337 16.543C24.4664 16.2057 24.322 15.9076 24.0913 15.6495C23.7867 15.309 23.4161 15.0614 23.0103 14.862C22.4352 14.5796 21.8265 14.3913 21.2082 14.2336C20.8599 14.1446 20.5069 14.0743 20.1576 13.9883C19.8403 13.9101 19.9064 13.9414 19.8432 14.1837C19.6599 14.8882 19.4853 15.595 19.31 16.3014C19.1579 16.9136 19.0074 17.5262 18.8606 18.1398C18.8176 18.3196 18.8299 18.3302 19.0068 18.3734C19.3696 18.4617 19.735 18.5401 20.094 18.6412C20.4148 18.7315 20.7418 18.7958 21.0691 18.8337C21.5585 18.8904 22.0523 18.9334 22.5478 18.8841C22.9194 18.8472 23.2779 18.7659 23.6057 18.5837C24.0141 18.3568 24.2942 18.0276 24.4304 17.5698C24.486 17.3835 24.5229 17.1949 24.569 17.0077C24.7282 16.9062 24.7285 16.8374 24.5704 16.7342H24.5706ZM21.0935 26.0752C21.4823 26.082 21.8891 26.0551 22.2859 25.9452C23.2979 25.6649 23.7647 25.051 23.8718 24.0918C23.9294 23.5765 23.7697 23.118 23.4576 22.7108C23.2209 22.402 22.9198 22.1653 22.5936 21.9581C22.1252 21.6606 21.6148 21.4542 21.0966 21.2644C20.6599 21.1043 20.2082 20.9954 19.7604 20.8742C19.4763 20.7974 19.1855 20.746 18.8993 20.6768C18.7189 20.6332 18.5371 20.5964 18.3565 20.5552C18.2781 20.5372 18.2566 20.548 18.239 20.622C18.1716 20.9066 18.0982 21.1901 18.0275 21.4739C17.7192 22.7104 17.411 23.9468 17.1031 25.1835C17.0585 25.3625 17.0603 25.3619 17.2319 25.4038C17.4252 25.4509 17.6197 25.4949 17.8109 25.5498C18.1213 25.6391 18.4362 25.7118 18.7496 25.7867C19.0782 25.8653 19.4132 25.9239 19.7508 25.9663C20.1902 26.0215 20.6283 26.0789 21.0931 26.0754L21.0935 26.0752ZM14.9898 17.9187C14.9843 17.9183 14.9781 17.9166 14.9734 17.9183C14.9712 17.9191 14.9722 17.9275 14.9716 17.9324C14.9771 17.9328 14.9834 17.9346 14.9879 17.9328C14.99 17.932 14.989 17.9238 14.9896 17.9189L14.9898 17.9187Z"
                                    fill="white"></path>
                                <path
                                    d="M21.0927 26.0752C20.6279 26.0787 20.1896 26.0212 19.7504 25.9661C19.4128 25.9237 19.0777 25.865 18.7492 25.7865C18.4358 25.7116 18.1209 25.6389 17.8105 25.5496C17.6193 25.4946 17.4248 25.4506 17.2315 25.4035C17.0599 25.3617 17.0581 25.3623 17.1027 25.1832C17.4104 23.9467 17.7188 22.7102 18.0271 21.4737C18.0978 21.1898 18.1711 20.9064 18.2386 20.6218C18.2562 20.5477 18.2777 20.537 18.3561 20.555C18.5367 20.5962 18.7185 20.633 18.8989 20.6765C19.1851 20.7457 19.4759 20.7971 19.76 20.874C20.2078 20.995 20.6595 21.104 21.0962 21.2641C21.6144 21.4539 22.1248 21.6603 22.5932 21.9578C22.9194 22.165 23.2204 22.4019 23.4572 22.7106C23.7693 23.1179 23.9288 23.5763 23.8714 24.0916C23.7642 25.0507 23.2975 25.6647 22.2855 25.945C21.8887 26.0548 21.4817 26.0818 21.0931 26.075L21.0927 26.0752Z"
                                    fill="#F7931A"></path>
                                <path
                                    d="M24.5691 17.0077C24.523 17.195 24.486 17.3836 24.4305 17.5699C24.2941 18.0277 24.0141 18.3568 23.6058 18.5838C23.278 18.7659 22.9195 18.8473 22.5479 18.8842C22.0522 18.9337 21.5586 18.8907 21.0691 18.8338C20.7419 18.7959 20.4149 18.7315 20.0941 18.6412C19.735 18.5402 19.3695 18.4616 19.0069 18.3734C18.83 18.3304 18.8177 18.3199 18.8607 18.1399C19.0075 17.5263 19.158 16.9137 19.3101 16.3015C19.4854 15.5951 19.66 14.8883 19.8433 14.1838C19.9064 13.9414 19.8404 13.9101 20.1576 13.9883C20.5068 14.0743 20.8598 14.1447 21.2083 14.2336C21.8266 14.3914 22.4353 14.5796 23.0104 14.8621C23.4162 15.0614 23.787 15.3091 24.0914 15.6496C24.322 15.9076 24.4665 16.2055 24.5337 16.5431C24.5464 16.6068 24.5583 16.6705 24.5707 16.7343C24.5703 16.8253 24.5697 16.9164 24.5693 17.0077H24.5691Z"
                                    fill="#F7931A"></path>
                                <path
                                    d="M24.5703 17.0076C24.5707 16.9165 24.5713 16.8254 24.5717 16.7341C24.7298 16.8373 24.7296 16.9059 24.5703 17.0076Z"
                                    fill="white"></path>
                                <path
                                    d="M14.9918 17.9189C14.9914 17.9241 14.9923 17.9329 14.99 17.9337C14.985 17.9356 14.9785 17.9337 14.9727 17.9333C14.9731 17.9281 14.9722 17.9193 14.9745 17.9183C14.9795 17.9164 14.986 17.9183 14.992 17.9187L14.9918 17.9189Z"
                                    fill="white"></path>
                            </svg>
                            <p>Bitcoin</p>
                        </div>
                        <div class="earning-right-section">
                            <p class="btc_percent_text">23.19 %</p>
                        </div>
                    </div>
                    <div class="radio-button-form">
                        <label>
                            <input type="radio" name="btc_radio" value="0.23" checked="">
                            <div class="days">
                                <span>10</span>
                                <p>Days</p>
                            </div>
                        </label>
                        <label>
                            <input type="radio" name="btc_radio" value="0.58" checked="">
                            <div class="days">
                                <span>20</span>
                                <p>Days</p>
                            </div>
                        </label><label>
                            <input type="radio" name="btc_radio" value="1.01" checked="">
                            <div class="days">
                                <span>30</span>
                                <p>Days</p>
                            </div>
                        </label><label>
                            <input type="radio" name="btc_radio" value="3.47" checked="">
                            <div class="days">
                                <span>90</span>
                                <p>Days</p>
                            </div>
                        </label><label>
                            <input type="radio" name="btc_radio" value="7.53" checked="">
                            <div class="days">
                                <span>380</span>
                                <p>Days</p>
                            </div>
                        </label><label>
                            <input type="radio" name="btc_radio" value="17.39" checked="">
                            <div class="days">
                                <span>160</span>
                                <p>Days</p>
                            </div>
                        </label>
                    </div>
                    <a href="{{ url('/staking') }}" class="site-btn">Start Earning</a>
                </div>

                <div class="earning">
                    <div class="earning-top-section">
                        <div class="earning-left-section">
                            <svg width="40" height="41" viewBox="0 0 40 41" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M39.9998 19.1945V21.0301C39.9525 21.0862 39.9685 21.1547 39.9681 21.2174C39.9627 22.1238 39.829 23.0163 39.6732 23.9055C39.5342 24.6991 39.337 25.4798 39.0928 26.2472C38.9331 26.7491 38.7601 27.247 38.5648 27.7366C36.3884 33.1953 31.8968 37.3736 26.402 39.2311C25.7487 39.452 25.0849 39.6334 24.4113 39.7874C23.9187 39.9 23.424 40.005 22.9267 40.0779C22.2975 40.1701 21.6612 40.2208 21.0245 40.2604C19.6556 40.3459 18.2992 40.2413 16.946 40.0474C16.4469 39.9758 15.9506 39.8834 15.4615 39.7669C14.9177 39.6375 14.3794 39.4807 13.8444 39.315C13.4827 39.203 13.1324 39.062 12.7796 38.9274C11.9768 38.6211 11.1982 38.261 10.4449 37.8484C7.40739 36.1849 4.96728 33.8841 3.10773 30.965C2.31919 29.7273 1.68957 28.4102 1.18075 27.0369C0.985665 26.5101 0.82909 25.9678 0.68268 25.4239C0.479778 24.6697 0.329458 23.9045 0.205332 23.1355C0.120496 22.6103 0.0843329 22.0759 0.0528615 21.544C0.0206083 20.9989 -0.0192685 20.4527 0.0104436 19.9054C0.0403512 19.3532 0.0624398 18.8008 0.117368 18.25C0.161741 17.8037 0.223315 17.3589 0.290949 16.9169C0.336299 16.6208 0.404324 16.3233 0.472349 16.0281C0.605272 15.4519 0.752269 14.8788 0.9237 14.3139C1.02026 13.9956 1.12699 13.6776 1.24877 13.3657C1.43076 12.8989 1.61412 12.4337 1.82269 11.9778C2.96191 9.48855 4.54369 7.32277 6.57193 5.48479C8.48797 3.74845 10.6605 2.43139 13.0853 1.53282C13.8051 1.2662 14.5387 1.04689 15.2856 0.863346C16.0216 0.682538 16.764 0.549229 17.513 0.447586C18.0289 0.377609 18.5471 0.326592 19.0684 0.319946C19.12 0.319359 19.1732 0.321314 19.2191 0.290039C19.7139 0.291016 20.2086 0.291016 20.7034 0.291016C20.7739 0.301181 20.8443 0.318577 20.9151 0.320141C21.6739 0.338906 22.4233 0.43449 23.1718 0.558221C23.6357 0.63504 24.0984 0.71479 24.5564 0.820343C25.0667 0.938014 25.5715 1.07758 26.0711 1.23493C26.595 1.3999 27.1108 1.58853 27.6187 1.79788C30.7421 3.08601 33.3738 5.04029 35.5066 7.65878C36.7489 9.18401 37.755 10.8515 38.4882 12.6835C38.7073 13.2308 38.9098 13.7826 39.0971 14.3401C39.3782 15.1767 39.5661 16.0383 39.7185 16.9064C39.8456 17.6302 39.9425 18.3587 39.9623 19.0952C39.9633 19.1314 39.9507 19.1753 39.9998 19.1945Z"
                                    fill="#50AF95"></path>
                                <path
                                    d="M21.0927 26.0752C20.6279 26.0787 20.1896 26.0212 19.7504 25.9661C19.4128 25.9237 19.0777 25.865 18.7492 25.7865C18.4358 25.7116 18.1209 25.6389 17.8105 25.5496C17.6193 25.4946 17.4248 25.4506 17.2315 25.4035C17.0599 25.3617 17.0581 25.3623 17.1027 25.1832C17.4104 23.9467 17.7188 22.7102 18.0271 21.4737C18.0978 21.1898 18.1711 20.9064 18.2386 20.6218C18.2562 20.5477 18.2777 20.537 18.3561 20.555C18.5367 20.5962 18.7185 20.633 18.8989 20.6765C19.1851 20.7457 19.4759 20.7971 19.76 20.874C20.2078 20.995 20.6595 21.104 21.0962 21.2641C21.6144 21.4539 22.1248 21.6603 22.5932 21.9578C22.9194 22.165 23.2204 22.4019 23.4572 22.7106C23.7693 23.1179 23.9288 23.5763 23.8714 24.0916C23.7642 25.0507 23.2975 25.6647 22.2855 25.945C21.8887 26.0548 21.4817 26.0818 21.0931 26.075L21.0927 26.0752Z"
                                    fill="#F7931A"></path>
                                <path
                                    d="M24.5691 17.0077C24.523 17.195 24.486 17.3836 24.4305 17.5699C24.2941 18.0277 24.0141 18.3568 23.6058 18.5838C23.278 18.7659 22.9195 18.8473 22.5479 18.8842C22.0522 18.9337 21.5586 18.8907 21.0691 18.8338C20.7419 18.7959 20.4149 18.7315 20.0941 18.6412C19.735 18.5402 19.3695 18.4616 19.0069 18.3734C18.83 18.3304 18.8177 18.3199 18.8607 18.1399C19.0075 17.5263 19.158 16.9137 19.3101 16.3015C19.4854 15.5951 19.66 14.8883 19.8433 14.1838C19.9064 13.9414 19.8404 13.9101 20.1576 13.9883C20.5068 14.0743 20.8598 14.1447 21.2083 14.2336C21.8266 14.3914 22.4353 14.5796 23.0104 14.8621C23.4162 15.0614 23.787 15.3091 24.0914 15.6496C24.322 15.9076 24.4665 16.2055 24.5337 16.5431C24.5464 16.6068 24.5583 16.6705 24.5707 16.7343C24.5703 16.8253 24.5697 16.9164 24.5693 17.0077H24.5691Z"
                                    fill="#F7931A"></path>
                                <path
                                    d="M24.5703 17.0073C24.5707 16.9163 24.5713 16.8252 24.5717 16.7339C24.7298 16.8371 24.7296 16.9057 24.5703 17.0073Z"
                                    fill="white"></path>
                                <path
                                    d="M14.9918 17.9189C14.9914 17.9241 14.9923 17.9329 14.99 17.9337C14.985 17.9356 14.9785 17.9337 14.9727 17.9333C14.9731 17.9281 14.9722 17.9193 14.9745 17.9183C14.9795 17.9164 14.986 17.9183 14.992 17.9187L14.9918 17.9189Z"
                                    fill="white"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M10.3096 8.41282L5.02174 19.4197C5.00162 19.4607 4.99524 19.5069 5.00354 19.5517C5.01184 19.5965 5.03439 19.6375 5.06788 19.6686L19.3492 33.23C19.3898 33.2685 19.4438 33.29 19.5 33.29C19.5562 33.29 19.6102 33.2685 19.6508 33.23L33.9321 19.6695C33.9656 19.6383 33.9882 19.5973 33.9965 19.5525C34.0048 19.5077 33.9984 19.4615 33.9783 19.4206L28.6904 8.41367C28.6733 8.37671 28.6459 8.34539 28.6113 8.32347C28.5768 8.30155 28.5366 8.28995 28.4956 8.29006H10.5061C10.4649 8.28955 10.4244 8.30087 10.3896 8.32265C10.3547 8.34444 10.3269 8.37575 10.3096 8.41282Z"
                                    fill="white"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M21.2295 20.4284C21.1329 20.4363 20.6338 20.4686 19.5205 20.4686C18.6351 20.4686 18.0064 20.4398 17.7858 20.4284C14.3639 20.265 11.8098 19.6183 11.8098 18.844C11.8098 18.0698 14.3639 17.424 17.7858 17.2579V19.7844C18.0096 19.8018 18.6504 19.8429 19.5358 19.8429C20.5984 19.8429 21.1305 19.7948 21.2262 19.7852V17.2597C24.6409 17.4248 27.1894 18.0715 27.1894 18.844C27.1894 19.6166 24.6417 20.2633 21.2262 20.4275L21.2295 20.4284ZM21.2295 16.9984V14.7376H25.9948V11.29H13.0205V14.7376H17.785V16.9975C13.9124 17.1906 11 18.0234 11 19.0214C11 20.0194 13.9124 20.8514 17.785 21.0454V28.29H21.2287V21.0428C25.0925 20.8496 28 20.0177 28 19.0206C28 18.0234 25.0949 17.1915 21.2287 16.9975L21.2295 16.9984Z"
                                    fill="#50AF95"></path>
                            </svg>
                            <p>Tether US</p>
                        </div>
                        <div class="earning-right-section">
                            <p class="usdt_percent_text">24.85 %</p>
                        </div>
                    </div>

                    <div class="radio-button-forms">
                        <label>
                            <input type="radio" name="usdt_radio" value="0.25" checked="">
                            <div class="days">
                                <span>10</span>
                                <p>Days</p>
                            </div>
                        </label>
                        <label>
                            <input type="radio" name="usdt_radio" value="0.62" checked="">
                            <div class="days">
                                <span>20</span>
                                <p>Days</p>
                            </div>
                        </label><label>
                            <input type="radio" name="usdt_radio" value="1.08" checked="">
                            <div class="days">
                                <span>30</span>
                                <p>Days</p>
                            </div>
                        </label><label>
                            <input type="radio" name="usdt_radio" value="3.73" checked="">
                            <div class="days">
                                <span>90</span>
                                <p>Days</p>
                            </div>
                        </label><label>
                            <input type="radio" name="usdt_radio" value="8.06" checked="">
                            <div class="days">
                                <span>380</span>
                                <p>Days</p>
                            </div>
                        </label><label>
                            <input type="radio" name="usdt_radio" value="18.64" checked="">
                            <div class="days">
                                <span>160</span>
                                <p>Days</p>
                            </div>
                        </label>
                    </div><a href="{{ url('/staking') }}" class="site-btn">Start Earning</a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.Crypto-Lending-inner-section .top-section .Main_headings {
    text-align: center;
    margin-bottom: 20px;
}

.Crypto-Lending-inner-section .top-section p {
    text-align: center;
    font-size: 16px;
    max-width: 446px;
    margin: 0 auto;
}

.Crypto-Lending-section .container {
    max-width: 1049px;
}

.Crypto-Lending-section .pick-plans-section {
    display: flex;
    justify-content: center;
    padding: 60px 0 41px 0;
    position: relative;
}

.Crypto-Lending-section .pick-plans-section:after {
    content: '';
    position: absolute;
    top: 50%;
    background-color: rgba(78, 78, 78, 1);
    height: 1px;
    width: 100%;
    max-width: 530px;
    border-radius: 4px;
}

.Crypto-Lending-section .pick-plans-section .plans p {
    max-width: 175px;
    text-align: center;
    font-size: 16px;
    line-height: 30px;
    position: relative;
    padding-top: 80px;
    margin: 0;
}

.Crypto-Lending-section .pick-plans-section .plans p:before {
    content: '';
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background-color: #4D4D4D;
    position: absolute;
    top: 26px;
    left: 50%;
    transform: translate(-50%, 0);
    z-index: 11;
}

.Crypto-Lending-section .pick-plans-section .plans p:after {
    content: '';
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background-color: #111111;
    position: absolute;
    top: 17px;
    left: 50%;
    transform: translate(-50%, 0);
    z-index: 1;
}

.Crypto-Lending-section .pick-plans-section .plans {
    padding: 0 35px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
    align-items: center;
}

.Crypto-Lending-section .earning-section .earning {
    border-radius: 20px;
    background: #181819;
    padding: 20px;
}

.Crypto-Lending-section {
    padding: 0 0 10px 0;
}

.Crypto-Lending-section .earning-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-gap: 21px;
}

.earning-left-section svg {
    margin-right: 18px;
}

.earning-left-section {
    display: flex;
    align-items: center;
}

.earning-top-section {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid;
    padding-bottom: 26px;
}

.earning-top-section p {
    font-size: 26.3px;
    font-weight: 700;
    line-height: 40px;
}

.radio-button-form label,
.radio-button-forms label {
    position: relative;
    margin: 0 13px 13px 0;
}

.radio-button-form .days p,
.radio-button-forms .days p {
    color: #CFCFCF;
    text-align: center;
    font-size: 12px;
    font-family: Segoe UI;
    font-weight: 600;
    margin: 0;
}

.radio-button-form label:last-child,
.radio-button-forms label:last-child {
    margin-right: 0;
}

.radio-button-form .days,
.radio-button-forms .days {
    display: inline-block;
    position: relative;
    border-radius: 10px;
    background: #2F2F2F;
    text-align: center;
    padding: 5px 12px;
    cursor: pointer;
    border: 2px solid #007bff00;
}

.radio-button-form label input:checked+.days,
.radio-button-forms label input:checked+.days {
    border-color: #007bff;
}

.radio-button-form .days span,
.radio-button-forms .days span {
    text-align: center;
    font-size: 20px;
    font-weight: 600;
}

.radio-button-form,
.radio-button-forms {
    padding-top: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    padding-bottom: 17px;
}

.radio-button-form input,
.radio-button-forms input {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    z-index: 99;
    cursor: pointer;
}
</style>

<script>
$(document).ready(function(){
//   $('input[name="name_of_your_radiobutton"]:checked').val();
    $('input[name="btc_radio"]').click(function() {
        $('.btc_percent_text').text($(this).val() + '%');
    });
    $('input[name="usdt_radio"]').click(function() {
        $('.usdt_percent_text').text($(this).val() + '%');
    });
});
</script>

<!-- APPS -->
	<section class="apps">
		<div class="container">
			<div class="row">
				<div class="col-lg-5 col-md-12 order-lg-2 align-items-self">
					<div class="appss-content">
						<h2 class="apps-title">Coming Soon: Cryptimize App for the best trading experience!</h2>
						<ul class="apps-property">
							<li>Experience the ultimate coin buying and selling platform.</li>
							<li>Quick and effortless sign-up process.</li>
							<li>Trade anytime, anywhere, 24/7</li>
							<li>Tablet and mobile multi-device support</li>
						</ul>
					</div>
				</div>
				<div class="col-lg-7 col-md-12 order-lg-1 align-items-end">
					<div class="appss-img">
						<img class="img-fluid" src="{{ asset('uploads/image_1_1765544443639-removebg-preview.png') }}" alt="Cryptimize Apps">
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- APPS End-->

    

    <style>
        .explore-our-products {
            padding-top: 108px;
            padding-bottom: 100px;
        }

        .explore-box-main-section {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            grid-gap: 25px;
        }

        .explore-box {
            background-color: rgba(24, 24, 25, 1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            margin-right: 10px;
            padding: 80px 0 0 32px;
        }

        .explore-box p {
            font-size: 27px;
            line-height: 38px;
            padding-right: 18px;
            text-align: left;
            margin-bottom: 1em;
        }

        .explore-box-bottom {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .explore-box p span {
            font-weight: 800;
        }

        .explore-box-bottom img {
            width: 112px;
            height: 112px;
        }

        .index_action-text {
            display: flex;
            align-items: center;
            padding-bottom: 30px;
            cursor: pointer;
        }

        .index_action-text svg {
            height: 10px;
            margin-right: 9px;
            display: flex;
            align-items: center;
            margin-top: 3px;
        }

        .index_action-text a {
            color: #1e90ff;
            font-size: 20px;
            font-weight: 700;
            line-height: 20px;
        }

        .explore-our-products .Main_headings {
            margin-bottom: 55px;
        }
    </style>


<section class="blog">
    <div class="container">
        
        <h2 class="section-title Main_headings text-center">{{ \App\Models\Setting::get('site_name', 'Website Name') }} Blog</h2>

        <div class="blog-single-item arrow-black">
            <div>
                <div class="blog-grid">
                    <a href="{{ url('article/22') }}"  class="card">
                        <figure>

                            <img class="img-fluid" src="{{ asset('uploads/blog/image_1_1765544767625.jpg') }}" alt="Pin Bar Candlestick Pattern">
                        </figure>
                        <div class="card-body">
                            <!--div class="blog-categorie"><span>Bitcoin</span><span>News</span></div-->
                            <h4 class="card-title">Pin Bar Candlestick Pattern...</h4>
                            <!-- <p class="card-text">		
		
			
			
		

		
		 .my-blog-post-container {    display: flex;    flex-direction: column...</p> -->
                            <!--span class="date">2023-11-09 05:51:53</span-->
                        </div>
                    </a>
                </div>
            </div><div>
                <div class="blog-grid">
                    <a href="{{ url('article/9') }}"  class="card">
                        <figure>

                            <img class="img-fluid" src="{{ asset('uploads/blog/c8f1581f924448149b4843a41aafadc1.jpg') }}" alt="Chart Patterns Cheat Sheet">
                        </figure>
                        <div class="card-body">
                            <!--div class="blog-categorie"><span>Bitcoin</span><span>News</span></div-->
                            <h4 class="card-title">Chart Patterns Cheat Sheet...</h4>
                            <!-- <p class="card-text">		
		
			
			
		

		
		 .my-blog-post-container {    display: flex;    flex-direction: column...</p> -->
                            <!--span class="date">2023-10-14 00:45:39</span-->
                        </div>
                    </a>
                </div>
            </div><div>
                <div class="blog-grid">
                    <a href="{{ url('article/8') }}"  class="card">
                        <figure>

                            <img class="img-fluid" src="uploads/blog/2f782a61-413a-4cc7-a2a8-aeb18d115b3c.png" alt="How To Draw Trend Lines">
                        </figure>
                        <div class="card-body">
                            <!--div class="blog-categorie"><span>Bitcoin</span><span>News</span></div-->
                            <h4 class="card-title">How To Draw Trend Lines...</h4>
                            <!-- <p class="card-text">		
		
			
			
		

		
		 .my-blog-post-container {    display: flex;    flex-direction: column...</p> -->
                            <!--span class="date">2023-10-14 00:45:22</span-->
                        </div>
                    </a>
                </div>
            </div><div>
                <div class="blog-grid">
                    <a href="{{ url('article/7') }}"  class="card">
                        <figure>

                            <img class="img-fluid" src="{{asset('uploads/blog/653225f095433.png')}}" alt="Avalanche Vs Solana">
                        </figure>
                        <div class="card-body">
                            <!--div class="blog-categorie"><span>Bitcoin</span><span>News</span></div-->
                            <h4 class="card-title">Avalanche Vs Solana...</h4>
                            <!-- <p class="card-text">	
	
		
		
	

	
	 .my-blog-post-container {    display: flex;    flex-direction: column;    align-ite...</p> -->
                            <!--span class="date">2023-10-14 00:45:03</span-->
                        </div>
                    </a>
                </div>
            </div>        </div>
    </div>
</section>
<!-- Guide & Blog End -->



    <div class="cta-trading-now wow animate__fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
        <div class="block-section">
            <div class="container container--medium">
                <h2 class="has-text-align-center">Start <span class="blue">Trading</span> Now</h2>
                <!-- <form class="trade-form" id="trade-form">
              <input class="phone" id="fphone" type="text" placeholder="Enter Mobile No." autocomplete="off">
              <button type="submit" class=""><img src="assets/assets/images/icons/btn-arrow.svg" alt=""></button>
            </form> -->
                <div class="wp-block-lazyblock-country-based-content lazyblock-country-based-content-ZVukOf">
                    <div class="block-section">
                        <div class="container container--medium ">
                            <div class="cta-section wow animate__fadeInUp wp-block-lazyblock-cta lazyblock-cta-1FASAQ"
                                style="visibility: visible; animation-name: fadeInUp;">
                                <div class="cta">
                                    <div class="container container--medium">
                                        <div class="cta__row">
                                            @auth
                                                <h3 class="cta__heading">
                                                    Go To Dashboard to Continue Trading<strong>Us</strong> </h3>
                                                <div class="cta__btn">
                                                    <a class="btn btn--fill" target="_blank"
                                                        href="{{ url('dashboard') }}">Dashboard</a>
                                                </div>
                                            @else
                                                <h3 class="cta__heading">
                                                    Register now to begin trading with <strong>Us</strong> </h3>
                                                <div class="cta__btn">
                                                    <a class="btn btn--fill" target="_blank" href="{{ url('register') }}">Signup
                                                        Now</a>
                                                </div>
                                            @endauth

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                        <img src="${pair.base_currency.logo_url}" width="30" class="rounded-circle me-2">
                        <div>
                            <strong>${pair.base_currency.symbol}/${pair.quote_currency.symbol}</strong>
                            <div class="small text-muted">${pair.base_currency.name}</div>
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

                <td><a href="/trade/${pair.id}" class="btn-2">Trade</a></td>
            </tr>`;
            });
        }
    </script>


@endsection

@push('scripts')
  <script>
function easytrade() {
    const coin = $('#easycoin').find(':selected').data('value');
    const price = parseFloat($('#easycoin').find(':selected').data('price'));
    const amount = parseFloat($('#easyamount').val());

    if (!amount || amount <= 0) {
        $('#tradeResult').html('<span class="text-danger">Please enter a valid USDT amount!</span>');
        return;
    }

    $.post("{{ route('do-trade') }}", {
        _token: "{{ csrf_token() }}",
        coin: coin,
        amount: amount,
        type: 'buy'  // Only buy allowed
    }, function(data) {
        if (data.status === 1) {
            $('#tradeResult').html('<span class="text-success">' + data.info + '</span>');
        } else {
            $('#tradeResult').html('<span class="text-danger">' + data.info + '</span>');
        }
    }, 'json');
}
</script>
@endpush








