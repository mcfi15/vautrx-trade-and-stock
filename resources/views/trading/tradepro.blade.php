@extends('layouts.app')

@section('title', 'Select Cryptocurrency for Deposit')

@section('content')

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
    $('.mob_scr_orders').find('.sellMobtbody').attr('id', 'sellorderlist1');
    $('.mob_scr_orders').find('.buyMobtbody').attr('id', 'buyorderlist1');
    $('.mob_scr_trades').find('.tradeMobtbody').attr('id', 'dealrecordbody1');

    $('.customm_tabs ul li').on('click', function () {
      $('.customm_tabs ul li').removeClass('active');
      $(this).addClass('active');
      let attr = $(this).attr('attr');
      $('.mob_scr_orders').hide();
      $('.mob_scr_chart').hide();
      $('.mob_scr_trades').hide();
      $('.mob_scr_' + attr).show();
    });
    $('.market-order ul li').on('click', function () {
      if (window.innerWidth <= 768) {
        $('.market-order ul li a').removeClass('active');
        $('.market-order ul li a').attr('aria-selected', 'false');
        $(this).find('a').addClass('active');
        $(this).find('a').attr('aria-selected', 'true');
        if ($(this).find('a').attr('href') == '#closed-orders') {
          $('#opened-orders').removeClass('active show');
          $('#closed-orders').addClass('active show');
        } else {
          $('#closed-orders').removeClass('active show');
          $('#opened-orders').addClass('active show');
        }
      }
    });
    if (window.innerWidth <= 768) {
      $('.market-order ul li:nth-child(2) a').addClass('active');
      $('.market-order ul li:nth-child(2) a').attr('aria-selected', 'true');
      $('#closed-orders').addClass('active show');

      $('.mob_scr_orders').find('.sellMobtbody').attr('id', 'sellorderlist');
      $('.mob_scr_orders').find('.buyMobtbody').attr('id', 'buyorderlist');
      $('.mob_scr_trades').find('.tradeMobtbody').attr('id', 'dealrecordbody');
    }
  });
</script>
<style>
  #sellorderlist,
  #buyorderlist {
    min-height: 326px;
  }

  body,
  html {
    overflow-x: hidden;
  }

  .customm_tabs,
  .mob_scr_trades,
  .mob_scr_orders {
    display: none;
  }

  .order-book tbody tr {
    flex-shrink: 0;
  }

  @media only screen and (max-width: 768px) {
    .mobscrno {
      display: none;
    }

    .customm_tabs {
      display: block;
    }

    .customm_tabs ul {
      display: flex;
      background: black;
      padding: 5px 12px;
      font-size: 16px;
    }

    .customm_tabs ul li.active {
      color: white;
      border-bottom: 2px solid;
    }

    .customm_tabs ul li {
      cursor: pointer;
      margin-right: 20px;
    }

    .market-order ul li:first-child,
    .market-order .tab-content .tab-pane:first-child {
      display: none;
    }
  }
