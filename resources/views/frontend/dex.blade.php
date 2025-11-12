@extends('layouts.app')

@section('title', 'Dex')

@section('content')

<link rel="stylesheet" href="{{ asset('Public/template/epsilon/otc/otc.css') }}" />

<main class="wrapper">
	<!-- Swap Start -->

<style>
  .dropdown-backdrop {
    display: none;
  }
</style>
<div class="page-container">
  <div class="otc-body">
    <div class="row card">
      <!-- leftbox Starts -->
      <div class="col-md-12 col-sm-12">
        <div class="leftbox">
          <div class="left-title-1">
            <div class="left-title-2">
              Buy ZTU            </div>
          </div>
          <div class="head-tabs-1">
            <div class="head-tabs-1-2">
              <div class="BaseLine"></div>
            </div>
          </div>
          <!--buytab starts-->
          <div class="coinwrap buytab">
            <div class="coin-input-div">
              <div class="coin-input-label" id="input1text">Buy</div>

              <input
                placeholder="Enter Amount"
                class="input-box-css"
                value=""
                id="change1stInput"
              />
              <div class="coin-input-split"></div>
              <div class="coin-input-coinwrap" id="showbuyoptions">
                <a
                  class="dropdown-toggle xbuyoption"
                  data-toggle="dropdown"
                  href="#"
                >
                  <img
                    class="coin-input-coinimage"
                    src="Upload/Coin/625d0de658984.html"
                  /><span class="coinInput-CoinText"
                    >ZTU</span
                  >
                </a>
              </div>
            </div>

            <div class="coinInput-error" id="input1error"></div>
          </div>
          <!--buytab ends-->
          <div class="coinwrap selltab">
            <div class="coin-input-div">
              <div class="coin-input-label" id="input2text">Spend</div>
              <input
                placeholder="Enter Amount"
                class="input-box-css"
                value=""
                id="change2ndInput"
              />
              <div class="coin-input-split"></div>
              <div class="coin-input-coinwrap" id="showselloptions">
                <a
                  class="dropdown-toggle xselloption"
                  data-toggle="dropdown"
                  href="#"
                >
                  <img
                    class="coin-input-coinimage"
                    src="#"
                  /><span class="coinInput-CoinText"
                    >ETH</span
                  >
                  <span class="caret"></span>
                </a>

                <ul class="dropdown-menu">
                  <li role="option">
                      <div class="CoinInput-Option">
                        <a href="javascript:void(0);"
                          ><img
                            class="CoinInput-Logo"
                            src="{{ asset('Upload/coin/6113d051e051d.png') }}"
                          />UNI</a
                        >
                      </div>
                    </li><li role="option">
                      <div class="CoinInput-Option">
                        <a href="javascript:void(0);"
                          ><img
                            class="CoinInput-Logo"
                            src="{{ asset('Upload/coin/DAI.png') }}"
                          />DAI</a
                        >
                      </div>
                    </li><li role="option">
                      <div class="CoinInput-Option">
                        <a href="javascript:void(0);"
                          ><img
                            class="CoinInput-Logo"
                            src="{{ url('Upload/coin/ETH.png') }}"
                          />ETH</a
                        >
                      </div>
                    </li>                </ul>
              </div>
            </div>

            <div class="coinInput-error" id="input2error"></div>
          </div>

          <input name="tradetype" id="tradetype" value="buy" type="hidden" />
          <input
            name="trade_coin"
            id="trade_coin"
            value="ztu"
            type="hidden"
          />
          <input
            name="base_coin"
            id="base_coin"
            value="eth"
            type="hidden"
          />
          <input name="min_req" id="min_req" value="" type="hidden" />
          <input name="max_req" id="max_req" value="" type="hidden" />
          <input name="qid" id="qid" value="" type="hidden" />
          <button
            data-bn-type="button"
            disabled=""
            id="quote-button"
            class="quote-button"
            onclick="requestQuote()"
          >
            Buy Now
          </button>
        </div>
      </div>
      <!-- Leftbox ends -->

      <!--Right box was here-->
    </div>
  </div>
