@extends('layouts.app')

{{-- @section('title', 'Dashboard') --}}

@section('content')

<main class="wrapper grey-bg"></main>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'979579038a0e65fe',t:'MTc1NjkwNDg3Mw=='};var a=document.createElement('script');a.src='../cdn-cgi/challenge-platform/h/b/scripts/jsd/4710d66e8fda/maind41d.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script>
</body>

	<!-- Graph Start -->
	<div class="graph graph-main-section graph-padding d-none d-md-block hide-mobile">
		<div class="container-fluid">
			<div class="row margin-balance">
						</div>
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
    color: #ffbe40 !important;
}
.slick-dots li button:before{
    color: #ffbe40;
}
#dark .slip-limit-container .limit-input, #dark .slip-limit-container .limit-button, #dark .card-header, #dark .card, #dark .home-markets > .tab-content {
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
#dark .coin-pnl-modal-summary ul li, #dark .table td, #dark .table th{
    border: 0;
}
#dark #daily-top-winners .coin-list .btn-2, .codono-distribution-table.table .btn-2, .p2p-list-table .btn-2 {
    background-color: transparent;
    border-color: #ffbe40;
    color: #fff!important;
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
								
								<li  class=" nav-link trade_moshi trade_qu_pai current" data="0.html" onclick="trade_qu(this)">
	<a href="#highlighted-justified-tab1" class="active" data-toggle="tab">USDT</a>
	</li>
	{{-- <li  class=" nav-link trade_moshi trade_qu_pai " data="1.html" onclick="trade_qu(this)">
	<a href="#highlighted-justified-tab1" class="" data-toggle="tab">BTC</a>
	</li><li  class=" nav-link trade_moshi trade_qu_pai " data="2.html" onclick="trade_qu(this)">
	<a href="#highlighted-justified-tab1" class="" data-toggle="tab">ETH</a>
	</li><li  class=" nav-link trade_moshi trade_qu_pai " data="3.html" onclick="trade_qu(this)">
	<a href="#highlighted-justified-tab1" class="" data-toggle="tab">EUR</a>
	</li>	 --}}
							</ul>

						</div>
						<div class="tab-content">
							<div class="tab-pane fade show active" style="min-height: 200px !important;" id="daily-top-winners" role="tabpanel">
								<div class="table-responsive">

									<table class="table markets-pair-list table-hover">
										<thead class="price_today_ull">
										<tr>
                                    <th scope="col" data-sort="0" style="cursor: default;">PAIR</th>
                                    <th scope="col"class="click-sort" data-sort="1" data-flaglist="0" data-toggle="0">Price </th>
									
                                    {{-- <th scope="col" class="click-sort" data-sort="2" data-flaglist="0" data-toggle="0">Buy</th>
                                    <th scope="col" class="click-sort" data-sort="3" data-flaglist="0" data-toggle="0">Sell</th> --}}
                                    <th scope="col" class="click-sort" data-sort="6" data-flaglist="0" data-toggle="0">24H Vol</th>
                                    <th scope="col"  class="click-sort" data-sort="4" data-flaglist="0" data-toggle="0">24H Total</th>
                                    <th scope="col" class="click-sort" data-sort="7" data-flaglist="0" data-toggle="0">Market Cap</th>
									<th scope="col">Action</th>
										</tr>
									</thead>
										<tbody id="">
											@forelse($tradingPairs as $pair)
											<tr class="">
												<td class="">
													<div class="">
														@if($pair->baseCurrency->logo_url)
														<img src="{{ $pair->baseCurrency->logo_url }}" class="h-8 w-8 rounded-full mr-3" alt="{{ $pair->baseCurrency->symbol }}">
														@endif
														<div>
															<div class="text-sm font-medium text-gray-900">{{ $pair->symbol }}</div>
															<div class="text-sm text-gray-500">{{ $pair->baseCurrency->name }}</div>
														</div>
													</div>
												</td>
												<td class="">
													<div class="text-sm font-semibold text-gray-900" data-price-{{ $pair->baseCurrency->id }}>
														${{ number_format($pair->getCurrentPrice(), 2) }}
													</div>
												</td>
												<td class="">
													<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $pair->baseCurrency->price_change_24h >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}" data-change-{{ $pair->baseCurrency->id }}>
														{{ number_format($pair->baseCurrency->price_change_24h, 2) }}%
													</span>
												</td>
												<td class=" text-sm text-gray-900">
													${{ number_format($pair->baseCurrency->volume_24h, 0) }}
												</td>
												<td class=" text-sm text-gray-900">
													${{ number_format($pair->baseCurrency->market_cap, 0) }}
												</td>
												<td class="text-right">
													<a href="{{ url('trading/spot', $pair->id) }}" class="btn-2 yellow-bg ">
														Trade
													</a>
												</td>
											</tr>
											@empty
											<tr>
												<td colspan="6" class="px-6 py-4 text-center text-gray-500">
													No trading pairs available
												</td>
											</tr>
											@endforelse
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
	<script>
	$('.price_today_ull > .click-sort').each(function () {
		$(this).click(function () {
			click_sortList(this);
		})
	});
	function allcoin_callback(priceTmp) {
		for (var i in priceTmp) {
			var c = priceTmp[i][8];
			if (typeof (trends[c]['data']) && typeof (trends[c]['data']) != 'null') {
				if (typeof (trends[c]) != 'undefined' && typeof (trends[c]['data']) != 'undefined') {
				
				}
			}
		}
	}
	function click_sortList(sortdata) {
		var a = $(sortdata).attr('data-toggle');
		var b = $(sortdata).attr('data-sort');
		$(".price_today_ull > th").find('.fa-angle-up').css('border-bottom-color', '#848484');
		$(".price_today_ull > th").find('.fa-angle-down').css('border-top-color', '#848484');
		$(".price_today_ull > th").attr('data-flaglist', 0).attr('data-toggle', 0);
		$(".price_today_ull > th").css('color', '');
		$(sortdata).css('color', '#1a81d6');
		if (a == 0) {
			priceTmp = priceTmp.sort(sortcoinList('dec', b));
			$(sortdata).find('.fa-angle-down').css('border-top-color', '#1a81d6');
			$(sortdata).find('.fa-angle-up').css('border-bottom-color', '#848484');
			$(sortdata).attr('data-flaglist', 1).attr('data-toggle', 1)
		}
		else if (a == 1) {
			$(sortdata).attr('data-toggle', 0).attr('data-flaglist', 2);
			$(sortdata).find('.fa-angle-up').css('border-bottom-color', '#1a81d6');
			$(sortdata).find('.fa-angle-down').css('border-top-color', '#848484');
			priceTmp = priceTmp.sort(sortcoinList('asc', b));
		}
		renderPage(priceTmp);
		change_line_bg('price_today_ul', 'li');
	//	allcoin_callback(priceTmp);
	}
	function trends() {
		$.getJSON("/Ajax/top_coin_menu?t=" + rd(), function (d) {
			trends = d;
			allcoin();
		});
	}
	function allcoin(cb) {
		var trade_qu_id = $('.trade_qu_list .current').attr('data');
		$.get("/Ajax/allcoin_a/id/" + trade_qu_id + '?t=' + rd(), cb ? cb : function (data) {
			var d;
			if (data.status == 1) {
				d = data.url;
			}
			ALLCOIN  = d;
			var t    = 0;
			var img  = '';
			priceTmp = [];
			for (var x in d) {
				if (typeof(trends[x]) != 'undefined' && parseFloat(trends[x]['yprice']) > 0) {
					rise1 = (((parseFloat(d[x][4]) + parseFloat(d[x][5])) / 2 - parseFloat(trends[x]['yprice'])) * 100) / parseFloat(trends[x]['yprice']);
					rise1 = rise1.toFixed(2);
				} else {
					rise1 = 0;
				}
				img = d[x].pop();
				d[x].push(rise1);
				d[x].push(x);
				d[x].push(img);
				priceTmp.push(d[x]);
			}
			$('.price_today_ull > .click-sort').each(function () {
				var listId = $(this).attr('data-sort');
				if ($(this).attr('data-flaglist') == 1 && $(this).attr('data-sort') !== 0) {
					priceTmp = priceTmp.sort(sortcoinList('dec', listId))
				} else if ($(this).attr('data-flaglist') == 2 && $(this).attr('data-sort') !== 0) {
					priceTmp = priceTmp.sort(sortcoinList('asc', listId))
				}
			});
			renderPage(priceTmp);
		//	allcoin_callback(priceTmp);
			change_line_bg('price_today_ul', 'li');
			//t = setTimeout('allcoin()', 5000);
		}, 'json');
	}
	function rd() {
		return Math.random()
	}
	function renderPage(ary) {
		var html = '';
		for (var i in ary) {
		
			var coinfinance = 0;
			if (typeof FINANCE == 'object') coinfinance = parseFloat(FINANCE.data[ary[i][8] + '_balance']);
			let total_24=ary[i][4] * ary[i][6];
			html += '<tr class="ok" id="m_' + ary[i][8] + '"><td><a href="/trade/index/market/' + ary[i][8] + '/"><div class="pair-name"><i class="icon ion-md-star add-to-favorite"></i><div class="icon" style="background-image: url(\'/Upload/coin/'+ary[i][9]+'\')"></div><div class="name">'+ary[i][12]+' <span>'+ary[i][11]+'</span></div></div></a></td><td class="tc-warning">' + ary[i][1] + '</td><td >' + ary[i][2] + '</td><td >' + ary[i][3] + '</td><td >' + formatCount(ary[i][6]) + '</td><td >' + formatCount(total_24) + '</td><td class="' + (ary[i][7] >= 0 ? 'green' : 'red') + '" >' + (parseFloat(ary[i][7]) < 0 ? '' : '') + ((parseFloat(ary[i][7]) < 0.01 && parseFloat(ary[i][7]) > -0.01) ? "0.00" : (parseFloat(ary[i][7])).toFixed(2)) + '%</td><td class="text-right"><button type="button" class="btn-2 yellow-bg infobutton" data-id="' + ary[i][8] + '">Info</button> <a href=\'/trade/index/market/' + ary[i][8] + '/\'" class="btn-2 yellow-bg"> Trade</a></td></tr>';
		}
		$('#price_today_ul').html(html);
	}
	function formatCount(count) {
	count=parseFloat(count.toFixed(8));
	
		var countokuu = (count / 1000000000).toFixed(3);
		var countwan  = (count / 1000000).toFixed(3);
		if (count > 1000000000)
			return countokuu.substring(0, countokuu.lastIndexOf('.') + 3) + ' bl';
		if (count > 1000000){
			return countwan.substring(0, countwan.lastIndexOf('.') + 3) + ' ml';
		}
		else
		{
			return count;
			if(isNaN(count))
				{return count;}
				else
				{return count.toFixed(2);}
		}
			
	}
	function change_line_bg(id, tag, nobg) {
		var oCoin_list = $('#' + id);
		var oC_li      = oCoin_list.find(tag);
		var oInp       = oCoin_list.find('input');
		var oldCol     = null;
		var newCol     = null;
		if (!nobg) {
			for (var i = 0; i < oC_li.length; i++) {
				oC_li.eq(i).css('background-color', i % 2 ? '#f8f8f8' : '#fff');
			}
		}
		oCoin_list.find(tag).hover(function () {
			oldCol = $(this).css('backgroundColor');
			$(this).css('background-color', '#eaedf4');
		}, function () {
			$(this).css('background-color', oldCol);
		})
	}
	function sortcoinList(order, sortBy) {
		var ordAlpah = (order == 'asc') ? '>' : '<';
		var sortFun  = new Function('a', 'b', 'return parseFloat(a[' + sortBy + '])' + ordAlpah + 'parseFloat(b[' + sortBy + '])? 1:-1');
		return sortFun;
	}
	function trade_qu(o){
		$('.trade_qu_pai').removeClass('current');
		$(o).addClass('current');
		allcoin();
	}
	trends();
