@extends('layouts.app')

@section('title', 'Dashboard - Crypto Trading Platform')

@section('content')


<style>
  body,
  html {
    overflow-x: hidden;
  }

  .sm_screen {
    display: none;
  }

  .order-book tbody tr {
    flex-shrink: 0;
  }

  @media only screen and (max-width: 768px) {
    .lg_screen {
      display: none;
    }

    .sm_screen {
      display: flex;
    }

    .outflexboxx {
      display: block !important;
    }
  }
</style>
<script type="text/javascript">
  let market = "btc_usdt";
  let market_round = "2";

  let market_round_num = "2";
  let market_type = "1";
  let userid = "0";
  let trade_moshi = 1;
  let getDepth_tlme = null;
  let trans_lock = 0;
  if ("") {
    const colorshade = "";
  }
  else {
    const colorshade = "Dark";
  }
  $(document).ready(function () {
    if (window.innerWidth <= 768) {
      $('.lg_screen').remove();
    }
  });
</script>

<div class="container-fluid mtb15 no-fluid">
  <div class="row sm-gutters lg_screen">
    <div class="col-md-3">
      <!-- Order Book Start -->
     @include('trading.order-book')
      <!-- Order Book End -->


    </div>
    <div class="col-md-6">

     @include('trading.chart')

      <div class="market-trade">
        <div class="d-flex justify-content-between align-items-center">
          <ul class="nav nav-pills" role="tablist">
            <li class="nav-item">
              <span class="nav-link active" onclick="OrderType('limit')">Limit</span>
            </li>
            <li class="nav-item">
              <span class="nav-link active" onclick="OrderType('market')">Market</span>
            </li>
            <li class="nav-item">
              <span class="nav-link active" onclick="OrderType('stop')">Stop-Limit</span>
            </li>
          </ul>
          <div class="hide-mobile">
            <button class="btn btn-sm btn-outline-warning">
              <a href="%23login.html">Transfer</a>
            </button>

          </div>
        </div>

        <div class="tab-content">

          <div class="tab-pane fade show active" id="pills-trade-limit" role="tabpanel">
            <div class="outflexboxx d-flex justify-content-between">
              <div class="market-trade-buy">
                <div class="d-flex justify-content-between align-items-center">
                  <p class="w-full">
                    Balance:
                    <span><span id="base_coin">0</span>
                      USDT</span>
                    <span class="pull-right hide-mobile"><a id="deposit-href" href="Login/login-2.html" class="green"><i
                          class="fa fa-download"></i> Deposit</a></span>
                  </p>
                </div>
                <div class="input-group" id="buystop" style="display: none">
                  <input class="form-control" placeholder="Stop" type="number" id="buy_stop" name="stop" />
                  <div class="input-group-append">
                    <button style="min-width: 2.5rem" class="btn btn-increment hide-mobile" type="button">
                      <strong>+</strong>
                    </button>
                    <span class="input-group-text">USDT</span>
                  </div>
                </div>
                <div class="input-group" id="buypricebox">
                  <input class="form-control" placeholder="Price" type="number" id="buy_price" name="price" />
                  <div class="input-group-append">
                    <button style="min-width: 2.5rem" class="btn btn-increment hide-mobile" type="button">
                      <strong>+</strong>
                    </button>
                    <span class="input-group-text">USDT</span>
                  </div>
                </div>
                <div class="input-group">
                  <input class="form-control" placeholder="Qty" type="number" id="buy_num" name="num" />
                  <div class="input-group-append">
                    <button style="min-width: 2.5rem" class="btn btn-increment hide-mobile" type="button">
                      <strong>+</strong>
                    </button>
                    <span class="input-group-text">BTC</span>
                  </div>
                </div>
                <input type="text" name="buy_range" id="buy_range" />
                <ul class="market-trade-list">
                  <li>
                    <a href="#!" onclick="Percentage('25','buy')">25%</a>
                  </li>
                  <li>
                    <a href="#!" onclick="Percentage('50','buy')">50%</a>
                  </li>
                  <li>
                    <a href="#!" onclick="Percentage('75','buy')">75%</a>
                  </li>
                  <li>
                    <a href="#!" onclick="Percentage('100','buy')">100%</a>
                  </li>
                </ul>
                <div class="d-flex justify-content-between align-items-center">
                  <p class="m-b-0">
                    Total: <span id="buy_mum">--</span>
                  </p>
                  <p class="m-b-0">
                    Fee: <span id="buy_fees">0.1</span>%
                  </p>
                </div>
                <div id="stopbuybutton" style="display: none">
                  <button value="STOP BUY" onclick="stopadd_buy();" class="btn buy-trade">
                    STOP BUY </button>
                </div>
                <div id="limitbuybutton">
                  <button value="Buy" onclick="tradeadd_buy('limit');" class="btn buy-trade m-t-0">
                    Buy </button>
                </div>
                <div id="marketbuybutton" style="display: none">
                  <button value="Buy" onclick="tradeadd_buy('market');" class="btn buy-trade">
                    Buy </button>
                </div>
              </div>
              <div class="market-trade-sell">
                <div class="d-flex justify-content-between align-items-center">
                  <p class="w-full">
                    Balance:
                    <span><span id="user_coin">0</span>
                      BTC</span>

                    <span class="pull-right hide-mobile">
                      <a href="Login/login-2.html" class="red"><i class="fa fa-download"></i> Deposit</a>
                    </span>
                  </p>
                </div>
                <div class="input-group" id="sellstop" style="display: none">
                  <input class="form-control" placeholder="Stop" type="number" id="sell_stop" name="sellstop" />
                  <div class="input-group-append">
                    <button style="min-width: 2.5rem" class="btn btn-increment hide-mobile" type="button">
                      <strong>+</strong>
                    </button>
                    <span class="input-group-text">USDT</span>
                  </div>
                </div>
                <div class="input-group" id="sellpricebox">
                  <input class="form-control" placeholder="Price" type="number" id="sell_price" name="price" />
                  <div class="input-group-append">
                    <button style="min-width: 2.5rem" class="btn btn-increment hide-mobile" type="button">
                      <strong>+</strong>
                    </button>
                    <span class="input-group-text">USDT</span>
                  </div>
                </div>

                <div class="input-group">
                  <input class="form-control" placeholder="Qty" type="number" id="sell_num" name="num" />
                  <div class="input-group-append">
                    <button style="min-width: 2.5rem" class="btn btn-increment hide-mobile" type="button">
                      <strong>+</strong>
                    </button>
                    <span class="input-group-text">BTC</span>
                  </div>
                </div>
                <input type="text" name="sell_range" id="sell_range" />
                <ul class="market-trade-list">
                  <li>
                    <a href="#!" onclick="Percentage('25','sell')">25%</a>
                  </li>
                  <li>
                    <a href="#!" onclick="Percentage('50','sell')">50%</a>
                  </li>
                  <li>
                    <a href="#!" onclick="Percentage('75','sell')">75%</a>
                  </li>
                  <li>
                    <a href="#!" onclick="Percentage('100','sell')">100%</a>
                  </li>
                </ul>
                <div class="d-flex justify-content-between align-items-center">
                  <p class="m-b-0">
                    Total: <span id="sell_mum">--</span>
                  </p>
                  <p class="m-b-0">
                    Fee: <span id="sell_fees">0.1</span>%
                  </p>
                </div>
                <div id="stopsellbutton" style="display: none">
                  <button value="STOP SELL" onclick="stopadd_sell();" class="btn sell">
                    STOP SELL </button>
                </div>
                <div id="limitsellbutton">
                  <button onclick="tradeadd_sell('limit');" class="btn sell m-t-0">
                    Sell </button>
                </div>
                <div id="marketsellbutton" style="display: none">
                  <button onclick="tradeadd_sell('market');" class="btn sell">
                    Sell </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>





    </div>
    <div class="col-md-3">
      <div class="market-pairs " id="CryptoPriceTable">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroup-sizing-sm"><i class="icon ion-md-search"></i></span>
          </div>
          <input aria-describedby="inputGroup-sizing-sm" class="form-control" placeholder="Search" type="text"
            id="searchFilter" />
        </div>
        <ul class="nav nav-pills" role="tablist" id="crypt-tab">
          <li class="nav-item">
            <a aria-selected="true" class="nav-link" data-toggle="pill" href="#STAR" role="tab"><i
                class="icon ion-md-star"></i> Fav</a>
          </li>
          <li class="nav-item">
            <a aria-selected="true" class="nav-link" href="#USDT" data-toggle="pill" id="idusdt"
              data-target="#usdt">USDT</a>
          </li>
          <li class="nav-item">
            <a aria-selected="true" class="nav-link" href="#BTC" data-toggle="pill" id="idbtc"
              data-target="#btc">BTC</a>
          </li>
          <li class="nav-item">
            <a aria-selected="true" class="nav-link" href="#ETH" data-toggle="pill" id="ideth"
              data-target="#eth">ETH</a>
          </li>
          <li class="nav-item">
            <a aria-selected="true" class="nav-link" href="#EUR" data-toggle="pill" id="ideur"
              data-target="#eur">EUR</a>
          </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane fade show" id="STAR" role="tabpanel">
            <table class="table">
              <thead>
                <tr>
                  <th>
                    <span class="coin">Tokenler</span>
                  </th>
                  <th>
                    <span class="val">Fiyat</span>
                  </th>
                  <th>
                    <span class="degree">Degisiklik</span>
                  </th>
                </tr>
              </thead>
              <tbody class="white-bg" id="STAR-DATA">
                <tr>
                  <td>
                    <span class="coin"><i class="icon ion-md-star add-to-favorite"></i> ETH/BTC</span>
                  </td>
                  <td>
                    <span class="val">0.00020255</span>
                  </td>
                  <td class="red">
                    <span class="degree">-2.58%</span>
                  </td>
                </tr>
                <tr>
                  <td>
                    <span class="coin"><i class="icon ion-md-star add-to-favorite"></i> KCS/BTC</span>
                  </td>
                  <td>
                    <span class="val">0.00013192</span>
                  </td>
                  <td class="green">
                    <span class="degree">+5.6%</span>
                  </td>
                </tr>
                <tr>
                  <td>
                    <span class="coin"><i class="icon ion-md-star add-to-favorite"></i> ETH/BTC</span>
                  </td>
                  <td>
                    <span class="val">0.00020255</span>
                  </td>
                  <td class="red">
                    <span class="degree">-2.58%</span>
                  </td>
                </tr>
                <tr>
                  <td>
                    <span class="coin"><i class="icon ion-md-star add-to-favorite"></i> KCS/BTC</span>
                  </td>
                  <td>
                    <span class="val">0.00013192</span>
                  </td>
                  <td class="green">
                    <span class="degree">+5.6%</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="tab-pane" id="usdt" role="tabpanel">
            <table class="table">
              <thead>
                <tr class="d-flex w-100 justify-content-between">
                  <th scope="col">Market</th>
                  <th scope="col">Price</th>
                  <th scope="col">Change</th>
                </tr>
              </thead>
              <tbody id="coinleftmenu-usdt"></tbody>
            </table>
          </div>
          <div class="tab-pane" id="btc" role="tabpanel">
            <table class="table">
              <thead>
                <tr class="d-flex w-100 justify-content-between">
                  <th scope="col">Market</th>
                  <th scope="col">Price</th>
                  <th scope="col">Change</th>
                </tr>
              </thead>
              <tbody id="coinleftmenu-btc"></tbody>
            </table>
          </div>
          <div class="tab-pane" id="eth" role="tabpanel">
            <table class="table">
              <thead>
                <tr class="d-flex w-100 justify-content-between">
                  <th scope="col">Market</th>
                  <th scope="col">Price</th>
                  <th scope="col">Change</th>
                </tr>
              </thead>
              <tbody id="coinleftmenu-eth"></tbody>
            </table>
          </div>
          <div class="tab-pane" id="eur" role="tabpanel">
            <table class="table">
              <thead>
                <tr class="d-flex w-100 justify-content-between">
                  <th scope="col">Market</th>
                  <th scope="col">Price</th>
                  <th scope="col">Change</th>
                </tr>
              </thead>
              <tbody id="coinleftmenu-eur"></tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="market-history">
        <ul class="nav nav-pills" role="tablist">
          <li class="nav-item">
            <a aria-selected="true" class="nav-link active" data-toggle="pill" href="#latest-trades" role="tab">Recent
              Trades</a>
          </li>
        </ul>
        <div class="tab-content" id="DealRecordTable">
          <div class="tab-pane fade show active" id="dealrecords" role="tabpanel">
            <table class="table">
              <thead>
                <tr class="row">
                  <th scope="col" class="col-4">
                    <span class="time">Time</span>
                  </th>
                  <th scope="col" class="col-4">
                    <span class="price">Price</span>
                  </th>
                  <th scope="col" class="col-4">
                    <span class="quantity">Volume</span>
                  </th>
                </tr>
              </thead>
              <tbody id="dealrecordbody"></tbody>
            </table>
          </div>
        </div>
      </div>
      <style>
        div#dealrecords td {
          padding: 4px 14px !important;
        }
      </style>

    </div>
    <div class="col-md-12">
      <div class="recent-history market-order card mt-3">
        <div class="header d-flex justify-content-between align-items-center white-bg">
          <ul class="nav nav-pills" role="tablist">
            <li class="nav-item">
              <a aria-selected="true" class="nav-link active" data-toggle="pill" href="#newrecent-trades"
                role="tab">Recent Trades</a>
            </li>
            <li class="nav-item">
              <a aria-selected="false" class="nav-link" data-toggle="pill" href="#closed-orders" role="tab">My
                Orders</a>
            </li>
            <li class="nav-item">
              <a aria-selected="false" class="nav-link" data-toggle="pill" href="#opened-orders" role="tab">Active
                Order</a>
            </li>

          </ul>
          <ul class="list-inline m-r-15 f-s-12 filterMenu">

            <li class="list-inline-item active all-orders"><a href="Login/login-2.html">All</a></li>
          </ul>
        </div>
        <div class="tab-content">
          <div class="tab-pane fade show active" id="newrecent-trades" role="tabpanel">
            <div class="d-flex justify-content-between market-order-item table-responsive white-bg hello-xqf">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Time</th>
                    <th scope="col">Type</th>
                    <th scope="col">Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Total</th>
                  </tr>
                </thead>
                <tbody id="recent-orders-list">
                  <tr>
                    <td colspan="5"><span class="no-data">
                        <i class="icon ion-md-document"></i>
                        No Data</td>
                  </tr>
                </tbody>
              </table>

            </div>

          </div>
          <div class="tab-pane fade" id="opened-orders" role="tabpanel">
            <div class="d-flex justify-content-between table-responsive white-bg market-order-item">

              <table class="table " id="entrust_over">
                <thead>
                  <tr>
                    <th scope="col">Market</th>
                    <th scope="col">Time</th>
                    <th scope="col">Type</th>
                    <th scope="col">Price</th>
                    <th scope="col">Stop</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Dealt</th>
                    <th scope="col">Total</th>

                    <th scope="col">Option</th>

                  </tr>
                </thead>
                <tbody id="entrustlist">
                  <span class="no-data" id="loginrequired">
                    <i class="icon ion-md-person"></i>
                    <span class="login-menu">
                      <a class="cd-signin" href="#0">Login</a> or <a class="cd-signup" href="#0">Signup</a>
                    </span>
                  </span>
                </tbody>
              </table>
            </div>

          </div>
          <div class="tab-pane fade" id="closed-orders" role="tabpanel">
            <div class="d-flex justify-content-between table-responsive white-bg market-order-item">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Time</th>
                    <th scope="col">Type</th>
                    <th scope="col">Price</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Total</th>
                  </tr>
                </thead>
                <tbody id="closedorderslist">
                  <span class="no-data">
                    <i class="icon ion-md-person"></i>
                    <span class="login-menu">
                      <a class="cd-signin" href="#0">Login</a> or <a class="cd-signup" href="#0">Signup</a>
                    </span>
                  </span>
                </tbody>
              </table>
            </div>
          </div>


        </div>
      </div>
    </div>
  </div>
  <div class="row sm-gutters sm_screen">
    <div class="col-md-12 middd_stats_s1">
      <div class="exchange-headline white-bg">
        <div class="headline-left col-11 p-0">
          <div class="title-big">
            BTC / USDT <i class="icon ion-md-star add-to-favorite default"></i>
          </div>
          <div class="headline-item instant-status">
            <div>
              <span class="title" id="market_sell_price"></span>
              <span class="green" id="market_change">-</span>
            </div>
            <div>
              <span>USDT</span> <span id="market_buy_price"></span>
            </div>
          </div>
          <div class="headline-item is-hh">
            <div>24H H</div>
            <div class="title green" id="market_max_price"></div>
          </div>
          <div class="headline-item is-hl">
            <div>24H L</div>
            <div class="title red" id="market_min_price"></div>
          </div>
          <div class="headline-item is-hv">
            <div>24H Volume</div>
            <div class="title" id="market_volume"></div>
          </div>
        </div>
        <div class="headline-right col-1 p-0 ml-auto">
          <a href="#!" class="btn changeThemeLight"><i class="icon ion-md-moon"></i></a>
        </div>
      </div>

    </div>

    @include('trading.fav')

    @include('trading.chartmobile')

    <div class="col-md-12 buysell_s4">
      <div class="market-trade">
        <div class="d-flex justify-content-between align-items-center">
          <ul class="nav nav-pills" role="tablist">
            <li class="nav-item">
              <span class="nav-link active" onclick="OrderType('limit')">Limit</span>
            </li>
            <li class="nav-item">
              <span class="nav-link active" onclick="OrderType('market')">Market</span>
            </li>
            <li class="nav-item">
              <span class="nav-link active" onclick="OrderType('stop')">Stop-Limit</span>
            </li>
          </ul>
          <div class="hide-mobile">
            <button class="btn btn-sm btn-outline-warning">
              <a href="%23login.html">Transfer</a>
            </button>

          </div>
        </div>

        <div class="tab-content">

          <div class="tab-pane fade show active" id="pills-trade-limit" role="tabpanel">
            <div class="outflexboxx d-flex justify-content-between">
              <div class="market-trade-buy">
                <div class="d-flex justify-content-between align-items-center">
                  <p class="w-full">
                    Balance:
                    <span><span id="base_coin">0</span>
                      USDT</span>
                    <span class="pull-right hide-mobile"><a id="deposit-href" href="Login/login-2.html" class="green"><i
                          class="fa fa-download"></i> Deposit</a></span>
                  </p>
                </div>
                <div class="input-group" id="buystop" style="display: none">
                  <input class="form-control" placeholder="Stop" type="number" id="buy_stop" name="stop" />
                  <div class="input-group-append">
                    <button style="min-width: 2.5rem" class="btn btn-increment hide-mobile" type="button">
                      <strong>+</strong>
                    </button>
                    <span class="input-group-text">USDT</span>
                  </div>
                </div>
                <div class="input-group" id="buypricebox">
                  <input class="form-control" placeholder="Price" type="number" id="buy_price" name="price" />
                  <div class="input-group-append">
                    <button style="min-width: 2.5rem" class="btn btn-increment hide-mobile" type="button">
                      <strong>+</strong>
                    </button>
                    <span class="input-group-text">USDT</span>
                  </div>
                </div>
                <div class="input-group">
                  <input class="form-control" placeholder="Qty" type="number" id="buy_num" name="num" />
                  <div class="input-group-append">
                    <button style="min-width: 2.5rem" class="btn btn-increment hide-mobile" type="button">
                      <strong>+</strong>
                    </button>
                    <span class="input-group-text">BTC</span>
                  </div>
                </div>
                <input type="text" name="buy_range" id="buy_range" />
                <ul class="market-trade-list">
                  <li>
                    <a href="#!" onclick="Percentage('25','buy')">25%</a>
                  </li>
                  <li>
                    <a href="#!" onclick="Percentage('50','buy')">50%</a>
                  </li>
                  <li>
                    <a href="#!" onclick="Percentage('75','buy')">75%</a>
                  </li>
                  <li>
                    <a href="#!" onclick="Percentage('100','buy')">100%</a>
                  </li>
                </ul>
                <div class="d-flex justify-content-between align-items-center">
                  <p class="m-b-0">
                    Total: <span id="buy_mum">--</span>
                  </p>
                  <p class="m-b-0">
                    Fee: <span id="buy_fees">0.1</span>%
                  </p>
                </div>
                <div id="stopbuybutton" style="display: none">
                  <button value="STOP BUY" onclick="stopadd_buy();" class="btn buy-trade">
                    STOP BUY </button>
                </div>
                <div id="limitbuybutton">
                  <button value="Buy" onclick="tradeadd_buy('limit');" class="btn buy-trade m-t-0">
                    Buy </button>
                </div>
                <div id="marketbuybutton" style="display: none">
                  <button value="Buy" onclick="tradeadd_buy('market');" class="btn buy-trade">
                    Buy </button>
                </div>
              </div>
              <div class="market-trade-sell">
                <div class="d-flex justify-content-between align-items-center">
                  <p class="w-full">
                    Balance:
                    <span><span id="user_coin">0</span>
                      BTC</span>

                    <span class="pull-right hide-mobile">
                      <a href="Login/login-2.html" class="red"><i class="fa fa-download"></i> Deposit</a>
                    </span>
                  </p>
                </div>
                <div class="input-group" id="sellstop" style="display: none">
                  <input class="form-control" placeholder="Stop" type="number" id="sell_stop" name="sellstop" />
                  <div class="input-group-append">
                    <button style="min-width: 2.5rem" class="btn btn-increment hide-mobile" type="button">
                      <strong>+</strong>
                    </button>
                    <span class="input-group-text">USDT</span>
                  </div>
                </div>
                <div class="input-group" id="sellpricebox">
                  <input class="form-control" placeholder="Price" type="number" id="sell_price" name="price" />
                  <div class="input-group-append">
                    <button style="min-width: 2.5rem" class="btn btn-increment hide-mobile" type="button">
                      <strong>+</strong>
                    </button>
                    <span class="input-group-text">USDT</span>
                  </div>
                </div>

                <div class="input-group">
                  <input class="form-control" placeholder="Qty" type="number" id="sell_num" name="num" />
                  <div class="input-group-append">
                    <button style="min-width: 2.5rem" class="btn btn-increment hide-mobile" type="button">
                      <strong>+</strong>
                    </button>
                    <span class="input-group-text">BTC</span>
                  </div>
                </div>
                <input type="text" name="sell_range" id="sell_range" />
                <ul class="market-trade-list">
                  <li>
                    <a href="#!" onclick="Percentage('25','sell')">25%</a>
                  </li>
                  <li>
                    <a href="#!" onclick="Percentage('50','sell')">50%</a>
                  </li>
                  <li>
                    <a href="#!" onclick="Percentage('75','sell')">75%</a>
                  </li>
                  <li>
                    <a href="#!" onclick="Percentage('100','sell')">100%</a>
                  </li>
                </ul>
                <div class="d-flex justify-content-between align-items-center">
                  <p class="m-b-0">
                    Total: <span id="sell_mum">--</span>
                  </p>
                  <p class="m-b-0">
                    Fee: <span id="sell_fees">0.1</span>%
                  </p>
                </div>
                <div id="stopsellbutton" style="display: none">
                  <button value="STOP SELL" onclick="stopadd_sell();" class="btn sell">
                    STOP SELL </button>
                </div>
                <div id="limitsellbutton">
                  <button onclick="tradeadd_sell('limit');" class="btn sell m-t-0">
                    Sell </button>
                </div>
                <div id="marketsellbutton" style="display: none">
                  <button onclick="tradeadd_sell('market');" class="btn sell">
                    Sell </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>





    </div>
    <div class="col-md-12 tradehistory_s5">
      <div class="recent-history market-order card mt-3">
        <div class="header d-flex justify-content-between align-items-center white-bg">
          <ul class="nav nav-pills" role="tablist">
            <li class="nav-item">
              <a aria-selected="true" class="nav-link active" data-toggle="pill" href="#newrecent-trades"
                role="tab">Recent Trades</a>
            </li>
            <li class="nav-item">
              <a aria-selected="false" class="nav-link" data-toggle="pill" href="#closed-orders" role="tab">My
                Orders</a>
            </li>
            <li class="nav-item">
              <a aria-selected="false" class="nav-link" data-toggle="pill" href="#opened-orders" role="tab">Active
                Order</a>
            </li>

          </ul>
          <ul class="list-inline m-r-15 f-s-12 filterMenu">

            <li class="list-inline-item active all-orders"><a href="Login/login-2.html">All</a></li>
          </ul>
        </div>
        <div class="tab-content">
          <div class="tab-pane fade show active" id="newrecent-trades" role="tabpanel">
            <div class="d-flex justify-content-between market-order-item table-responsive white-bg hello-xqf">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Time</th>
                    <th scope="col">Type</th>
                    <th scope="col">Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Total</th>
                  </tr>
                </thead>
                <tbody id="recent-orders-list">
                  <tr>
                    <td colspan="5"><span class="no-data">
                        <i class="icon ion-md-document"></i>
                        No Data</td>
                  </tr>
                </tbody>
              </table>

            </div>

          </div>
          <div class="tab-pane fade" id="opened-orders" role="tabpanel">
            <div class="d-flex justify-content-between table-responsive white-bg market-order-item">

              <table class="table " id="entrust_over">
                <thead>
                  <tr>
                    <th scope="col">Market</th>
                    <th scope="col">Time</th>
                    <th scope="col">Type</th>
                    <th scope="col">Price</th>
                    <th scope="col">Stop</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Dealt</th>
                    <th scope="col">Total</th>

                    <th scope="col">Option</th>

                  </tr>
                </thead>
                <tbody id="entrustlist">
                  <span class="no-data" id="loginrequired">
                    <i class="icon ion-md-person"></i>
                    <span class="login-menu">
                      <a class="cd-signin" href="#0">Login</a> or <a class="cd-signup" href="#0">Signup</a>
                    </span>
                  </span>
                </tbody>
              </table>
            </div>

          </div>
          <div class="tab-pane fade" id="closed-orders" role="tabpanel">
            <div class="d-flex justify-content-between table-responsive white-bg market-order-item">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Time</th>
                    <th scope="col">Type</th>
                    <th scope="col">Price</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Total</th>
                  </tr>
                </thead>
                <tbody id="closedorderslist">
                  <span class="no-data">
                    <i class="icon ion-md-person"></i>
                    <span class="login-menu">
                      <a class="cd-signin" href="#0">Login</a> or <a class="cd-signup" href="#0">Signup</a>
                    </span>
                  </span>
                </tbody>
              </table>
            </div>
          </div>


        </div>
      </div>
    </div>
    <div class="col-md-12 order_s6">
      <!-- Order Book Start -->
      <div class="order-book mb15">
        <h2 class="heading white-bg">Order Book</h2>
        <div class="action-tools">
          <div class="filter-buttons">
            <button data-bn-type="button" id="defaultModeButton" class="active">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M11 21v-8H3v8h8zm-6-2v-4h4v4H5z" fill="#2EBD85"></path>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M11 11V3H3v8h8zM5 9V5h4v4H5z" fill="#F6465D"></path>
                <path fill="currentColor"
                  d="M13 3h8v2h-8zM13 19h8v2h-8zM13 11h8v2h-8v-2zM13 7h8v2h-8V7zM13 15h8v2h-8v-2z"></path>
              </svg>
            </button>
            <button data-bn-type="button" id="buyModeButton" class="">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M11 21V3H3v18h8zm-6-2V5h4v14H5z" fill="#2EBD85"></path>
                <path fill="currentColor"
                  d="M13 3h8v2h-8zM13 19h8v2h-8zM13 11h8v2h-8v-2zM13 7h8v2h-8V7zM13 15h8v2h-8v-2z"></path>
              </svg>
            </button>
            <button data-bn-type="button" id="sellModeButton" class="">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M11 21V3H3v18h8zm-6-2V5h4v14H5z" fill="#F6465D"></path>
                <path fill="currentColor"
                  d="M13 3h8v2h-8zM13 19h8v2h-8zM13 11h8v2h-8v-2zM13 7h8v2h-8V7zM13 15h8v2h-8v-2z"></path>
              </svg>
            </button>
          </div>
          <div class="order-book-drop">
            <select class="form-control" data-width="100%" id="decimalplaces">
              <option value="8">0.00000001</option>
              <option value="7">0.0000001</option>
              <option value="6">0.0000001</option>
              <option value="5">0.000001</option>
              <option value="4">0.00001</option>
              <option value="3">0.0001</option>
            </select>
          </div>
        </div>
        <div id="bsorders">
          <table class="table buysellorders">
            <thead>
              <tr class="d-flex w-100 justify-content-between">
                <th scope="col">Price (USDT)</th>
                <th scope="col">Amount</th>
                <th scope="col">Total (USDT)</th>
              </tr>
            </thead>
            <tbody class="selling sellMobtbody" id="sellorderlist">
            </tbody>
            <tbody class="ob-heading">

              <td class="contractPrice col-4">
                <span id="activexnb">--</span>
              </td>
              <td class="marketPrice col-4">
                <span style="margin-right: 4px;"></span> <span id="activesign" class="OK"></span>
              </td>
              <td class="red rate col-4">
                <span id="activermb" class="OK">--</span>
              </td>
              </tr>
            </tbody>
            <tbody class="buying buyMobtbody" id="buyorderlist">
            </tbody>
          </table>
        </div>
      </div>
      <!-- Order Book End -->

    </div>
  </div>