</style>
<div class="trade trade-pro common-bg">
  <div class="container-fluid no-fluid p-0">
    <div class="row no-gutters">
      <div class="col-lg-8 col-xl-9">
        <div class="row no-gutters">
          <div class="col-xl-9">
            <div class="exchange-headline white-bg">
              <div class="headline-left col-11 p-0">
                <div class="title-big">
                  BTC / USDT <i class="fa fa-chevron-down collapse-btn"></i>
                  <div class="col market-pairs" id="market-pairs-collapse">
                    <div id="CryptoPriceTable">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="inputGroup-sizing-sm"><i
                              class="icon ion-md-search"></i></span>
                        </div>
                        <input aria-describedby="inputGroup-sizing-sm" class="form-control" placeholder="Search"
                          type="text" id="searchFilter" />
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

                  </div>


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

            <div class="customm_tabs">
              <ul>
                <li class="active" attr="chart">Chart</li>
                <li attr="orders">Orders</li>
                <li attr="trades">Trades</li>
              </ul>
            </div>
            <div class="tab-content mob_scr_chart" id="v-pills-tabContent">
              <div class="tab-pane fade show active" id="v-pills-tradingview" role="tabpanel"
                aria-labelledby="v-pills-tradingview-tab">
                <div class="main-chart">
                  <!-- TradingView Widget Start -->
                  <div class="tradingview-widget-container">
                    <div class="  ">
                      <style>
                        .noWrapWrapper-1WIwNaDF {
                          display: none !important;
                        }
                      </style>
                      <div class="main-chart mb15">
                        <!-- TradingView Widget Start -->
                        <div class="tradingview-widget-container">
                          <div id="tradingview_e8053"></div>
                          <script src="{{ asset('Public/s3.tradingview.com/tv.js') }}"></script>
                          <script>

                            var chartParams = {
                              "width": "100%",
                              "height": 600,
                              "symbol": "BINANCE:BTCUSDT",
                              "interval": "D",
                              "timezone": "Etc/UTC",
                              "theme": 'dark',
                              "style": "1",
                              "locale": "en",
                              "toolbar_bg": "#f1f3f6",
                              "enable_publishing": false,
                              "withdateranges": true,
                              "hide_side_toolbar": false,
                              "allow_symbol_change": false,
                              "show_popup_button": true,
                              "popup_width": "1000",
                              "popup_height": "650",
                              "hide_legend": true,
                              "container_id": "tradingview_e8053"

                            }
                            new TradingView.widget(chartParams);
                          </script>
                        </div>
                        <!-- TradingView Widget End -->

                      </div>

                    </div>
                  </div>
                  <!-- TradingView Widget End -->
                </div>
              </div>
              <div class="tab-pane fade" id="v-pills-deepchart" role="tabpanel" aria-labelledby="v-pills-deepchart-tab">
                <div id="depthchart" class="depthchart ">
                  <img src="../Public/template/epsilon/img/deepchart.jpg" class="img-fluid m-auto">
                </div>
              </div>
            </div>

            <div class="mob_scr_orders">
              <!-- Order Book Start -->
              <div class="order-book mb15">
                <h2 class="heading white-bg">Order Book</h2>
                <div class="action-tools">
                  <div class="filter-buttons">
                    <button data-bn-type="button" id="defaultModeButton" class="active">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M11 21v-8H3v8h8zm-6-2v-4h4v4H5z"
                          fill="#2EBD85"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M11 11V3H3v8h8zM5 9V5h4v4H5z" fill="#F6465D">
                        </path>
                        <path fill="currentColor"
                          d="M13 3h8v2h-8zM13 19h8v2h-8zM13 11h8v2h-8v-2zM13 7h8v2h-8V7zM13 15h8v2h-8v-2z"></path>
                      </svg>
                    </button>
                    <button data-bn-type="button" id="buyModeButton" class="">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M11 21V3H3v18h8zm-6-2V5h4v14H5z"
                          fill="#2EBD85"></path>
                        <path fill="currentColor"
                          d="M13 3h8v2h-8zM13 19h8v2h-8zM13 11h8v2h-8v-2zM13 7h8v2h-8V7zM13 15h8v2h-8v-2z"></path>
                      </svg>
                    </button>
                    <button data-bn-type="button" id="sellModeButton" class="">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M11 21V3H3v18h8zm-6-2V5h4v14H5z"
                          fill="#F6465D"></path>
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
            <div class="mob_scr_trades">
              <div class="market-history">
                <ul class="nav nav-pills" role="tablist">
                  <li class="nav-item">
                    <a aria-selected="true" class="nav-link active" data-toggle="pill" href="#latest-trades"
                      role="tab">Recent Trades</a>
                  </li>
                </ul>
                <div class="tab-content" id="DealRecordTable">
                  <div class="tab-pane fade show active" id="dealrecords" role="tabpanel">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>
                            <span class="time text-left">Time</span>
                          </th>
                          <th>
                            <span class="price">Price</span>
                          </th>
                          <th>
                            <span class="quantity   text-right">Volume</span>
                          </th>
                        </tr>
                      </thead>
                      <tbody class="tradeMobtbody" id="dealrecordbody"></tbody>
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
            <div class="p-0 order-history market-order">
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
                      <a aria-selected="false" class="nav-link" data-toggle="pill" href="#opened-orders"
                        role="tab">Active Order</a>
                    </li>

                  </ul>
                  <ul class="list-inline m-r-15 f-s-12 filterMenu">

                    <li class="list-inline-item active all-orders"><a href="../Login/login-2.html">All</a></li>
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
          <div class="col-xl-3 mobscrno">
            <!-- Order Book Start -->
            <!-- Order Book Start -->
            <div class="order-book mb15">
              <h2 class="heading white-bg">Order Book</h2>
              <div class="action-tools">
                <div class="filter-buttons">
                  <button data-bn-type="button" id="defaultModeButton" class="active">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M11 21v-8H3v8h8zm-6-2v-4h4v4H5z" fill="#2EBD85">
                      </path>
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M11 11V3H3v8h8zM5 9V5h4v4H5z" fill="#F6465D">
                      </path>
                      <path fill="currentColor"
                        d="M13 3h8v2h-8zM13 19h8v2h-8zM13 11h8v2h-8v-2zM13 7h8v2h-8V7zM13 15h8v2h-8v-2z"></path>
                    </svg>
                  </button>
                  <button data-bn-type="button" id="buyModeButton" class="">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M11 21V3H3v18h8zm-6-2V5h4v14H5z" fill="#2EBD85">
                      </path>
                      <path fill="currentColor"
                        d="M13 3h8v2h-8zM13 19h8v2h-8zM13 11h8v2h-8v-2zM13 7h8v2h-8V7zM13 15h8v2h-8v-2z"></path>
                    </svg>
                  </button>
                  <button data-bn-type="button" id="sellModeButton" class="">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M11 21V3H3v18h8zm-6-2V5h4v14H5z" fill="#F6465D">
                      </path>
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


            <!-- Order Book End -->
            <div class="market-history">
              <ul class="nav nav-pills" role="tablist">
                <li class="nav-item">
                  <a aria-selected="true" class="nav-link active" data-toggle="pill" href="#latest-trades"
                    role="tab">Recent Trades</a>
                </li>
              </ul>
              <div class="tab-content" id="DealRecordTable">
                <div class="tab-pane fade show active" id="dealrecords" role="tabpanel">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>
                          <span class="time text-left">Time</span>
                        </th>
                        <th>
                          <span class="price">Price</span>
                        </th>
                        <th>
                          <span class="quantity   text-right">Volume</span>
                        </th>
                      </tr>
                    </thead>
                    <tbody class="tradeMobtbody" id="dealrecordbody"></tbody>
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
        </div>
      </div>
      <div class="col-lg-4 col-xl-3">
        <div class="market-trade">
          <div class="col m-b-15">
            <div class="money-actions d-flex align-items-center justify-content-around">
              <a href="../%23login.html" class="yellow"><i class="fa fa-exchange"></i>Transfer</a>


              <a href="../Login/login-2.html" class="green"><i class="fa fa-download"></i> Deposit</a>
              <a href="../Wallet/cryptowithdrawl/coin/usdt.html" class="red"><i class="fa fa-upload"></i> Withdraw</a>

            </div>
          </div>
          <div class="col">
            <ul class="nav nav-pills nav-fill buy-sell-tab" role="tablist">
              <li class="nav-item">
                <a aria-selected="true" class="nav-link buy active" data-toggle="pill" href="#pills-trade-buy"
                  role="tab">Buy</a>
              </li>
              <li class="nav-item">
                <a aria-selected="false" class="nav-link sell" data-toggle="pill" href="#pills-trade-sell"
                  role="tab">Sell</a>
              </li>
            </ul>
          </div>
          <div class="col">
            <div class="tab-content">
              <div class="tab-pane fade show active" id="pills-trade-buy" role="tabpanel">
                <ul class="nav nav-pills" role="tablist">
                  <li class="nav-item">
                    <span class="nav-link active" onclick="OrderType('limit')" id="ltbutton">Limit</span>
                  </li>
                  <li class="nav-item">

                    <span class="nav-link" onclick="OrderType('market')" id="mtbutton">Market</span>
                  </li>
                  <li class="nav-item">
                    <span class="nav-link" onclick="OrderType('stop')" id="stbutton">Stop-Limit</span>
                  </li>

                </ul>
                <div class="d-flex justify-content-between align-items-center">
                  <p>
                    Balance:: <span><span id="base_coin">0</span>
                      USDT</span>
                  </p>
                </div>
                <div class="tab-content">
                  <div class="tab-pane fade show active" id="pills-trade-limit" role="tabpanel">
                    <div class="trade-content d-flex justify-content-between">
                      <div class="market-trade-buy">
                        <div class="input-group quantity" id="buystop" style="display: none">
                          <input class="form-control" placeholder="Stop" type="number" id="buy_stop" name="stop" />
                          <div class="input-group-append">
                            <button style="min-width: 2.5rem" class="btn btn-increment hide-mobile" type="button">
                              <strong>+</strong>
                            </button>
                            <span class="input-group-text">USDT</span>
                          </div>
                        </div>
                        <div class="input-group quantity" id="buypricebox">
                          <input class="form-control" placeholder="Price" type="number" id="buy_price" name="price" />
                          <div class="input-group-append">
                            <button style="min-width: 2.5rem" class="btn btn-increment hide-mobile" type="button">
                              <strong>+</strong>
                            </button>
                            <span class="input-group-text">USDT</span>
                          </div>
                        </div>
                        <div class="input-group quantity">
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

                      </div>
                    </div>
                  </div>
                </div>

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
                    BTC Buy </button>
                </div>
                <div id="marketbuybutton" style="display: none">
                  <button value="Buy" onclick="tradeadd_buy('market');" class="btn buy-trade">
                    BTC Buy </button>
                </div>
              </div>
              <div class="tab-pane fade" id="pills-trade-sell" role="tabpanel">
                <ul class="nav nav-pills" role="tablist">
                  <li class="nav-item">
                    <span class="nav-link active" id="ltbutton" onclick="OrderType('limit')">Limit</span>
                  </li>
                  <li class="nav-item">

                    <span class="nav-link" id="mtbutton" onclick="OrderType('market')">Market</span>
                  </li>
                  <li class="nav-item">
                    <span class="nav-link" id="stbutton" onclick="OrderType('stop')">Stop-Limit</span>
                  </li>

                </ul>
                <div class="d-flex justify-content-between align-items-center m-b-10">
                  <p>
                    Balance:
                    <span><span id="user_coin">0</span>
                      BTC</span>
                  </p>
                </div>
                <div class="tab-content">
                  <div class="tab-pane fade show active" id="pills-trade-limit" role="tabpanel">
                    <div class="trade-content d-flex justify-content-between">
                      <div class="market-trade-sell">
                        <div class="input-group quantity" id="sellstop" style="display: none">
                          <input class="form-control" placeholder="Stop" type="number" id="sell_stop" name="sellstop" />
                          <div class="input-group-append">
                            <button style="min-width: 2.5rem" class="btn btn-increment hide-mobile" type="button">
                              <strong>+</strong>
                            </button>
                            <span class="input-group-text">USDT</span>
                          </div>
                        </div>
                        <div class="input-group quantity" id="sellpricebox">
                          <input class="form-control" placeholder="Price" type="number" id="sell_price" name="price" />
                          <div class="input-group-append">
                            <button style="min-width: 2.5rem" class="btn btn-increment hide-mobile" type="button">
                              <strong>+</strong>
                            </button>
                            <span class="input-group-text">USDT</span>
                          </div>
                        </div>

                        <div class="input-group quantity">
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
                      </div>
                    </div>
                  </div>

                </div>
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
  </div>
</div>
<!--currency modal end -->