</script>
<!-- info modal start -->
	<div class="modal fade infoModal left" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="Info Modal" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="infoPair"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12">
							<p><span>Symbol <b id="infoSymbol"></b></span></p>
							<p><span>Name <b id="infoName"></b></span></p>
							<p><span>Release Date <b id="infoRelease"></b></span></p>
							<p><span>Block Reward <b id="infoReward"></b></span></b></p>
							<p><span>Supply <b id="infoSupply"></b></span></p>
							<p><span>Withdrawal <b id="infoWithdrawal"></b></span></p>
							<p><span>Deposit <b id="infoDeposit"></b></span></p>
							<p><span>Official Link <b id="infoLink"></b></span></p>
							<h2 class="" class="infoDescription">Description</h2>
							<div id="infoDescription"></div>
						</div><!--/col-sm-6-->
					</div><!--/row-->
				</div>
				<div class="modal-footer">
					<button type="button" class="btn-1" data-dismiss="modal">Close</button>
					<a href="#" class="btn-2" id="tradelink" >Trade</a>
				</div>
			</div>
		</div>
	</div>
	<!-- info modal end -->
	<script>
	$(document).on("click", ".infobutton", function () {
     var market = $(this).data('id');
	 
	 $.post("/Content/info", {
						
						market: market
					}, function (data) {
						
						if (data.status == 1) {
							$('#infoPair').html(data.info.market);
							$('#infoName').html(data.info.name);
							$('#infoSymbol').html(data.info.xnb);
							$('#infoRelease').html(data.info.releasedate);
							$('#infoSupply').html(data.info.supply);
							$('#infoReward').html(data.info.reward);
							$('#infoWithdrawal').html(data.info.withdrawal);
							$('#infoDeposit').html(data.info.deposit);
							$('#infoLink').html('<a href="'+data.info.link+'" target="_blank" rel="noreferrer">Official Site</a>');
							$('#infoDescription').html(data.info.description);
							if(data.info.description==''){
								$('.infoDescription').hide();	
							}else{
							}
							$('#tradelink').attr("href", data.info.tradelink); // Set herf value
							$('#infoModal').modal('show');
						} else {

						}
					}, "json");
     
      
});</script>
<!-- Footer -->


@endsection