</div>
<input class="hide" style="display:none" id="socket_data" value="0">

<script>
document.getElementById("decimalplaces").addEventListener("change", function () {
    let decimals = this.value;

    [...document.querySelectorAll("#sellorderlist tr, #buyorderlist tr")].forEach(row => {
        let priceCell = row.children[0];
        let amountCell = row.children[1];
        let totalCell = row.children[2];

        let price = parseFloat(priceCell.innerText);
        let amount = parseFloat(amountCell.innerText);
        let total = price * amount;

        priceCell.innerText = price.toFixed(decimals);
        amountCell.innerText = amount.toFixed(6);
        totalCell.innerText = total.toFixed(decimals);
    });
});
</script>


<script>
const pair = "{{ $tradingPair->symbol }}".replace("/", "").toLowerCase();

let ws = new WebSocket(`wss://stream.binance.com:9443/ws/${pair}@depth`);

ws.onmessage = function(event) {
    let data = JSON.parse(event.data);

    updateOrderBookSide(data.b, 'buy');  // bids
    updateOrderBookSide(data.a, 'sell'); // asks
};

function updateOrderBookSide(orders, side) {
    let listId = side === 'buy' ? '#buyorderlist' : '#sellorderlist';

    // Keep only local rows
    let localRows = document.querySelectorAll(`${listId} tr.local-order`);

    // Remove non-local rows
    document.querySelectorAll(`${listId} tr.binance-row`).forEach(e => e.remove());

    orders.slice(0, 20).forEach(order => {
        let price = parseFloat(order[0]);
        let amount = parseFloat(order[1]);
        if (!amount || !price) return;

        // Skip if local row exists at this price
        let exists = [...localRows].some(row =>
            Number(row.dataset.price) === price
        );
        if (exists) return;

        let total = (price * amount).toFixed(6);

        let tr = document.createElement('tr');
        tr.className = `${side}-row binance-row`;
        tr.innerHTML = `
            <td class="${side === 'buy' ? 'text-success' : 'text-danger'} price">${price.toFixed(8)}</td>
            <td class="amount">${amount.toFixed(6)}</td>
            <td class="total">${total}</td>`;
        document.querySelector(listId).appendChild(tr);
    });
}
</script>


@endsection