<input class="hide" style="display:none" id="socket_data" value="0">
<script>
  const websocketId = Math.floor(Math.random() * 1e12);
  ws = new WebSocket("wss://stream.binance.com:443/ws");

  ws.onopen = function (e) {
    var msg = {
      "method": "SUBSCRIBE",
      "params": [
        "btcusdt@depth@1000ms",
        "btcusdt@kline_1s",
        "btcusdt@trade",
      ],
      "id": websocketId
    }
    ws.send(JSON.stringify(msg));
  }
  ws.onmessage = function (e) {
    data = JSON.parse(e.data);

    switch (data.e) {
      case 'depthUpdate':
        if (data.s == 'btcusdt'.toUpperCase()) {
          ws_depth(data)
          break
        }
      case 'kline':
        if (data.s == 'btcusdt'.toUpperCase()) {
          ws_newprice(data.k)
          break
        }
      case 'trade':
        if (data.s == 'btcusdt'.toUpperCase()) {
          ws_tradelog(data)
          break
        }
      default:
        console.log(data);
    }
  };

  function ws_tradelog(data) {
    elementupdate("#activexnb", Number(data.p).toFixed(2));
    elementupdate("#market_sell_price", Number(data.p).toFixed(2));
    if (!data.m) {
      var typeclass = 'green';
      var ttype = "Buy";
      $("#activesign").html("↑");
    } else {
      var typeclass = 'red';
      var ttype = "Sell";
      $("#activesign").html("↓");
    }
    $('#market_sell_price').removeClass();
    $('#activexnb').removeClass();
    $('#market_sell_price').addClass(typeclass);
    $('#activexnb').addClass(typeclass);
    var total = toNum(data.q * data.p, 6);

    data.date = unixTime(data.T);
    $('#dealrecordbody tr:first').before('<tr class="row" id="tl' + data.t + '"><td class="pl-2 col-4" span="col"><span class="time">' + data.date + '</span></td><td class="' + typeclass + ' col-4"><span class="price">' + data.p + '</span></td><td class="col-4"><span class="quantity">' + Number(data.q).toFixed(8) + '</span></td></tr>');
    $('#recent-orders-list tr:first').before('<tr id="tll' + data.t + '"><td>' + data.date + '</td><td class="' + typeclass + '">' + ttype + '</td><td class="' + typeclass + '">' + data.p + '</td><td>' + Number(data.q).toFixed(8) + '</td><td>' + (total).toFixed(8) + '</td></tr>');
    if ($('#dealrecordbody tr').length > 30) {
      $('#dealrecordbody tr:last').remove();
    }
    if ($('#recent-orders-list tr').length > 30) {
      $('#recent-orders-list tr:last').remove();
    }
    // justblinkme('#tl'+data.t);
  }

  function justblinkme(idselector) {
    $(idselector).hide(300);
    $(idselector).show(1000);
  }

  function randomrange(start, end) {
    return (Math.floor(Math.random() * end) + start);
  }

  function ws_depth(data) {
    $('input#socket_data').val(1);
    var decimals = 6;

    const maxOrders = 40;
    const minOrders = 25;
    const minOrderAmount = 0.000029;

    var list = [];
    var previous_sell_vol = 0;
    var previous_buy_vol = 0;
    if (data.b) {
      data.sell = data.b;
      var sellvol = data.sell.reduce((x, y) => { return x + Number(y[1]); }, 0);
      for (i = 0; i < data.sell.length; i++) {
        if (data.sell[i][1] < minOrderAmount) { continue }
        previous_sell_vol = previous_sell_vol + Number(data.sell[i][1]);
        list.push('<tr title="Buy at this price" class="topmost pl-1" id="activesell' + i + Date.now() + '" style="background:linear-gradient(to left, rgba(240, 80, 110, 0.2) ' + (data.sell[i][1] * 100 / sellvol) + '%, transparent 0%);" onclick="autotrust(this,\'sell\',1)"><td class="red col-4">' + data.sell[i][0] + '</td><td >' + Number(data.sell[i][1]).toFixed(decimals) + '</td><td>' + (data.sell[i][0] * data.sell[i][1]).toFixed(decimals) + '</td></tr>');
      }

      if (list.length < minOrders) {
        $("#sellorderlist").prepend(list);
        $("#sellorderlist tr").length > maxOrders ? $(`#sellorderlist tr:nth-last-child( -n + ${$("#sellorderlist tr").length - maxOrders})`).remove() : '';
      } else {
        $("#sellorderlist").html(list);
      }
    }

    list = [];
    if (data.a) {
      data.buy = data.a;
      var buyvol = data.buy.reduce((x, y) => { return x + Number(y[1]); }, 0);
      for (i = 0; i < data.buy.length; i++) {
        if (data.buy[i][1] < minOrderAmount) { continue }
        previous_buy_vol = previous_buy_vol + Number(data.buy[i][1]);
        list.push('<tr title="Sell at this price" class="topmost pl-1" id="activebuy' + i + Date.now() + '" style="background:linear-gradient(to left, rgba(103, 153, 1, 0.2) ' + (data.buy[i][1] * 100 / buyvol) + '%, transparent 0%)" onclick="autotrust(this,\'buy\',1)"><td class="green col-4"  >' + data.buy[i][0] + '</td><td class="col-4">' + Number(data.buy[i][1]).toFixed(decimals) + '</td><td class="col-4">' + (data.buy[i][0] * data.buy[i][1]).toFixed(decimals) + '</td></tr>');
      }

      if (list.length < minOrders) {
        $("#buyorderlist").prepend(list);
        $("#buyorderlist tr").length > maxOrders ? $(`#buyorderlist tr:nth-last-child( -n + ${$("#buyorderlist tr").length - maxOrders})`).remove() : '';
      } else {
        $("#buyorderlist").html(list);
      }
    }

    // blinkme('#activebuy');
    // blinkme('#activebuy');
    // blinkme('#activesell');
    // blinkme('#activesell');

  }

  function ws_newprice(data) {
    if (decimals == null) {
      var decimals = 6;
    }

    var change = (data.o - data.c) / data.o * 100;
    elementupdate("#market_sell_price", Number(data.o).toFixed(2));
    elementupdate("#market_buy_price", Number(data.c).toFixed(2));
    elementupdate("#market_change", change.toFixed(3) + "%");
    elementupdate("#market_max_price", Number(data.h).toFixed(2));
    elementupdate("#market_min_price", Number(data.l).toFixed(2));
    elementupdate("#market_volume", toNum(data.v, 2));
    elementupdate("#activexnb", Number(data.c).toFixed(2));
    elementupdate("#activermb", Number(data.c).toFixed(2));

    if (change < 0) {
      $("#market_change").attr('class', 'red')
    } else {
      $("#market_change").attr('class', 'green')
    }
  }

  function unixTime(unixtime) {

    // var u = new Date(unixtime*1000);
    var u = new Date(unixtime);
    return ('0' + u.getUTCMonth()).slice(-2) +
      '-' + ('0' + u.getUTCDate()).slice(-2) +
      ' ' + ('0' + u.getUTCHours()).slice(-2) +
      ':' + ('0' + u.getUTCMinutes()).slice(-2) +
      ':' + ('0' + u.getUTCSeconds()).slice(-2)
  }



  function blinkme(idselector) {
    var randselect = randomrange(1, 19);
    var actualselector = idselector + randselect;
    $(actualselector).css("background-color", "#F0B90B");
    $(actualselector).hide(1500);
    $(actualselector).show(1500);
    $(actualselector).css("background-color", "none");
  }

  function elementupdate(idselector, content) {
    $(idselector).html(content);
  }
