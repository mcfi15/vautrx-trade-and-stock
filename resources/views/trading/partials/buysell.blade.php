<style>
/* Mobile First Approach */
.trade-panels-container {
    display: block;
}

/* Desktop: Side by side */
@media (min-width: 768px) {
    .trade-panels-container {
        display: flex;
        gap: 20px;
    }
    .market-trade-buy,
    .market-trade-sell {
        flex: 1;
    }
}

/* Mobile optimizations */
@media (max-width: 767px) {
    .market-trade-list {
        font-size: 14px;
    }
    .market-trade-list li {
        margin: 0 5px;
    }
    .input-group {
        flex-wrap: nowrap;
    }
    .input-group .form-control {
        min-width: 0; /* Allow shrinking */
    }
}

/* Button styles */
.btn-success {
    background-color: #28a745;
    border-color: #28a745;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-success:hover, .btn-danger:hover {
    opacity: 0.9;
}
</style>

<!-- Trading Panel -->
<div class="col-md-12 buysell_s4">
       <div class="market-trade">
        <input type="hidden" id="tradingPairId" value="{{ $tradingPair->id }}">
    <div class="d-flex justify-content-between align-items-center">
        <ul id="orderTypeTabs" class="nav nav-pills mb-3" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" href="javascript:void(0)" data-type="limit">Limit</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="javascript:void(0)" data-type="market">Market</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="javascript:void(0)" data-type="stop">Stop-Limit</a>
            </li>
        </ul>

        <div class="hide-mobile">
            <button class="btn btn-sm btn-outline-primary">
                <a href="{{ url('wallet') }}">Transfer</a>
            </button>
        </div>
    </div>
    

    <div class="tab-content">
        <div class="tab-pane fade show active" id="pills-trade-limit" role="tabpanel">
            <div class="outflexboxx d-flex justify-content-between">
                <!-- Buy Panel -->
                <div class="market-trade-buy">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="w-full">
                            Balance:
                            <span>{{ number_format($quoteWallet->available_balance ?? (($quoteWallet->balance ?? 0) - ($quoteWallet->locked_balance ?? 0)), 8) }}</span>
                            {{ $tradingPair->quoteCurrency->symbol }}
                            </span>
                            <span class="pull-right hide-mobile">
                                <a id="deposit-href" href="{{ url('wallet/deposit') }}" class="green">
                                    <i class="fa fa-download"></i> Deposit
                                </a>
                            </span>
                        </p>
                    </div>
                    <div class="input-group" id="buystop" style="display: none">
                        <input class="form-control" placeholder="Stop" type="number" id="buy_stop" name="stop"
                            step="0.00000001" />
                        <div class="input-group-append">
                            <button style="min-width: 2.5rem" class="btn btn-increment hide-mobile" type="button"
                                onclick="incrementValue('buy_stop', 0.01)">
                                <strong>+</strong>
                            </button>
                            <span class="input-group-text">{{ $tradingPair->quoteCurrency->symbol }}</span>
                        </div>
                    </div>
                    <div class="input-group" id="buypricebox">
                        <input class="form-control" placeholder="Price" type="number" id="buy_price" name="price"
       step="0.00000001" value="{{ number_format($currentPrice, 8, '.', '') }}" />
                        <div class="input-group-append">
                            <button style="min-width: 2.5rem" class="btn btn-increment hide-mobile" type="button"
                                onclick="incrementValue('buy_price', 0.01)">
                                <strong>+</strong>
                            </button>
                            <span class="input-group-text">{{ $tradingPair->quoteCurrency->symbol }}</span>
                        </div>
                    </div>
                    <div class="input-group">
                        <input class="form-control" placeholder="Qty" type="number" id="buy_num" name="num"
                            step="0.00000001" />
                        <div class="input-group-append">
                            <button style="min-width: 2.5rem" class="btn btn-increment hide-mobile" type="button"
                                onclick="incrementValue('buy_num', 0.001)">
                                <strong>+</strong>
                            </button>
                            <span class="input-group-text">{{ $tradingPair->baseCurrency->symbol }}</span>
                        </div>
                    </div>
                    <input type="range" name="buy_range" id="buy_range" min="0" max="100" value="0"
                        oninput="updateFromRange('buy', this.value)" />
                    <ul class="market-trade-list">
                        <li><a href="#!" onclick="setPercentage('25','buy')">25%</a></li>
                        <li><a href="#!" onclick="setPercentage('50','buy')">50%</a></li>
                        <li><a href="#!" onclick="setPercentage('75','buy')">75%</a></li>
                        <li><a href="#!" onclick="setPercentage('100','buy')">100%</a></li>
                    </ul>
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="m-b-0">Total: <span id="buy_mum">--</span></p>
                        <p class="m-b-0">Fee: <span
                                id="buy_fees">{{ number_format($tradingPair->trading_fee, 2) }}</span>%</p>
                    </div>
                    @auth
                    <div id="stopbuybutton" style="display: none">
                        <button value="STOP BUY" onclick="placeOrder('buy', 'stop')" class="btn buy-trade">
                            STOP BUY
                        </button>
                    </div>
                    <div id="limitbuybutton">
                        <button value="Buy" onclick="placeOrder('buy', 'limit')" class="btn buy-trade m-t-0">
                            Buy
                        </button>
                    </div>
                    <div id="marketbuybutton" style="display: none">
                        <button value="Buy" onclick="placeOrder('buy', 'market')" class="btn buy-trade">
                            Buy
                        </button>
                    </div>
                    @else
                    <div id="stopbuybutton" style="display: none">
                        <button onclick="promptLogin()" class="btn buy-trade">
                            STOP BUY
                        </button>
                    </div>
                    <div id="limitbuybutton">
                        <button onclick="promptLogin()" class="btn buy-trade m-t-0">
                            Buy
                        </button>
                    </div>
                    <div id="marketbuybutton" style="display: none">
                        <button onclick="promptLogin()" class="btn buy-trade">
                            Buy
                        </button>
                    </div>
                    @endauth
                </div>

                <!-- Sell Panel -->
                <div class="market-trade-sell">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="w-full">
                            Balance:
                            <span>
                                <span id="user_coin">
                                    {{ number_format($baseWallet->available_balance ?? (($baseWallet->balance ?? 0) - ($baseWallet->locked_balance ?? 0)), 8) }}</span>
                                {{ $tradingPair->baseCurrency->symbol }}
                            </span>
                            <span class="pull-right hide-mobile">
                                <a href="{{ url('wallet/deposit') }}" class="red">
                                    <i class="fa fa-download"></i> Deposit
                                </a>
                            </span>
                        </p>
                    </div>
                    <div class="input-group" id="sellstop" style="display: none">
                        <input class="form-control" placeholder="Stop" type="number" id="sell_stop" name="sellstop"
                            step="0.00000001" />
                        <div class="input-group-append">
                            <button style="min-width: 2.5rem" class="btn btn-increment hide-mobile" type="button"
                                onclick="incrementValue('sell_stop', 0.01)">
                                <strong>+</strong>
                            </button>
                            <span class="input-group-text">{{ $tradingPair->quoteCurrency->symbol }}</span>
                        </div>
                    </div>
                    <div class="input-group" id="sellpricebox">
                        <input class="form-control" placeholder="Price" type="number" id="sell_price" name="price"
       step="0.00000001" value="{{ number_format($currentPrice, 8, '.', '') }}" />
                        <div class="input-group-append">
                            <button style="min-width: 2.5rem" class="btn btn-increment hide-mobile" type="button"
                                onclick="incrementValue('sell_price', 0.01)">
                                <strong>+</strong>
                            </button>
                            <span class="input-group-text">{{ $tradingPair->quoteCurrency->symbol }}</span>
                        </div>
                    </div>
                    <div class="input-group">
                        <input class="form-control" placeholder="Qty" type="number" id="sell_num" name="num"
                            step="0.00000001" />
                        <div class="input-group-append">
                            <button style="min-width: 2.5rem" class="btn btn-increment hide-mobile" type="button"
                                onclick="incrementValue('sell_num', 0.001)">
                                <strong>+</strong>
                            </button>
                            <span class="input-group-text">{{ $tradingPair->baseCurrency->symbol }}</span>
                        </div>
                    </div>
                    <input type="range" name="sell_range" id="sell_range" min="0" max="100" value="0"
                        oninput="updateFromRange('sell', this.value)" />
                    <ul class="market-trade-list">
                        <li><a href="#!" onclick="setPercentage('25','sell')">25%</a></li>
                        <li><a href="#!" onclick="setPercentage('50','sell')">50%</a></li>
                        <li><a href="#!" onclick="setPercentage('75','sell')">75%</a></li>
                        <li><a href="#!" onclick="setPercentage('100','sell')">100%</a></li>
                    </ul>
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="m-b-0">Total: <span id="sell_mum">--</span></p>
                        <p class="m-b-0">Fee: <span
                                id="sell_fees">{{ number_format($tradingPair->trading_fee, 2) }}</span>%</p>
                    </div>

                    @auth
                    <div id="stopsellbutton" style="display: none">
                        <button value="STOP SELL" onclick="placeOrder('sell', 'stop')" class="btn sell">
                            STOP SELL
                        </button>
                    </div>
                    <div id="limitsellbutton">
                        <button onclick="placeOrder('sell', 'limit')" class="btn sell m-t-0">
                            Sell
                        </button>
                    </div>
                    <div id="marketsellbutton" style="display: none">
                        <button onclick="placeOrder('sell', 'market')" class="btn sell">
                            Sell
                        </button>
                    </div>
                    @else
                    <form action=""></form>
                    <div id="stopsellbutton" style="display: none">
                        <button onclick="promptLogin()" class="btn sell">
                            STOP SELL
                        </button>
                    </div>
                    <div id="limitsellbutton">
                        <button onclick="promptLogin()" class="btn sell m-t-0">
                            Sell
                        </button>
                    </div>
                    <div id="marketsellbutton" style="display: none">
                        <button onclick="promptLogin()" class="btn sell">
                            Sell
                        </button>
                    </div>
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











