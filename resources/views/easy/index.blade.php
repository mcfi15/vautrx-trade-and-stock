@extends('layouts.app')

@section('title', 'Dashboard - Crypto Trading Platform')

@section('content')


<section class="swap-page dark-bg pb-3">
    <div class="container">
        <div class="row justify-content-center inner-wrapper">
            <div class="col-12">
                <div class="easytrade-form-wrapper">
                    <div class="easytrade-form-inner">
                        <h1 class="text-center m-b-30">Easy Buy/Sell </h1>

                        <div class="easy-trade-from">
                            <select id="swapFrom" class="bootstrap-select selectpicker" data-live-search="true"
                                data-live-search-placeholder="Search" data-width="100%">

                                <option value="BTC" data-value="BTC" data-title="Bitcoin" data-balance="0"
                                    data-subtitle="BTC"
                                    data-content="<img src='Upload/coin/651a8b193619f.png'> Bitcoin <span>BTC</span> <span class='value'>111279.09 <span class='green'>2.114%</span></span>">
                                </option>
                                <option value="ETH" data-value="ETH" data-title="Ethereum" data-balance="0"
                                    data-subtitle="ETH"
                                    data-content="<img src='Upload/coin/651a8b39436f9.png'> Ethereum <span>ETH</span> <span class='value'>4369.37 <span class='green'>1.836%</span></span>">
                                </option>
                                <option value="LTC" data-value="LTC" data-title="Litcoin" data-balance="0"
                                    data-subtitle="LTC"
                                    data-content="<img src='Upload/coin/651d314f07b42.png'> Litcoin <span>LTC</span> <span class='value'>111.85 <span class='green'>2.53%</span></span>">
                                </option>
                                <option value="BNB" data-value="BNB" data-title="Bnb" data-balance="0"
                                    data-subtitle="BNB"
                                    data-content="<img src='Upload/coin/651a9f6b66163.png'> Bnb <span>BNB</span> <span class='value'>853.39 <span class='green'>1.326%</span></span>">
                                </option>
                                <option value="SOL" data-value="SOL" data-title="Solana" data-balance="0"
                                    data-subtitle="SOL"
                                    data-content="<img src='Upload/coin/651a9c6a85d78.png'> Solana <span>SOL</span> <span class='value'>210.35 <span class='green'>5.81%</span></span>">
                                </option>
                                <option value="TRX" data-value="TRX" data-title="Tron" data-balance="0"
                                    data-subtitle="TRX"
                                    data-content="<img src='Upload/coin/651a9c7bd8f28.png'> Tron <span>TRX</span> <span class='value'>0.339 <span class='green'>0.953%</span></span>">
                                </option>
                                <option value="ADA" data-value="ADA" data-title="Cardano" data-balance="0"
                                    data-subtitle="ADA"
                                    data-content="<img src='Upload/coin/651a9f81b173f.png'> Cardano <span>ADA</span> <span class='value'>0.833 <span class='green'>3.581%</span></span>">
                                </option>
                                <option value="XRP" data-value="XRP" data-title="XRP" data-balance="0"
                                    data-subtitle="XRP"
                                    data-content="<img src='Upload/coin/651d448ba8a85.png'> XRP <span>XRP</span> <span class='value'>2.853 <span class='green'>2.885%</span></span>">
                                </option>
                                <option value="DOGE" data-value="DOGE" data-title="Dogecoin" data-balance="0"
                                    data-subtitle="DOGE"
                                    data-content="<img src='Upload/coin/651d435be096b.png'> Dogecoin <span>DOGE</span> <span class='value'>0.216 <span class='green'>3.975%</span></span>">
                                </option>
                                <option value="LINK" data-value="LINK" data-title="Chainlink" data-balance="0"
                                    data-subtitle="LINK"
                                    data-content="<img src='Upload/coin/651a9f9c326ee.png'> Chainlink <span>LINK</span> <span class='value'>23.53 <span class='green'>3.338%</span></span>">
                                </option>
                                <option value="AVAX" data-value="AVAX" data-title="Avalanche" data-balance="0"
                                    data-subtitle="AVAX"
                                    data-content="<img src='Upload/coin/651d423522f4b.png'> Avalanche <span>AVAX</span> <span class='value'>24.95 <span class='green'>5.676%</span></span>">
                                </option>
                                <option value="BAT" data-value="BAT" data-title="Basic Attention Token" data-balance="0"
                                    data-subtitle="BAT"
                                    data-content="<img src='Upload/coin/651d427fd54c6.png'> Basic Attention Token <span>BAT</span> <span class='value'>0.156 <span class='green'>1.501%</span></span>">
                                </option>
                                <option value="BCH" data-value="BCH" data-title="Bitcoin Cash" data-balance="0"
                                    data-subtitle="BCH"
                                    data-content="<img src='Upload/coin/651d42ed456c3.png'> Bitcoin Cash <span>BCH</span> <span class='value'>598.8 <span class='green'>6.359%</span></span>">
                                </option>
                                <option value="ATOM" data-value="ATOM" data-title="Cosmos" data-balance="0"
                                    data-subtitle="ATOM"
                                    data-content="<img src='Upload/coin/651d4332420d5.png'> Cosmos <span>ATOM</span> <span class='value'>4.466 <span class='green'>2.267%</span></span>">
                                </option>
                                <option value="XMR" data-value="XMR" data-title="Monero" data-balance="0"
                                    data-subtitle="XMR"
                                    data-content="<img src='Upload/coin/651d439dc957d.png'> Monero <span>XMR</span> <span class='value'>118.7 <span class='green'>4.766%</span></span>">
                                </option>
                                <option value="ETC" data-value="ETC" data-title="Ethereum Classic" data-balance="0"
                                    data-subtitle="ETC"
                                    data-content="<img src='Upload/coin/651d437f05838.png'> Ethereum Classic <span>ETC</span> <span class='value'>20.84 <span class='green'>2.207%</span></span>">
                                </option>
                                <option value="DOT" data-value="DOT" data-title="Polkadot" data-balance="0"
                                    data-subtitle="DOT"
                                    data-content="<img src='Upload/coin/651d43cbcb830.png'> Polkadot <span>DOT</span> <span class='value'>3.825 <span class='green'>2.961%</span></span>">
                                </option>
                                <option value="XLM" data-value="XLM" data-title="Stellar" data-balance="0"
                                    data-subtitle="XLM"
                                    data-content="<img src='Upload/coin/651d4441ea44e.png'> Stellar <span>XLM</span> <span class='value'>0.364 <span class='green'>2.103%</span></span>">
                                </option>
                                <option value="WBTC" data-value="WBTC" data-title="Wrapped Bitcoin" data-balance="0"
                                    data-subtitle="WBTC"
                                    data-content="<img src='Upload/coin/651d446ea10b3.png'> Wrapped Bitcoin <span>WBTC</span> <span class='value'>111242.62 <span class='green'>2.013%</span></span>">
                                </option>
                                <option value="FIL" data-value="FIL" data-title="Filecoin" data-balance="0"
                                    data-subtitle="FIL"
                                    data-content="<img src='Upload/coin/651d46c120e49.png'> Filecoin <span>FIL</span> <span class='value'>2.278 <span class='green'>2.244%</span></span>">
                                </option>
                                <option value="VET" data-value="VET" data-title="VeChain" data-balance="0"
                                    data-subtitle="VET"
                                    data-content="<img src='Upload/coin/651d46df970c6.png'> VeChain <span>VET</span> <span class='value'>0.024 <span class='green'>2.538%</span></span>">
                                </option>
                                <option value="RUNE" data-value="RUNE" data-title="THORChain" data-balance="0"
                                    data-subtitle="RUNE"
                                    data-content="<img src='Upload/coin/651d46ed2c794.png'> THORChain <span>RUNE</span> <span class='value'>1.198 <span class='green'>3.187%</span></span>">
                                </option>
                                <option value="ZEC" data-value="ZEC" data-title="Zcash" data-balance="0"
                                    data-subtitle="ZEC"
                                    data-content="<img src='Upload/coin/651d46fec3190.png'> Zcash <span>ZEC</span> <span class='value'>42.23 <span class='green'>3.302%</span></span>">
                                </option>
                                <option value="WAVES" data-value="WAVES" data-title="Waves" data-balance="0"
                                    data-subtitle="WAVES"
                                    data-content="<img src='Upload/coin/651d470b0fc3f.png'> Waves <span>WAVES</span> <span class='value'>1.076 <span class='green'>11.967%</span></span>">
                                </option>
                                <option value="AAVE" data-value="AAVE" data-title="Aave" data-balance="0"
                                    data-subtitle="AAVE"
                                    data-content="<img src='Upload/coin/652ee8d20c09a.png'> Aave <span>AAVE</span> <span class='value'>322.47 <span class='green'>5.458%</span></span>">
                                </option>
                                <option value="APE" data-value="APE" data-title="Apecoin" data-balance="0"
                                    data-subtitle="APE"
                                    data-content="<img src='Upload/coin/652eebd119907.png'> Apecoin <span>APE</span> <span class='value'>0.575 <span class='green'>3.29%</span></span>">
                                </option>
                                <option value="DASH" data-value="DASH" data-title="Dash" data-balance="0"
                                    data-subtitle="DASH"
                                    data-content="<img src='Upload/coin/652eec18867f1.png'> Dash <span>DASH</span> <span class='value'>23.54 <span class='green'>4.437%</span></span>">
                                </option>
                                <option value="EOS" data-value="EOS" data-title="EOS" data-balance="0"
                                    data-subtitle="EOS"
                                    data-content="<img src='Upload/coin/652eec2dcce9c.png'> EOS <span>EOS</span> <span class='value'>0.78 <span class='red'>-0.662%</span></span>">
                                </option>
                                <option value="GALA" data-value="GALA" data-title="Gala" data-balance="0"
                                    data-subtitle="GALA"
                                    data-content="<img src='Upload/coin/652eec5cc15f1.png'> Gala <span>GALA</span> <span class='value'>0.016 <span class='green'>3.021%</span></span>">
                                </option>
                                <option value="IOTA" data-value="IOTA" data-title="Miota" data-balance="0"
                                    data-subtitle="IOTA"
                                    data-content="<img src='Upload/coin/652eec7cc0444.png'> Miota <span>IOTA</span> <span class='value'>0.188 <span class='green'>2.51%</span></span>">
                                </option>
                                <option value="NEO" data-value="NEO" data-title="Neo" data-balance="0"
                                    data-subtitle="NEO"
                                    data-content="<img src='Upload/coin/652eec8fe31b2.png'> Neo <span>NEO</span> <span class='value'>6.595 <span class='green'>1.197%</span></span>">
                                </option>
                                <option value="SAND" data-value="SAND" data-title="The Sand" data-balance="0"
                                    data-subtitle="SAND"
                                    data-content="<img src='Upload/coin/652eeca561d1a.png'> The Sand <span>SAND</span> <span class='value'>0.278 <span class='green'>3.497%</span></span>">
                                </option>
                                <option value="UNI" data-value="UNI" data-title="Uniswap" data-balance="0"
                                    data-subtitle="UNI"
                                    data-content="<img src='Upload/coin/652eecbd4ce7c.png'> Uniswap <span>UNI</span> <span class='value'>9.575 <span class='green'>2.659%</span></span>">
                                </option>
                            </select>
                            <ul class="nav nav-pills nav-fill m-t-40" role="tablist">
                                <li class="nav-item">
                                    <a aria-selected="true" class="nav-link active" data-toggle="pill"
                                        href="#easy-trade-buy" role="tab"><span
                                            class="coin-name etrade cointitle"></span> Buy</a>
                                </li>
                                <li class="nav-item">
                                    <a aria-selected="false" class="nav-link" data-toggle="pill" href="#easy-trade-sell"
                                        role="tab"><span class="coin-name etrade cointitle"></span> Sell</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active p-t-15" role="tabpanel" id="easy-trade-buy">
                                    <div class="input-group">
                                        <input class="form-control" placeholder="Amount in [usdt] you want to spend"
                                            type="number" id="buyamount" value="0">
                                        <div class="input-group-append">
                                            <button style="min-width: 2.5rem" class="btn btn-increment" type="button"
                                                tabindex="0" data-toggle="tooltip" data-html="true"
                                                title="0 USDT"><strong>USDT</strong>
                                            </button>
                                        </div>
                                    </div>
                                    <button id="buybutton" class="btn buy d-block w-100" onclick="trade(1);">
                                        <span class="coin-name etrade cointitle">--</span> Buy </button>
                                </div>
                                <div class="tab-pane fade p-t-15" role="tabpanel" id="easy-trade-sell">
                                    <div class="input-group">
                                        <input class="form-control" placeholder="Amount you want to sell (USDT)"
                                            type="number" value="" id="sellamount">
                                        <div class="input-group-append">
                                            <button style="min-width: 2.5rem" class="btn btn-increment" type="button"
                                                tabindex="0" data-toggle="tooltip" data-html="true"
                                                title="<span class='etrade coinname'></span>">
                                                <strong class="coinname"></strong></button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="selectedcoin" value="" />
                                    <button class="btn sell d-block w-100" onclick="trade(2);">
                                        <span class="coin-name etrade cointitle">--</span> Sell </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    jQuery(document).ready(function ($) {
        var base_coin_name = document.querySelector('select#swapFrom').selectedOptions[0].getAttribute('data-value');
        var base_coin_title = document.querySelector('select#swapFrom').selectedOptions[0].getAttribute('data-title');
        var base_coin_balance = document.querySelector('select#swapFrom').selectedOptions[0].getAttribute('data-balance');
        $(".coinname").html(base_coin_name);
        $(".cointitle").html(base_coin_title);
        $("#selectedcoin").val(base_coin_name);
        $("#sellamount").val(base_coin_balance);
        document.querySelector('select#swapFrom').onchange = function () {
            var base_coin_name = document.querySelector('select#swapFrom').selectedOptions[0].getAttribute('data-value');
            var base_coin_title = document.querySelector('select#swapFrom').selectedOptions[0].getAttribute('data-title');
            var base_coin_balance = document.querySelector('select#swapFrom').selectedOptions[0].getAttribute('data-balance');
            $(".coinname").html(base_coin_name);
            $(".cointitle").html(base_coin_title);
            $("#selectedcoin").val(base_coin_name);
            $("#sellamount").val(base_coin_balance);
        };
    });
    function trade(type) {
        if (type == 1) {
            var amount = $("#buyamount").val();
            if (amount == '' || amount == 0) {
                layer.tips('Please enter amount!', '#buyamount', { tips: 3 });
                return false;
            }
        }
        if (type == 2) {
            var amount = $("#sellamount").val();
            if (amount == '' || amount == 0) {
                layer.tips('Please enter amount!', '#sellamount', { tips: 3 });
                return false;
            }
        }


        var coin = document.querySelector('select#swapFrom').selectedOptions[0].getAttribute('data-value');
        if (coin == '' || coin == null) {
            layer.tips('Please select coin!', '#swapFrom', { tips: 3 });
            return false;
        }

        layer.load(0, { shade: [0.5, '#8F8F8F'] });
        $.post("/Easy/doTrade", {
            coin: coin,
            amount: amount,
            type: type,

        }, function (data) {
            layer.closeAll('loading');
            if (data.status == 1) {
                layer.msg(data.info, { icon: 1 });
            } else {
                layer.alert(data.info, { title: "Info", btn: ['Ok'] });
            }
        }, "json");
    }
</script>

@endsection