</script>
<script>
  $('#buy_price,#sell_price').bind('keyup change', function () {

    var buyprice = parseFloat($('#buy_price').val()) || 0;

    var sellprice = parseFloat($('#sell_price').val()) || 0;

    var fiat_symbol = "&#36;";
    var conversion_price = parseFloat(1.00118774);

    if (buyprice == null || buyprice.toString().split(".") == null) {
      $('#buyHintBox').html("");
    } else {

      var buyHintBox = (buyprice * conversion_price)

      $('#buyHintBox').html(fiat_symbol + buyHintBox.toFixed(2));
    }

    if (sellprice != null && sellprice.toString().split(".") != null) {

      var sellHintBox = (sellprice * conversion_price)
      $('#sellHintBox').html(fiat_symbol + sellHintBox.toFixed(2));

    } else {
      $('#sellHintBox').html("");
    }


  }).bind("paste", function () {
    //return false;
  }).bind("blur", function () {
    if (this.value.slice(-1) == ".") {
      this.value = this.value.slice(0, this.value.length - 1);
    }
  }).bind("keypress", function (e) {
    var code = (e.keyCode ? e.keyCode : e.which); // COMPATIBLE_WITH_FIREFOX IE
    if (this.value.indexOf(".") == -1) {
      return (code >= 48 && code <= 57) || (code == 46);
    } else {
      return code >= 48 && code <= 57
    }
  });
  function choose_lang(lang) {
    $.cookies.set("lang", lang);
    window.location.reload();
  }
  /*document.getElementById("buyorderlist").scrollIntoView();*/
  /* Search Filter For Market Pairs*/
  $(document).ready(function () {
    $("#crypt-tab li a").click(function () {
      $.cookies.set("selectedcoin", $(this).attr("id"));
    });

    if (!$.cookies.get("selectedcoin")) {
      $("a#idbtc").trigger("click");
      $.cookies.set("selectedcoin", "idbtc");
    } else {
      var sc = $.cookies.get("selectedcoin");
      $("a#" + sc).trigger("click");
    }
    /*Chat Hide/Show*/
    $("#chatshowhidebutton").click(function () {
      $("#chatboxshowhide").toggle("slow", function () {
        if ($("#chatshowhidebutton").text() == "+") {
          $("#chatshowhidebutton").html("-");
        } else {
          $("#chatshowhidebutton").html("+");
        }
        // $(".log").text('Toggle Transition Complete');
      });
    });
    $(window).resize(function () {
      if ($(window).width() >= 980) {
        // when you hover a toggle show its dropdown menu
        $(".navbar .dropdown-toggle").hover(function () {
          $(this).parent().toggleClass("show");
          $(this).parent().find(".dropdown-menu").toggleClass("show");
        });

        // hide the menu when the mouse leaves the dropdown
        $(".navbar .dropdown-menu").mouseleave(function () {
          $(this).removeClass("show");
        });

        // do something here
      }
    });
    if (!$.cookies.get("decimalplaces")) {
      var deci = 8;
    } else {
      var deci = $.cookies.get("decimalplaces");
    }
    $("select#decimalplaces option[value=" + deci + "]").attr(
      "selected",
      "selected"
    );

    $("select#decimalplaces").on("change", function () {
      $.cookies.set("decimalplaces", this.value);
      getActiveOrders(this.value);
    });

    $("#searchFilter").on("keyup", function () {
      var value = $(this).val().toLowerCase();
      $("#CryptoPriceTable tbody tr").filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
      });
    });
  });
  function Fav() {
    var fav = $("#favourite").data("favourite");
    // console.log(fav);
    if (fav == 1) {
      $("#favourite").data("favourite", 0);
      $("#favourite").attr("src", "../Public/trade-white/images/unstar.svg");
    } else {
      $("#favourite").data("favourite", 1);
      $("#favourite").attr("src", "../Public/trade-white/images/star.svg");
    }
  }

  function getFav() {
    $.getJSON("/Trade/star?action=find" + "&t=" + Math.random(), function (data) {
      if (data) {

        if (data['data']['market']) {
          let xdata = data['data']['market'];
          console.log(xdata, 137);
          var list = '';
          var type = '';
          var typename = '';
          for (var i in xdata) {
            if (xdata[i]) {
              list += '<tr><td class="align-middle text-uppercase"><img class="crypt-star pr-1" alt="star" src="{{ asset('Public/trade-white/images/star.svg') }}" width="15" id="favourite' + xdata[i][4] + '" onclick="Fav()" data-favourite="0" data-id="' + xdata[i][4] + '" width="15"><a href="/Trade/tradepro/market/' + xdata[i][0] + '">' + xdata[i][0] + '</a></td><td class="align-middle"><span class="pr-2" data-toggle="tooltip" data-placement="right">' + xdata[i][1] + '</span></td><td> <span class="d-block ' + xdata[i][5] + '">' + xdata[i][3] + '</span></td></tr>';
            }
          }
          $("#STAR_DATA").html(list);
        }
      }
    });
  }