</div>
</main>
<script type="text/javascript">
  var trade_coins = [{"id":2,"name":"Uniswap","is_token":1,"symbol":"uni","contract_address":"0x1f9840a85d5af5bf1d1762f925bdaddc4201f984","decimals":18,"img":"6113d051e051d.png","price":"0.00200000","buy_max":"100.00000000","buy_min":"0.00010000","is_default":0,"status":1},{"id":3,"name":"DAI","is_token":1,"symbol":"dai","contract_address":"0xad6d458402f60fd3bd25163575031acdce07538d","decimals":18,"img":"DAI.png","price":"0.01010000","buy_max":"10000.00000000","buy_min":"0.01000000","is_default":0,"status":1},{"id":4,"name":"ethereum","is_token":0,"symbol":"eth","contract_address":"","decimals":8,"img":"ETH.png","price":"0.00030001","buy_max":"10000000.00000000","buy_min":"0.00001000","is_default":1,"status":1}] ;
  var base_coins = [{"id":2,"name":"Uniswap","is_token":1,"symbol":"uni","contract_address":"0x1f9840a85d5af5bf1d1762f925bdaddc4201f984","decimals":18,"img":"6113d051e051d.png","price":"0.00200000","buy_max":"100.00000000","buy_min":"0.00010000","is_default":0,"status":1},{"id":3,"name":"DAI","is_token":1,"symbol":"dai","contract_address":"0xad6d458402f60fd3bd25163575031acdce07538d","decimals":18,"img":"DAI.png","price":"0.01010000","buy_max":"10000.00000000","buy_min":"0.01000000","is_default":0,"status":1},{"id":4,"name":"ethereum","is_token":0,"symbol":"eth","contract_address":"","decimals":8,"img":"ETH.png","price":"0.00030001","buy_max":"10000000.00000000","buy_min":"0.00001000","is_default":1,"status":1}] ;

  $("#change2ndInput").change(function(){
  $('.quote-button').prop('disabled', true);
  $("#input1error").html('');
  $("#input2error").html('');
  $('.quote-button').prop('disabled', false);
  var value=parseFloat($('#change2ndInput').val());
  $('#change1stInput').val("");
  var select_coin_b=$("#base_coin").val().toLowerCase();
  var select_coin=select_coin_b.toLowerCase();

  var min_req_b=parseFloat(base_coins[select_coin]['min']);
  var max_req_b=parseFloat(base_coins[select_coin]['max']);

  if(value<min_req_b){
  $('.quote-button').prop('disabled', true);
  $("#input2error").html('Amount should be greater than '+min_req_b+' '+select_coin_b);

  }
  else if(value>max_req_b)
  {
  $('.quote-button').prop('disabled', true);
  $("#input2error").html('Amount should be less than  '+max_req_b+' '+select_coin_b);

  }else{
  //Means its true enable button for quote
  $('.quote-button').prop('disabled', false);
  $("#input2error").html('');
  }


  });


  $("#change1stInput").change(function(){
  $('.quote-button').prop('disabled', true);
  $("#input1error").html('');
  $("#input2error").html('');
  $('.quote-button').prop('disabled', false);
  var value=parseFloat($('#change1stInput').val());
  var select_coin_t=$("#trade_coin").val().toLowerCase();
  var select_coin=select_coin_t.toLowerCase();
  $('#change2ndInput').val("");
  var min_req_t=parseFloat(trade_coins['min']);
  var max_req_t=parseFloat(trade_coins['max']);

  if(value<min_req_t){
  $('.quote-button').prop('disabled', true);
  $("#input1error").html('Amount should be greater than  '+min_req_t+' '+select_coin_t);

  }
  else if(value>max_req_t)
  {
  $('.quote-button').prop('disabled', true);
  $("#input1error").html('Amount should be less than  '+max_req_t+' '+select_coin_t);

  }else{
  //Means its true enable button for quote
  $('.quote-button').prop('disabled', false);
  $("#input1error").html('');
  }

  });


  $("#showbuyoptions li a").click(function () {
      var selText = $(this).text();
      var imgSource = $(this).find('img').attr('src');
      var img = '<img class="coin-input-coinimage" src="' + imgSource + '"/>';
  	var source =img+'<span class="coinInput-CoinText">'+selText+'</span> <span class="caret"></span>';
      $('.xbuyoption').html(source);
  	$("#trade_coin").val(selText);
  });

  $("#showselloptions li a").click(function () {
      var selText = $(this).text();

      var imgSource = $(this).find('img').attr('src');
      var img = '<img class="coin-input-coinimage" src="' + imgSource + '"/>';
  	var source =img+'<span class="coinInput-CoinText">'+selText+'</span> <span class="caret"></span>';
      $('.xselloption').html(source);
  	$("#base_coin").val(selText);

  });

  function rejectQuote(){
  $('#quote-button').prop('disabled', false);
  				$('#received_price').html("");
  				$('#received_total').html("");
  				$('#tradecoin_name').html("");
  				$('#tradecoin_qty').html("");
  				$('#tradetype_final').html("");
  				$("#with_order").css("display","none");
  				$("#blank_order").css("display","flex");
  }
  function requestQuote(){
  $("#quotefinal").css("display","flex");
  $("#quoteerror").css("display","none");

  var base_coin=$("#base_coin").val();
  var input1=$("#change1stInput").val();
  var input2=$("#change2ndInput").val();
  var tradetype=$("#tradetype").val();

  		$.post("/Dex/getPrice", {
  			spend_coin: base_coin,
  			buy_qty: input1,
  			spend_qty: input2,

  		}, function (data) {
  			if (data.status == 1) {
  				layer.msg(data.msg, {icon: 1});

  				window.location=data.url;

  			} else {
  				layer.msg(data.msg, {icon: 3});
  				layer.alert(data.info, {title: "Info",btn: ['Ok']});
  			}
  		}, "json");
  }

  function approveQuote(){

  var qid=$("#qid").val();
  if(qid=='' || qid==0)
  {
  layer.alert('You currently do not have a valid quote, Please refresh!', {title: "Info",btn: ['Ok']});
  }
  $.post("/Otc/approveQuote", {
  			qid: qid,
  		}, function (data) {
  			if (data.status == 1) {
  				layer.msg(data.msg, {icon: 1});

  				window.location=data.url;
  			} else {
  				layer.alert(data.info, {title: "Info",btn: ['Ok']});
  			}
  		}, "json");
  }
</script>



@endsection