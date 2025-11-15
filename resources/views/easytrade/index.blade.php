@extends('layouts.app')

@section('title', 'Easy Trade')

@section('content')

<section class="swap-page dark-bg pb-3">
    <div class="container">
        <div class="row justify-content-center inner-wrapper">
            <div class="col-12">
                <div class="easytrade-form-wrapper">
                    <div class="easytrade-form-inner">
                        <h1 class="text-center m-b-30">Easy Buy/Sell</h1>

                        <div class="easy-trade-from">
                            <select id="swapFrom" class="bootstrap-select selectpicker" data-live-search="true"
                                data-live-search-placeholder="Search" data-width="100%">
                                @foreach($cryptos as $crypto)
                                    @php
    $change = $crypto->price_change_percentage_24h;
    $color = $change >= 0 ? 'green' : 'red';
@endphp

<option value="{{ $crypto->symbol }}"
    data-value="{{ $crypto->symbol }}"
    data-title="{{ $crypto->name }}"
    data-balance="{{ $balances[$crypto->symbol] ?? 0 }}"
    data-subtitle="{{ $crypto->symbol }}"
    data-content="<img src='{{ $crypto->logo_url ?? asset('default-coin.png') }}'> {{ $crypto->name }} 
                  <span>{{ $crypto->symbol }}</span> 
                  <span class='value'>{{ $crypto->current_price }} 
                  <span class='{{ $color }}'>{{ $change }}%</span></span>">
</option>

                                @endforeach
                            </select>

                            <ul class="nav nav-pills nav-fill m-t-40" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="pill" href="#easy-trade-buy" role="tab">
                                        <span class="coin-name etrade cointitle"></span> Buy
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="pill" href="#easy-trade-sell" role="tab">
                                        <span class="coin-name etrade cointitle"></span> Sell
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade show active p-t-15" role="tabpanel" id="easy-trade-buy">
                                    <div class="input-group">
                                        <input class="form-control" placeholder="Amount in [usdt] you want to spend"
                                            type="number" id="buyamount" value="0">
                                        <div class="input-group-append">
                                            <button style="min-width: 2.5rem" class="btn btn-increment" type="button">
                                                <strong>USDT</strong>
                                            </button>
                                        </div>
                                    </div>
                                    <button id="buybutton" class="btn buy d-block w-100" onclick="trade(1);">
                                        <span class="coin-name etrade cointitle">--</span> Buy
                                    </button>
                                </div>

                                <div class="tab-pane fade p-t-15" role="tabpanel" id="easy-trade-sell">
                                    <div class="input-group">
                                        <input class="form-control" placeholder="Amount you want to sell (USDT)"
                                            type="number" value="" id="sellamount">
                                        <div class="input-group-append">
                                            <button style="min-width: 2.5rem" class="btn btn-increment" type="button">
                                                <strong class="coinname"></strong>
                                            </button>
                                        </div>
                                    </div>
                                    <button class="btn sell d-block w-100" onclick="trade(2);">
                                        <span class="coin-name etrade cointitle">--</span> Sell
                                    </button>
                                </div>
                            </div>
                        </div><!-- easy-trade-from -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/layer/dist/layer.js"></script>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const IS_LOGGED_IN = @json(auth()->check());
    const LOGIN_URL = "{{ route('login') }}";

    console.log("JS LOADED ✓");

    let opt = document.querySelector("#swapFrom").selectedOptions[0];
    updateCoinInfo(opt);

    document.querySelector("#swapFrom").addEventListener("change", function () {
        updateCoinInfo(this.selectedOptions[0]);
    });

    function updateCoinInfo(opt) {
        console.log("Selected coin:", opt.dataset.value);
        document.querySelectorAll(".cointitle").forEach(e => e.innerHTML = opt.dataset.title);
        document.querySelectorAll(".coinname").forEach(e => e.innerHTML = opt.dataset.value);
        document.querySelector("#sellamount").value = opt.dataset.balance;
    }

    window.trade = function (type) {

        console.log("TRADE CLICKED:", type);

        if (!IS_LOGGED_IN) {
            alert("You must log in before trading.");
            window.location.href = LOGIN_URL;
            return;
        }

        const amount = (type === 1)
            ? document.querySelector("#buyamount").value
            : document.querySelector("#sellamount").value;

        if (!amount || amount <= 0) {
            alert("Enter amount");
            return;
        }

        const coin = document.querySelector("#swapFrom").selectedOptions[0].dataset.value;

        console.log("Sending trade request:", {amount, coin, type});

        $.post("{{ route('easytrade.doTrade') }}", {
            _token: "{{ csrf_token() }}",
            amount: amount,
            type: type,
            coin: coin
        }).done(function (data) {
            console.log("SERVER RESPONSE:", data);
            alert(data.info);
        }).fail(function (xhr) {
            console.log("SERVER ERROR:", xhr.responseText);
            alert("Trade failed. Server error.");
        });

    };

});
</script>
@endpush