</script>
<script>
  function getJsonTop() {
    var sthesign = "";
    var bthesign = "";
    $.getJSON("/Ajax/getJsonTop/market/btc_usdt/?t=" + Math.random(), function (data) {
      if (data) {
        if (data['info']['new_price']) {
          $('#market_new_price').removeClass('buy');
          $('#market_new_price').removeClass('sell');
          if ($("#market_new_price").html() > data['info']['new_price']) {
            $('#market_new_price').addClass('sell');
          }
          if ($("#market_new_price").html() < data['info']['new_price']) {
            $('#market_new_price').addClass('buy');
          }
          $("#market_new_price").html(data['info']['new_price'] * 1);
        }
        if (data['info']['buy_price']) {
          $('#market_buy_price').removeClass('buy');
          $('#market_buy_price').removeClass('sell');
          $('#activermb').removeClass('buy');
          $('#activermb').removeClass('sell');
          if ($("#market_buy_price").html() > data['info']['buy_price']) {
            $('#market_buy_price').addClass('sell');
            $('#activermb').addClass('sell');
            $("#activesign").html("↓");
          }
          if ($("#market_buy_price").html() < data['info']['buy_price']) {
            //$('#market_buy_price').addClass('buy');
            $('#activermb').addClass('buy');
            $("#activesign").html("↑");
          }
          $("#market_buy_price").html(data['info']['buy_price'] * 1);
          $("#activermb").html(data['info']['buy_price'] * 1);

          $("#sell_best_price").html('$' + data['info']['buy_price']);
        }
        if (data['info']['sell_price']) {
          $('#market_sell_price').removeClass('buy');
          $('#market_sell_price').removeClass('sell');
          $('#activexnb').removeClass('buy');
          $('#activexnb').removeClass('sell');
          if ($("#market_sell_price").html() > data['info']['sell_price']) {
            //     $('#market_sell_price').addClass('sell');
            $('#activexnb').addClass('sell');

          }
          if ($("#market_sell_price").html() < data['info']['sell_price']) {
            //     $('#market_sell_price').addClass('buy');
            $('#activexnb').addClass('buy');
          }
          $("#market_sell_price").html(data['info']['sell_price'] * 1);
          $("#activexnb").html(data['info']['sell_price'] * 1);
          $("#buy_best_price").html('$' + data['info']['sell_price']);
        }
        if (data['info']['max_price']) {
          $("#market_max_price").html(data['info']['max_price'] * 1);
          $("#zhangting").html("$" + data['info']['max_price']);
        }
        if (data['info']['min_price']) {
          $("#market_min_price").html(data['info']['min_price'] * 1);
          $("#dieting").html("$" + data['info']['min_price']);
        }
        if (data['info']['volume']) {
          if (data['info']['volume'] > 1000000) {
            data['info']['volume'] = (data['info']['volume'] / 1000000).toFixed(2) + "ml"
          }
          if (data['info']['volume'] > 1000000000) {
            data['info']['volume'] = (data['info']['volume'] / 1000000000).toFixed(2) + "bl"
          }
          $("#market_volume").html(data['info']['volume']);
        }
        if (data['info']['change']) {
          $('#market_change').removeClass('buy');
          $('#market_change').removeClass('sell');
          if (data['info']['change'] > 0) {
            $('#market_change').addClass('buy');
          } else {
            $('#market_change').addClass('sell');
          }
          $("#market_change").html(data['info']['change'] + "%");
        }
      }
    });
  }
  $(function () {
    var the_decimals = $.cookies.get('decimalplaces');
    //getInstrument('usdt');
    newgetInstrument('usdt');//getInstrument('btc');
    newgetInstrument('btc');//getInstrument('eth');
    newgetInstrument('eth');//getInstrument('eur');
    newgetInstrument('eur'); getJsonTop();
    getActiveOrders(the_decimals);
    getTradelog();
    if (userid > 0) {
      getEntrustAndUsercoin();
      getClosedOrders();
    } else {
      $('#entrust_over').hide();
      $('#entrust_over2').hide();
      $('#loginrequired').show();
      $('#loginrequired2').show();
    }


  });

  function getTradelog() {

    $.getJSON("/Ajax/getTradelog/market/btc_usdt/?t=" + Math.random(), function (data) {
      if (data) {
        if (data['tradelog']) {
          var list = '';
          var shortlist = '';
          var type = '';

          var typename = '';
          for (var i in data['tradelog']) {
            if (data['tradelog'][i]['type'] == 1) {
              var total = toNum(data['tradelog'][i]['num'] * data['tradelog'][i]['price'], 6);
              list += '<tr><td span="col">' + data['tradelog'][i]['addtime'] + '</td><td class="green">Buy</td><td class="green"><span  class="price">' + data['tradelog'][i]['price'] + '</span></td><td><span  class="quantity">' + data['tradelog'][i]['num'] + '</span></td><td>' + total + '</td></tr>';
              shortlist += '<tr><td  ><span class="time">' + data['tradelog'][i]['time'] + '</span></td><td class="green"><span  class="price">' + data['tradelog'][i]['price'] + '</span></td><td ><span  class="quantity">' + data['tradelog'][i]['num'] + '</span></td></tr>';
            } else {
              list += '<tr><td span="col">' + data['tradelog'][i]['addtime'] + '</td><td class="red">Sell</td><td class="red"><span  class="price">' + data['tradelog'][i]['price'] + '</span></td><td><span  class="quantity">' + data['tradelog'][i]['num'] + '</span></td><td>' + data['tradelog'][i]['mum'] + '</td></tr>';
              shortlist += '<tr><td  ><span class="time">' + data['tradelog'][i]['time'] + '</span></td><td class="red"><span  class="price">' + data['tradelog'][i]['price'] + '</span></td><td ><span  class="quantity">' + data['tradelog'][i]['num'] + '</span></td></tr>';
            }
          }
          $('#dealrecordbody').html(shortlist);
          $("#recent-orders-list").html(list);
        }
      }
    });
  }
  function newgetInstrument(category) {
    $.getJSON("/Ajax/pairsBycat?cat=" + category + "&t=" + Math.random(), function (data) {
      if (data) {
        if (data['data']) {
          var list = '';
          var type = '';
          var typename = '';
          for (var i in data['data']) {
            if (data['data'][i]) {
              list += '<tr><td class="align-middle text-uppercase"><img class="crypt-star pr-1" alt="star" src="/Public/trade-white/images/star.svg" width="15" id="favourite' + data['data'][i][4] + '" onclick="Fav()" data-favourite="0" data-id="' + data['data'][i][4] + '" width="15"><a href="/Trade/tradepro/market/' + data['data'][i][0] + '">' + data['data'][i][0] + '</a></td><td class="align-middle"><span class="pr-2" data-toggle="tooltip" data-placement="right">' + data['data'][i][1] + '</span></td><td> <span class="d-block ' + data['data'][i][5] + '">' + data['data'][i][3] + '</span></td></tr>';
            }
          }
          $("#coinleftmenu-" + category).html(list);
        }
      }
    });
    setTimeout('newgetInstrument("' + category + '")', 50000);
  }
  function getInstrument(coinname) {
    $.getJSON("/Ajax/advanceinstrument?coin=" + coinname + "&t=" + Math.random(), function (data) {
      if (data) {
        if (data['data']) {
          var list = '';
          var type = '';
          var typename = '';
          for (var i in data['data']) {
            if (data["data"][i][5] == 'crypt-up') {
              var changeclass = "green";
            } else {
              var changeclass = "red";
            }
            if (data['data'][i]) {
              list += '<tr class="row"><td class="text-uppercase col-4 ml-3"><i class="icon ion-md-star add-to-favorite" id="favourite' + data['data'][i][4] + '" onclick="Fav()" data-favourite="0" data-id="' + data['data'][i][4] + '"></i> <a href="/Trade/tradepro/market/' + data['data'][i][0] + '">' + data['data'][i][0].toString() + '</a></td><td class="col-4"><span class="pr-2" data-toggle="tooltip" data-placement="right">' + data['data'][i][1].toString() + '</span></td><td class="col-3"> <span class="' + changeclass + ' ' + data['data'][i][5] + '">' + data['data'][i][3] + '</span></td></tr>';
            }
          }
          $("#coinleftmenu-" + coinname).html(list);
        }
      }
    });
    setTimeout('getInstrument("' + coinname + '")', 50000);
  }
  function getEntrustAndUsercoin() {
    $.getJSON("/Ajax/getFullEntrustAndUsercoin?market=" + market + "&t=" + Math.random(), function (data) {
      if (data) {
        if (data['entrust']) {
          $('#entrust_over').show();
          $('#loginrequired').hide();
          var list = '';
          var cont = data['entrust'].length;
          for (i = 0; i < data['entrust'].length; i++) {
            if (data['entrust'][i]['type'] == 1) {
              list += '<tr><td class="buy green text-uppercase">' + data['entrust'][i]['market'] + '</td><td class="buy green">' + data['entrust'][i]['addtime'] + '</td><td class="buy">Buy-' + data['entrust'][i]['tradetype'] + '</td><td class="buy">' + data['entrust'][i]['price'] + '</td><td class="buy">' + data['entrust'][i]['condition'] + '</td><td class="buy">' + data['entrust'][i]['num'] + '</td><td class="buy">' + data['entrust'][i]['deal'] + '</td><td class="buy">' + (parseFloat(data['entrust'][i]['price']) * parseFloat(data['entrust'][i]['num'])).toFixed(market_round) + '</td><td><a class="cancelaa btn btn-danger btn-xs" id="' + data['entrust'][i]['id'] + '" onclick="cancelaa(\'' + data['entrust'][i]['id'] + '\',\'' + data['entrust'][i]['tradetype'] + '\')" href="javascript:void(0);"><i class="fa fa-trash" ></i> </a></td></tr>';
            } else {
              list += '<tr><td class="sell red text-uppercase">' + data['entrust'][i]['market'] + '</td><td class="sell red">' + data['entrust'][i]['addtime'] + '</td><td class="sell">Sell-' + data['entrust'][i]['tradetype'] + '</td><td class="sell">' + data['entrust'][i]['price'] + '</td><td class="sell">' + data['entrust'][i]['condition'] + '</td><td class="sell">' + data['entrust'][i]['num'] + '</td><td class="sell">' + data['entrust'][i]['deal'] + '</td><td class="sell">' + (parseFloat(data['entrust'][i]['price']) * parseFloat(data['entrust'][i]['num'])).toFixed(market_round) + '</td><td><a class="cancelaa btn btn-danger btn-xs" id="' + data['entrust'][i]['id'] + '" onclick="cancelaa(\'' + data['entrust'][i]['id'] + '\',\'' + data['entrust'][i]['tradetype'] + '\')" href="javascript:void(0);"><i class="fa fa-trash" ></i> </a></td></tr>';
            }
          }
          if (cont == 10) {
            list += '<tr><td style="text_align:center;" colspan="7"><a href="/Finance/mywt" style="color: #2674FF;">View More</a>&nbsp;&nbsp;</td></tr>';
          }
          $('#entrustlist').html(list);
        } else {
          $('#entrust_over').hide();
          $('#loginrequired').show();
        }

        if (data['usercoin']) {
          if (data['usercoin']['usd']) {
            $("#base_coin").html(data['usercoin']['usd']);
          } else {
            $("#base_coin").html('0.00');
          }

          if (data['usercoin']['usdd']) {
            $("#my_usdd").html(data['usercoin']['usdd']);
          } else {
            $("#my_usdd").html('0.00');
          }

          if (data['usercoin']['xnb']) {
            $("#user_coin").html(data['usercoin']['xnb']);
          } else {
            $("#user_coin").html('0.00');
          }

          if (data['usercoin']['xnbd']) {
            $("#my_xnbd").html(data['usercoin']['xnbd']);
          } else {
            $("#my_xnbd").html('0.00');
          }
        }

      }
    });
    setTimeout('getEntrustAndUsercoin()', 5000);
  }

  function getActiveOrders(decimals) {
    if ($("input#socket_data").val() == 1) {
      return false;
    }
    var bestselloffer = '';
    var bestbuyoffer = '';
    if (decimals == null) { var decimals = 6; }
    if (trade_moshi != 2) {

      $.getJSON("/Ajax/getActiveOrders?market=" + market + "&trade_moshi=" + trade_moshi + "&t=" + Math.random(), function (data) {
        if (data) {

          if (data['depth']) {
            var list = '';

            if (data['depth']['sell']) {
              //   data['depth']['sell'].reverse();
              for (i = 0; i < data['depth']['sell'].length; i++) {
                list += '<tr title="Buy at this price" class="topmost pl-1" style="background:linear-gradient(to left, rgba(207,48,74, 0.15) ' + (data['depth']['sell'][i][1] * 100 / data['sellvol']) + '%, transparent 0%)" onclick="autotrust(this,\'sell\',1)"><td class="red col-4">' + data['depth']['sell'][i][0] + '</td><td class="col-4">' + data['depth']['sell'][i][1] + '</td><td class="col-4">' + (data['depth']['sell'][i][0] * data['depth']['sell'][i][1]).toFixed(decimals) + '</td></tr>';
                var bestselloffer = data['depth']['sell'][0][0];
              }
              if ($('#buy_price').val().length == 0 || $('#buy_price').val() == '--') {
                $("#buy_price").val(bestselloffer);
              }

            }
            $("#sellorderlist").html(list);
            list = '';
            if (data['depth']['buy']) {
              for (i = 0; i < data['depth']['buy'].length; i++) {
                list += '<tr title="Sell at this price" class="topmost pl-1" style="background:linear-gradient(to left, rgba(3,166,109, 0.15) ' + (data['depth']['buy'][i][1] * 100 / data['buyvol']) + '%, transparent 0%)" onclick="autotrust(this,\'buy\',1)"><td class="green col-4"  >' + data['depth']['buy'][i][0] + '</td><td  class="col-4">' + data['depth']['buy'][i][1] + '</td><td class="col-4"  >' + (data['depth']['buy'][i][0] * data['depth']['buy'][i][1]).toFixed(decimals) + '</td></tr>';
              }

            }

            if (data['depth']['buy']) {
              var bestbuyoffer = data['depth']['buy'][0][0];
            } else { bestbuyoffer = ''; }
            if ($('#sell_price').val().length === 0 || $('#sell_price').val() == '--') {
              $("#sell_price").val(bestbuyoffer);
            }
            $("#buyorderlist").html(list);
          }

        }
      });
      if ($('#buy_price').val().length == 0 || $('#buy_price').val() == '--') {
        $("#buy_price").val(bestselloffer);
      }
      if ($('#sell_price').val().length === 0 || $('#sell_price').val() == '--') {
        $("#sell_price").val(bestbuyoffer);
      }
      clearInterval(getDepth_tlme);

      var wait = second = 5;
      getDepth_tlme = setInterval(function () {
        wait--;
        if (wait < 0) {
          clearInterval(getDepth_tlme);
          getActiveOrders(decimals);

          wait = second;
        }
      }, 1000);
    }
  }
  function getClosedOrders(decimals = 1) {
    $.getJSON("/Ajax/getClosedOrders?market=" + market + "&t=" + Math.random(), function (data) {
      if (data) {
        if (data['entrust']) {
          $('#entrust_over2').show();
          $('#loginrequired2').hide();
          var list = '';
          var cont = data['entrust'].length;
          for (i = 0; i < data['entrust'].length; i++) {
            if (data['entrust'][i]['type'] == 1) {
              list += '<tr ><td class="buy">' + data['entrust'][i]['addtime'] + '</td><td class="buy">Buy</td><td class="buy">' + data['entrust'][i]['price'] + '</td><td class="buy">' + data['entrust'][i]['num'] + '</td><td class="buy">' + (parseFloat(data['entrust'][i]['price']) * parseFloat(data['entrust'][i]['num'])).toFixed(market_round) + '</td></tr>';
            } else {
              list += '<tr><td class="sell">' + data['entrust'][i]['addtime'] + '</td><td class="sell">Sell</td><td class="sell">' + data['entrust'][i]['price'] + '</td><td class="sell">' + data['entrust'][i]['num'] + '</td><td class="sell">' + (parseFloat(data['entrust'][i]['price']) * parseFloat(data['entrust'][i]['num'])).toFixed(market_round) + '</td></tr>';
            }
          }
          if (cont == 10) {
            list += '<tr><td style="text_align:center;" colspan="7"><a href="/Finance/mywt" style="color: #2674FF;">View More</a>&nbsp;&nbsp;</td></tr>';
          }
          $('#closedorderslist').html(list);
        } else {
          $('#entrust_over2').hide();
          $('#loginrequired2').show();
        }
      }
    });
    setTimeout('getClosedOrders()', 5000);
  }
  // AUTOFILL_PRICE
  function autotrust(_this, type, cq) {

    if (type == 'sell') {
      $('#buy_num').val($(_this).children().eq(cq).html()).css({ 'font_size': '14px' });
      $('#buy_price').val($(_this).children().eq(0).html()).css({ 'font_size': '14px' });
      if ($("#my_usd").html() > 0) {
        $("#buy_max").html(toNum(($("#my_usd").html() / $('#buy_price').val()), market_round_num));
      }
      if ($('#buy_num').val()) {
        $("#buy_mum").html(($('#buy_num').val() * $('#buy_price').val()).toFixed(market_round));
      }

    }
    if (type == 'buy') {
      $('#sell_num').val($(_this).children().eq(cq).html()).css({ 'fontSize': '14px' });
      $('#sell_price').val($(_this).children().eq(0).html()).css({ 'font_size': '14px' });
      if ($("#my_xnb").html() > 0) {
        $("#sell_max").html($("#my_xnb").html());
      }
      if ($('#sell_num').val()) {
        $("#sell_mum").html((parseFloat($('#sell_num').val() * $('#sell_price').val())).toFixed(market_round));
      }
    }

  }

  function tradeadd_buy(tradeType) {
    if (trans_lock) {
      layer.msg('Do not resubmit', { icon: 2 });
      return;
    }
    if (userid < 1) {
      layer.msg('Please login first', { icon: 2 });
      return;
    }
    trans_lock = 1;

    var price = parseFloat($('#buy_price').val());
    var num = parseFloat($('#buy-num').val());
    var paypassword = $('#buy_paypassword').val();
    if (price == "" || price == null) {
      layer.tips('Please enter content', '#buy_price', { tips: 3 });
      return false;
    }
    if (num == "" || num == null) {
      layer.tips('Please enter content', '#buy_num', { tips: 3 });
      return false;
    }

    //load layer style 3-1
    //layer.load(3);
    layer.load(0, { shade: [0.3, '#000'] });


    setTimeout(function () {
      layer.closeAll('loading');
      trans_lock = 0;
    }, 3000);
    $.post("/Trade/upTrade", {
      price: $('#buy_price').val(),
      num: $('#buy_num').val(),
      paypassword: $('#buy_paypassword').val(),
      market: market,
      type: 1,
      tradeType: tradeType
    }, function (data) {
      layer.closeAll('loading');
      trans_lock = 0;
      if (data.status == 1) {
        getActiveOrders(2);
        getTradelog();
        $("#buy_price").val('');
        $("#buy_num").val('');
        $("#sell_price").val('');
        $("#sell_num").val('');
        layer.msg(data.info, { icon: 1 });
      } else {
        layer.msg(data.info, { icon: 2 });
      }
    }, 'json');
  }

  function tradeadd_sell(tradeType) {
    if (trans_lock) {
      layer.msg('Do not resubmit', { icon: 2 });
      return;
    }
    if (userid < 1) {
      layer.msg('Please login first', { icon: 2 });
      return;
    }
    trans_lock = 1;
    var price = parseFloat($('#sell_price').val());
    var num = parseFloat($('#sell_num').val());
    var paypassword = $('#sell_paypassword').val();
    if (price == "" || price == null) {
      layer.tips('Please enter content', '#sell_price', { tips: 3 });
      return false;
    }
    if (num == "" || num == null) {
      layer.tips('Please enter content', '#sell_num', { tips: 3 });
      return false;
    }
    //layer.load(3);
    layer.load(0, { shade: [0.3, '#000'] });
    //HERE_DEMO_CLOSE
    setTimeout(function () {
      layer.closeAll('loading');
      trans_lock = 0;
    }, 10000);


    $.post("/Trade/upTrade", {
      price: $('#sell_price').val(),
      num: $('#sell_num').val(),
      paypassword: $('#sell_paypassword').val(),
      market: market,
      type: 2,
      tradeType: tradeType
    }, function (data) {
      getActiveOrders(2);
      getTradelog();
      layer.closeAll('loading');
      trans_lock = 0;
      if (data.status == 1) {
        $("#buy_price").val('');
        $("#buy_num").val('');
        $("#sell_price").val('');
        $("#sell_num").val('');
        layer.msg(data.info, { icon: 1 });
      } else {
        layer.msg(data.info, { icon: 2 });
      }
    }, 'json');
  }


  //UNDO
  function cancelaa(id, type) {
    if (userid < 1) {
      layer.msg('Please login first', { icon: 2 });
      return;
    }
    var router = 'reject';
    if (type == 'limit') {
      router = 'reject'
    }
    if (type == 'Stop-Limit') {
      router = 'stopreject'
    }
    $.post("/Trade/" + router + "", { id: id }, function (data) {
      if (data.status == 1) {
        getEntrustAndUsercoin();
        getActiveOrders(8);
        layer.msg(data.info, { icon: 1 });
      } else {
        layer.msg(data.info, { icon: 2 });
      }
    });
  }

  function hasClass(ele, cls) {
    return ele.className.match(new RegExp('(\\s|^)' + cls + '(\\s|$)'));
  }
  function Dark() {
    var elementx = document.getElementById("mainbody");
    var darkclass = "crypt-dark";
    var lightclass = "crypt-white";
    if (hasClass(elementx, darkclass)) {
      $('#darksun').html('<i class="pe-7s-moon" style="font-size:16px;font-weight:bold;"></i>');
      elementx.classList.remove(darkclass);
      elementx.classList.add(lightclass);
      $.cookies.set("colorshade", "White");
      $.cookies.set('chart_theme', 'white');
    }
    else {
      elementx.classList.add(darkclass);
      elementx.classList.remove(lightclass);
      $('#darksun').html('<i class="pe-7s-sun" style="font-size:16px;font-weight:bold;"></i>');
      $.cookies.set("colorshade", "Dark");
      $.cookies.set('chart_theme', 'black');
    }
    document.getElementById('market_chart').contentWindow.location.reload(true);
    document.getElementById('market_chartx').contentWindow.location.reload(true);

  }

  function ShowMe(ShowMe) {

    var elementx = document.getElementById(ShowMe);
    var hideclass = "hideme"

    if (ShowMe == 'depthchart') {
      document.getElementById('marketchartbox').classList.add(hideclass);
      document.getElementById('tradingviewbox').classList.add(hideclass);
      //document.getElementById('depthchart').classList.remove(hideclass);
    }
    if (ShowMe == 'tradingviewbox') {
      document.getElementById('marketchartbox').classList.add(hideclass);
      //document.getElementById('depthchart').classList.add(hideclass);
      document.getElementById('tradingviewbox').classList.remove(hideclass);
    }
    if (ShowMe == 'marketchartbox') {
      //document.getElementById('depthchart').classList.add(hideclass);
      document.getElementById('tradingviewbox').classList.add(hideclass);
      document.getElementById('marketchartbox').classList.remove(hideclass);
    }


  }

  function toNum(num, round) {
    return Math.round(num * Math.pow(10, round) - 1) / Math.pow(10, round);
  }

  $('#buy_price,#buy_num,#sell_price,#sell_num').bind('keyup change', function () {

    var buyprice = parseFloat($('#buy_price').val());
    var buynum = parseFloat($('#buy_num').val());
    var sellprice = parseFloat($('#sell_price').val());
    var sellnum = parseFloat($('#sell_num').val());
    var buymum = buyprice * buynum;
    var sellmum = sellprice * sellnum;
    var myrmb = $("#my_usd").html();
    var myxnb = $("#my_xnb").html();
    var buykenum = 0;
    var sellkenum = 0;
    if (myrmb > 0) {
      buykenum = myrmb / buyprice;
    }
    if (myxnb > 0) {
      sellkenum = myxnb;
    }

    if (buyprice != null && buyprice.toString().split(".") != null && buyprice.toString().split(".")[1] != null) {
      if (buyprice.toString().split('.')[1].length > market_round) {
        $('#buy_price').val(buyprice.toFixed(market_round));
      }
    }
    if (buynum != null && buynum.toString().split(".") != null && buynum.toString().split(".")[1] != null) {
      if (buynum.toString().split('.')[1].length > market_round_num) {
        $('#buy_num').val(toNum(buynum, market_round_num));
      }
    }
    if (sellprice != null && sellprice.toString().split(".") != null && sellprice.toString().split(".")[1] != null) {
      if (sellprice.toString().split('.')[1].length > market_round) {
        $('#sell_price').val(sellprice.toFixed(market_round));
      }
    }
    if (sellnum != null && sellnum.toString().split(".") != null && sellnum.toString().split(".")[1] != null) {
      if (sellnum.toString().split('.')[1].length > market_round_num) {
        $('#sell_num').val(toNum(sellnum, market_round_num));
      }
    }
    if (buymum != null && buymum > 0) {
      $('#buy_mum').html((buymum * 1).toFixed(market_round));
    }
    if (sellmum != null && sellmum > 0) {
      $('#sell_mum').html((sellmum * 1).toFixed(market_round));
    }
    if (buykenum != null && buykenum > 0 && buykenum != 'Infinity') {
      $('#buy_max').html(toNum(buykenum, market_round_num));
    }
    if (sellkenum != null && sellkenum > 0 && sellkenum != 'Infinity') {
      $('#sell_max').html(sellkenum);
    }
  }).bind("paste", function () {
    //return false;
  }).bind("blur", function () {
    if (this.value.slice(-1) == ".") {
      this.value = this.value.slice(0, this.value.length - 1);
    }
  }).bind("keypress", function (e) {
    var code = (e.keyCode ? e.keyCode : e.which); // COMPATIBLE_WITH_FIREFOX IE
    if (this.value.indexOf(".") == -1) {
      return (code >= 48 && code <= 57) || (code == 46);
    } else {
      return code >= 48 && code <= 57
    }
  });

  function Percentage(percent, type) {
    percent = parseInt(percent);

    var buy_fees = $('#buy_fees').html();
    var sell_fees = $('#sell_fees').html();
    if (type == 'buy' && percent == 100) {
      percent = parseFloat(100 - buy_fees);
    }
    if (type == 'sell' && percent == 100) {
      percent = parseFloat(100 - sell_fees);
    }
    var user_coin = $('#user_coin').html();
    var base_coin = $('#base_coin').html();

    if (type == 'buy') {
      if ($('#buy_price').val() == "" || $('#buy_price').val() == null) {
        layer.tips('Please enter a price', '#buy_price', { tips: 3 });
        return false;
      }

      var paste = (base_coin / 100) * percent;
      $('#buy_mum').html((paste).toFixed(market_round));
      $("#buy_num").val((paste / parseFloat($('#buy_price').val())).toFixed(market_round));
    }
    if (type == 'sell') {
      var paste = (user_coin / 100) * percent;
      $('#sell_num').val((paste).toFixed(market_round));
      $("#sell_mum").html((parseFloat($('#sell_num').val() * $('#sell_price').val())).toFixed(market_round));
    }

  }
  //SAVE_TRANSACTION_PASSWORD_SETT
  function tpwdsettingaa() {
    var paypassword = $("#aaapaypassword").val();
    var tpwdsetting = $("input[name='aaatpwdsetting']:checked").val();
    if (paypassword == "" || paypassword == null) {
      layer.tips('Provide Trans Password', '#paypassword', { tips: 3 });
      return false;
    }
    if (tpwdsetting == "" || tpwdsetting == null) {
      layer.tips('Please select Trans Password', '#tpwdsetting', { tips: 3 });
      return false;
    }


    $.post('/user/uptpwdsetting', { paypassword: paypassword, tpwdsetting: tpwdsetting }, function (d) {
      if (d.status) {
        layer.msg('Settings Saved', { icon: 1 });
        window.location.reload();
      } else {
        layer.msg(d.info, { icon: 2 });
      }

    }, 'json');
  }
  function OrderType(type) { //limitsell,limitbuy , stopsell stopbuy
    switch (type) {
      case 'limit':
        $('#ltbutton').addClass("active");
        $('#mtbutton').removeClass("active");
        $('#stbutton').removeClass("active");
        $('#sellstop').hide();
        $('#stopsellbutton').hide();
        $('#limitsellbutton').show();
        $('#buystop').hide();
        $('#stopbuybutton').hide();
        $('#limitbuybutton').show();
        $('#marketbuybutton').hide();
        $('#marketsellbutton').hide();
        $('#buypricebox').show();
        $('#sellpricebox').show();
        $('#selectedwidget').html('Limit');
        $('#margin-tab').hide();
        //$('#DealRecordTable').css('height','451px');

        break;
      case 'stop':
        $('#stbutton').addClass("active");
        $('#mtbutton').removeClass("active");
        $('#ltbutton').removeClass("active");
        $('#sellstop').show();
        $('#stopsellbutton').show();
        $('#limitsellbutton').hide();
        $('#buystop').show();
        $('#stopbuybutton').show();
        $('#limitbuybutton').hide();
        $('#marketbuybutton').hide();
        $('#marketsellbutton').hide();
        $('#buypricebox').show();
        $('#sellpricebox').show();
        $('#selectedwidget').html('Stop');
        $('#margin-tab').hide();
        //$('#DealRecordTable').css('height','489px');
        break;

      case 'market':
        $('#mtbutton').addClass("active");
        $('#ltbutton').removeClass("active");
        $('#stbutton').removeClass("active");
        $('#sellstop').hide();
        $('#stopsellbutton').hide();
        $('#limitsellbutton').hide();
        $('#buystop').hide();
        $('#buypricebox').hide();
        $('#sellpricebox').hide();
        $('#stopbuybutton').hide();
        $('#limitbuybutton').hide();
        $('#marketbuybutton').show();
        $('#marketsellbutton').show();
        $('#selectedwidget').html('Market');
        $('#margin-tab').hide();
        break;
      default:
        console.log(789, 'Incorrect Type');

    }
  }
  function TipStopLimit() {
    layer.tips('A Stop-Limit order is an order to buy or sell a coin once the price reaches a specified price.', '#stbutton', { tips: 3 });
  }
  function stopadd_buy() {
    /*if (trans_lock) {
        layer.msg('Do not resubmit', {icon: 2});
        return;
    }
    trans_lock = 1;
*/
    var price = $('#buy_price').val();
    var stop = $('#buy_stop').val();
    var num = parseFloat($('#buy_num').val());
    var paypassword = $('#buy_paypassword').val();
    var buyprice = $('#market_buy_price').html();
    //var buyprice= bestbuyoffer; //Highest buy offer take from Orderbook
    var xnb = 'btc';
    var rmb = 'usdt';

    if (price == "" || price == null) {
      layer.tips('Please enter content', '#buy_price', { tips: 3 });
      return false;
    }
    if (stop == "" || stop == null) {
      layer.tips('Please enter content', '#buy_stop', { tips: 3 });
      return false;
    }
    if (num == "" || num == null) {
      layer.tips('Please enter content', '#buy_num', { tips: 3 });
      return false;
    }

    //load layer style 3-1
    //  layer.load(3);
    //layer.load(0, { shade:[0.3, '#000'] });

    //HERE_DEMO_CLOSE
    /*    setTimeout(function () {
            layer.closeAll('loading');
            trans_lock = 0;
        }, 3000);*/

    if (buyprice > stop) { var condition = 'drops to or below'; }
    else { var condition = 'rises to or above'; }
    var msg = 'If the last price ' + condition + ' ' + stop + rmb + '  , an order to buy ' + num + xnb + '  at a price of ' + price + rmb + ' will be placed';
    layer.confirm(msg, {
      btn: ['Confirm', 'Cancel'] //PUSH_BUTTON
    }, function () {
      $.post("/Trade/upTrade", {
        price: $('#buy_price').val(),
        stop: $('#buy_stop').val(),
        num: $('#buy_num').val(),
        paypassword: $('#buy_paypassword').val(),
        market: market,
        tradeType: 'stop',
        type: 1
      }, function (data) {
        layer.closeAll('loading');
        trans_lock = 0;
        if (data.status == 1) {
          getActiveOrders(6);
          $("#buy_stop").val('');
          $("#buy_price").val('');
          $("#buy_num").val('');
          $("#sell_price").val('');
          $("#sell_num").val('');
          layer.msg(data.info, { icon: 1 });
        } else {
          layer.msg(data.info, { icon: 2 });
        }
      }, 'json');
    });
  }

  function stopadd_sell() {
    if (trans_lock) {
      //layer.msg('Do not resubmit', {icon: 2});
      //return;
    }
    trans_lock = 1;
    var price = $('#sell_price').val();
    var stop = $('#sell_stop').val();
    var num = parseFloat($('#sell_num').val());
    var paypassword = $('#sell_paypassword').val();
    var sellprice = $('#market_sell_price').val();
    //var sellprice= bestselloffer; //lowest sell offer take from Orderbook

    var xnb = 'btc';
    var rmb = 'usdt';

    if (price == "" || price == null) {
      layer.tips('Please enter content', '#sell_price', { tips: 3 });
      return false;
    }
    if (stop == "" || stop == null) {
      layer.tips('Please enter content', '#sell_stop', { tips: 3 });
      return false;
    }
    if (num == "" || num == null) {
      layer.tips('Please enter content', '#sell_num', { tips: 3 });
      return false;
    }

    if (sellprice > stop) { var condition = 'drops to or below'; }
    else { var condition = 'rises to or above'; }
    var msg = 'If the last price ' + condition + ' ' + stop + rmb + '  , an order to sell ' + num + xnb + '  at a price of ' + price + rmb + ' will be placed';
    layer.confirm(msg, {
      btn: ['Confirm', 'Cancel'] //PUSH_BUTTON
    }, function () {

      $.post("/Trade/upTrade", {
        price: $('#sell_price').val(),
        stop: $('#sell_stop').val(),
        num: $('#sell_num').val(),
        paypassword: $('#sell_paypassword').val(),
        tradeType: 'stop',
        market: market,
        type: 2
      }, function (data) {
        layer.closeAll('loading');
        trans_lock = 0;
        if (data.status == 1) {
          getActiveOrders(6);
          $("#buy_price").val('');
          $("#buy_num").val('');
          $("#sell_price").val('');
          $("#sell_stop").val('');
          $("#sell_num").val('');
          layer.msg(data.info, { icon: 1 });
        } else {
          layer.msg(data.info, { icon: 2 });
        }
      }, 'json');
    });
  }

  $(function () {
    var the_decimals = $.cookies.get('decimalplaces');
    getInstrument('usdt'); getInstrument('btc'); getInstrument('eth'); getInstrument('eur');

    getJsonTop();
    getActiveOrders(the_decimals);
    getTradelog();
    if (userid > 0) {
      getEntrustAndUsercoin();
      getClosedOrders();
    } else {
      $('#entrust_over').hide();
      $('#entrust_over2').hide();
      $('#loginrequired').show();
      $('#loginrequired2').show();
    }


  });

  $(document).ready(function () {

    $("#buy_range").ionRangeSlider({

      step: 1,
      min: 0,
      max: 100,
      from: 100,
      grid: true,
      skin: "round",
      onChange: function (data) {
        var fees = $("#buy_fees").val();
        var percent = data.from;
        var avail = $("#base_coin").html();
        var calc_buy = (avail * percent) / 100;
        var to_buy = calc_buy * (100 - fees) / 100;

        $("#buy_num").val((to_buy / parseFloat($('#buy_price').val())).toFixed(market_round));
      }
    });
    $("#sell_range").ionRangeSlider({

      step: 1,
      min: 0,
      max: 100,
      from: 100,
      grid: true,
      skin: "round",
      onChange: function (data) {
        var percent = data.from;
        var avail = $("#user_coin").html();
        var calc_buy = (avail * percent) / 100;
        $("#sell_num").val(calc_buy.toString());

      }
    });
  });
</script>

@endsection