<!-- Trading Panel -->
<div class="col-md-12 buysell_s4">
    <div class="market-trade">

        <!-- Tabs -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <ul id="orderTypeTabs" class="nav nav-pills">
                <li class="nav-item">
                    <button type="button" class="nav-link active" data-type="limit">Limit</button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-type="market">Market</button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-type="stop">Stop-Limit</button>
                </li>
            </ul>
            <div class="hide-mobile">
                <button class="btn btn-sm btn-outline-warning">
                    <a href="{{ url('wallet') }}">Transfer</a>
                </button>
            </div>
        </div>

        <!-- Trade content -->
        <div class="tab-content">
            <div class="tab-pane fade show active" id="pills-trade-limit" role="tabpanel">
                <div class="outflexboxx d-flex justify-content-between">

                    <!-- Buy Panel -->
                    <div class="market-trade-buy">
                        <p>
                            Balance:
                            <span id="base_coin">{{ number_format($quoteWallet->available_balance ?? (($quoteWallet->balance ?? 0) - ($quoteWallet->locked_balance ?? 0)), 8) }}</span>
                            {{ $tradingPair->quoteCurrency->symbol }}
                            <span class="pull-right hide-mobile">
                                <a id="deposit-href" href="{{ url('wallet/deposit') }}" class="green"><i class="fa fa-download"></i> Deposit</a>
                            </span>
                        </p>

                        <!-- Stop input -->
                        <div class="input-group" id="buystop" style="display:none">
                            <input class="form-control" placeholder="Stop" type="number" id="buy_stop" step="0.00000001" />
                            <div class="input-group-append">
                                <button class="btn btn-increment hide-mobile" type="button" onclick="incrementValue('buy_stop', 0.01)"><strong>+</strong></button>
                                <span class="input-group-text">{{ $tradingPair->quoteCurrency->symbol }}</span>
                            </div>
                        </div>

                        <!-- Price -->
                        <div class="input-group" id="buypricebox">
                            <input class="form-control" placeholder="Price" type="number" id="buy_price" step="0.00000001" value="{{ number_format($currentPrice,8,'.','') }}" />
                            <div class="input-group-append">
                                <button class="btn btn-increment hide-mobile" type="button" onclick="incrementValue('buy_price', 0.01)"><strong>+</strong></button>
                                <span class="input-group-text">{{ $tradingPair->quoteCurrency->symbol }}</span>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div class="input-group">
                            <input class="form-control" placeholder="Qty" type="number" id="buy_num" step="0.00000001" />
                            <div class="input-group-append">
                                <button class="btn btn-increment hide-mobile" type="button" onclick="incrementValue('buy_num', 0.001)"><strong>+</strong></button>
                                <span class="input-group-text">{{ $tradingPair->baseCurrency->symbol }}</span>
                            </div>
                        </div>

                        <!-- Range slider -->
                        <input type="range" name="buy_range" id="buy_range" min="0" max="100" value="0" oninput="updateFromRange('buy', this.value)" />

                        <!-- Quick % buttons -->
                        <ul class="market-trade-list">
                            <li><a href="#!" onclick="setPercentage('25','buy')">25%</a></li>
                            <li><a href="#!" onclick="setPercentage('50','buy')">50%</a></li>
                            <li><a href="#!" onclick="setPercentage('75','buy')">75%</a></li>
                            <li><a href="#!" onclick="setPercentage('100','buy')">100%</a></li>
                        </ul>

                        <!-- Totals & Fees -->
                        <div class="d-flex justify-content-between align-items-center">
                            <p>Total: <span id="buy_mum">--</span></p>
                            <p>Fee: <span id="buy_fees">{{ number_format($tradingPair->trading_fee,2) }}</span>%</p>
                        </div>

                        <!-- Buy buttons -->
                        @auth
                        <div id="stopbuybutton" style="display:none"><button class="btn buy-trade" onclick="placeOrder('buy','stop')">STOP BUY</button></div>
                        <div id="limitbuybutton"><button class="btn buy-trade" onclick="placeOrder('buy','limit')">Buy</button></div>
                        <div id="marketbuybutton" style="display:none"><button class="btn buy-trade" onclick="placeOrder('buy','market')">Buy</button></div>
                        @else
                        <div id="stopbuybutton" style="display:none"><button class="btn buy-trade" onclick="promptLogin()">STOP BUY</button></div>
                        <div id="limitbuybutton"><button class="btn buy-trade" onclick="promptLogin()">Buy</button></div>
                        <div id="marketbuybutton" style="display:none"><button class="btn buy-trade" onclick="promptLogin()">Buy</button></div>
                        @endauth
                    </div>

                    <!-- Sell Panel -->
                    <div class="market-trade-sell">
                        <p>
                            Balance: <span id="user_coin">{{ number_format($baseWallet->available_balance ?? (($baseWallet->balance ?? 0) - ($baseWallet->locked_balance ?? 0)),8) }}</span> {{ $tradingPair->baseCurrency->symbol }}
                            <span class="pull-right hide-mobile"><a href="{{ url('wallet/deposit') }}" class="red"><i class="fa fa-download"></i> Deposit</a></span>
                        </p>

                        <!-- Stop input -->
                        <div class="input-group" id="sellstop" style="display:none">
                            <input class="form-control" placeholder="Stop" type="number" id="sell_stop" step="0.00000001" />
                            <div class="input-group-append">
                                <button class="btn btn-increment hide-mobile" type="button" onclick="incrementValue('sell_stop', 0.01)"><strong>+</strong></button>
                                <span class="input-group-text">{{ $tradingPair->quoteCurrency->symbol }}</span>
                            </div>
                        </div>

                        <!-- Price -->
                        <div class="input-group" id="sellpricebox">
                            <input class="form-control" placeholder="Price" type="number" id="sell_price" step="0.00000001" value="{{ number_format($currentPrice,8,'.','') }}" />
                            <div class="input-group-append">
                                <button class="btn btn-increment hide-mobile" type="button" onclick="incrementValue('sell_price', 0.01)"><strong>+</strong></button>
                                <span class="input-group-text">{{ $tradingPair->quoteCurrency->symbol }}</span>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div class="input-group">
                            <input class="form-control" placeholder="Qty" type="number" id="sell_num" step="0.00000001" />
                            <div class="input-group-append">
                                <button class="btn btn-increment hide-mobile" type="button" onclick="incrementValue('sell_num', 0.001)"><strong>+</strong></button>
                                <span class="input-group-text">{{ $tradingPair->baseCurrency->symbol }}</span>
                            </div>
                        </div>

                        <!-- Range slider -->
                        <input type="range" name="sell_range" id="sell_range" min="0" max="100" value="0" oninput="updateFromRange('sell', this.value)" />

                        <!-- Quick % -->
                        <ul class="market-trade-list">
                            <li><a href="#!" onclick="setPercentage('25','sell')">25%</a></li>
                            <li><a href="#!" onclick="setPercentage('50','sell')">50%</a></li>
                            <li><a href="#!" onclick="setPercentage('75','sell')">75%</a></li>
                            <li><a href="#!" onclick="setPercentage('100','sell')">100%</a></li>
                        </ul>

                        <!-- Totals & Fees -->
                        <div class="d-flex justify-content-between align-items-center">
                            <p>Total: <span id="sell_mum">--</span></p>
                            <p>Fee: <span id="sell_fees">{{ number_format($tradingPair->trading_fee,2) }}</span>%</p>
                        </div>

                        <!-- Sell buttons -->
                        @auth
                        <div id="stopsellbutton" style="display:none"><button class="btn sell" onclick="placeOrder('sell','stop')">STOP SELL</button></div>
                        <div id="limitsellbutton"><button class="btn sell" onclick="placeOrder('sell','limit')">Sell</button></div>
                        <div id="marketsellbutton" style="display:none"><button class="btn sell" onclick="placeOrder('sell','market')">Sell</button></div>
                        @else
                        <div id="stopsellbutton" style="display:none"><button class="btn sell" onclick="promptLogin()">STOP SELL</button></div>
                        <div id="limitsellbutton"><button class="btn sell" onclick="promptLogin()">Sell</button></div>
                        <div id="marketsellbutton" style="display:none"><button class="btn sell" onclick="promptLogin()">Sell</button></div>
                        @endauth

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    // The JavaScript function that displays an alert message
    function promptLogin() {
        alert("Please login before you can continue.");
        // You could also add further logic here, 
        // such as redirecting the user to a login page:
        // window.location.href = "login.html"; 
    }
</script>











