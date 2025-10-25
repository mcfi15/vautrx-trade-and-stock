@extends('layouts.app')

@section('title', 'Deposit ' . ($cryptocurrency->name ?? 'Cryptocurrency'))

@section('content')

{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

    {{-- <main class="wrapper grey-bg"></main>
    </body>


    <div class="container mt-3 mb-3">
        <div class="row">
            <div class="col-12 col-md-6 order-2 order-md-1">
                <div class="page-title-content d-flex align-items-start mt-2">
                    <span>Welcome, <span> suiylath!</span> <br /></span>
                </div>
            </div>
            

            <div class="col-12 col-md-6 order-1 order-md-2 float-right">
                <ul class="text-right breadcrumbs list-unstyle">
                    <li>
                        <a class="btn btn-warning btn-sm " href="/Finance/index">Finance</a>
                    </li>
                    <li>
                        <a class="btn btn-warning btn-sm active" href="/wallet/cryptodeposit">Crypto Deposit</a>
                    </li>
                    <li>
                        <a class="btn btn-warning btn-sm" href="/Finance/mycz">Fiat Deposit</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container ">
        <div class="row  mb-3">

            

            <div class="col-12 ">
                
                <div class="deposit-crypto-alert   row alert-info mt-20 p-20 visible-lg-block 
                    " >

                    

                    <div class="col-lg-3">
                        <span class="number">1</span>
                        <div class="title">Copy address</div>
                        <div class="desc">On this page select the crypto and its network and copy the deposit address.</div>
                    </div>
                    <div class="col-lg-3">
                        <span class="number">2</span>
                        <div class="title">Make a deposit</div>
                        <div class="desc">Complete the deposit process.</div>
                    </div>
                    <div class="col-lg-3">
                        <span class="number">3</span>
                        <div class="title">Network confirmation</div>
                        <div class="desc">Wait for the blockchain network to confirm your transfer.</div>
                    </div>
                    <div class="col-lg-3">
                        <span class="number">4</span>
                        <div class="title">Deposit completed</div>
                        <div class="desc">After network confirmation, Dectrx will deposit the crypto into your account.
                        </div>
                    </div>

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
            <div class="col-md-6 card mt-3 m-b-40 ">
                <div class="card-body">

                    <div class="form-group">
                        <label> Select Coin</label>
                        <select id="depositCyrpto" class="bootstrap-select" data-live-search="true"
                            data-live-search-placeholder='Search' data-width="100%">
                            <option value="aave" data-value="aave"
                                data-content="<img src='{{ $wallet->cryptocurrency->logo_url }}' height='20px !important' width='20px !important'/> {{ strtoupper($wallet->cryptocurrency->symbol ?? 'N/A') }}">
                            </option>
                           
                        </select>
                    </div>
                    <div class="form-group">
                        <label> Network</label>

                        <select id="selectNetwork" class="bootstrap-select" data-live-search="true"
                            data-live-search-placeholder='Search' data-width="100%">

                            <option selected="selected" value="vet" data-value="vet"
                                data-content="VET <span>VeChain</span>"></option>
                        </select>
                        <small>Make sure the network you choose for the deposit matches the withdrawal network or your
                            assets may be lost.</small>
                    </div>



                    <div class="alert alert-warning alert-dismissible fade show" role="alert" id="networkMessage">

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <ul class="form-info row" id="walletinfo">
                        <li class="col-12">
                            <h6>Address</h6>
                            <div class="address-box">
                                <div id="copyAddress" class="h6 font-weight-bold">--</div>
                                <div class="pull-right text-right ">
                                    <span class="address-icon">
                                        <a role="button" data-toggle="popover" data-placement="top" data-content="Copied"
                                            trigger="hover" onclick="copyToClipboard('#copyAddress')" class="btn "
                                            data-original-title="" title=""><i class="fa  fa-copy fa-2x"></i></a>
                                    </span>

                                    <button type="button" class="btn " data-toggle="modal" data-target="#qr-show-box">
                                        <i class="fa fa-qrcode fa-2x"></i>
                                    </button>
                                </div>
                            </div>
                            <span id="qr2" class="address-box text-center" style="display: none;"></span>
                        </li>
                        <br>



                        <li class="col-6">
                            <h6>Expected Arrival</h6>
                            <div class="text-bold">
                                <label><span id="networkConfirmations" class="font-weight-bold">--</span> network
                                    confirmation/s</label>
                            </div>
                            <hr>
                        </li>


                        <li class="col-6">
                            <h6>Coin</h6>
                            <div class="text-bold">
                                <label>Only send <span id="networkName">-</span>
                                    <!--                       -->

                                </label>
                            </div>
                        </li>
                        <li class="col-6" style="margin-top: 10px;">
                            <h6>Network</h6>
                            <div class="text-bold">
                                <label>Make sure the network is <span id="networkTitle">-</span></label>
                            </div>
                        </li>

                    </ul>

                </div>
            </div>

            <div class="card col-md-5 mt-3 m-b-40  offset-md-1">
                <div class="card-body">
                    <h6 class="text-bold">
                        Facing Deposit Issues?
                    </h6>
                    <div class="card">

                    </div>
                    <div class="white-bg">
                        If you experience challenges during your deposit process, consider the following:
                        <ul class="orig">
                            <li>Ensure the correct Deposit Status Inquiry to check on your deposit status.</li>
                            <li>Verify that the correct MEMO/Label was entered.</li>
                            <li>Check that you haven't deposited any unlisted coins.</li>
                        </ul>
                        <!--a href="/Finance/trace" class="btn yellow-bg btn-sm">Trace Deposit</a-->
                    </div>
                    <hr />
                    <div class="white-bg">
                        Need Further Assistance?
                        <ul class="orig">
                            <li>Contact our Support Team.</li>
                            <li>Familiarize yourself with common issues related to cryptocurrencies deposited with incorrect
                                or missing information.</li>
                            <li>Learn more about purchasing cryptocurrencies.</li>
                        </ul>
                    </div>
                </div>
                <!--div class="card-body">
                        <h6 class="text-bold">FAQ</h6>

                        <ul class="orig">
                            <li><a href="">Video Guide</a></li>
                            <li><a href="">Step-by-Step Crypto Deposit Guide</a></li>
                            <li><a href="">Cryptocurrencies Deposited with Wrong or Missing Aktifet/Memo</a></li>
                            <li><a href="">How to buy Cryptocurrency</a></li>
                        </ul>
                    </div-->

            </div>
        </div>
    </div>
    <div class="container">
        <div class="row card">
            <div class="card-header">
                <div class="card-title">Recent Deposits</div>

            </div>
            <div class="card-body table-responsive">
                <table class="table text-center" id="investLog_content">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Address</th>
                            <th>Amount</th>

                            <th>Time</th>
                            <th>Hash</th>
                        </tr>
                    </thead>
                    <tbody>



                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- NO HEADER Modal START-->
    <div class="modal fade no-header-modal success-modal" id="qr-show-box" tabindex="-1" aria-labelledby="qr-show-boxx"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="icon">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <div class="title">
                        Scan QR code for deposit address
                    </div>
                    <div class="desc" id="qr3"></div>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-4 m-auto" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!--  NO HEADER Modal END -->
    <span style="display:none">
        <span id="qrcode1" class="qr_code_get"></span>
        <span id="qrcode2"></span>
    </span>


    <!-- Transparent background overlay -->
    <div class="cy-popup-overlay" id="popupOverlay">
        <!-- Popup content -->
        <div class="cy-popup">
            <!-- Loading spinner -->
            <div class="cy-spinner" id="spinner"></div>
            <!-- Success checkmark -->
            <div class="cy-checkmark" id="checkmark">
                ✓
            </div>
            <!-- Status text -->
            <p class="cy-status-text" id="statusText">Verifying Payment...</p>
        </div>
    </div>
    <style>
        /* Popup overlay */
        .cy-popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        /* Popup container */
        .cy-popup {
            background: white;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
            width: 300px;
        }

        /* Spinner */
        .cy-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #007bff;
            border-radius: 50%;
            animation: cy-spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        /* Checkmark */
        .cy-checkmark {
            display: none;
            font-size: 40px;
            color: green;
            margin: 0 auto 20px;
        }

        /* Status text */
        .cy-status-text {
            font-size: 18px;
            color: #333;
        }

        /* Spinner animation */
        @keyframes cy-spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <script type="text/javascript" src="/Public/Home/js/jquery.qrcode.min.js"></script>
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
        function wavesRefresh() {
            $.get("/IPN/WavesDeposit", function (data) {
                alert("Deposits checked!.");
            })
        }
        jQuery(document).ready(function ($) {

            var selectNetwork = $('select#selectNetwork').find(":selected").val();

            changeAddress(selectNetwork);
        });

        document.querySelector('select#selectNetwork').onchange = function () {
            var selectNetwork = this.selectedOptions[0].getAttribute('data-value');

            changeAddress(selectNetwork);
        };
        document.querySelector('select#depositCyrpto').onchange = function () {
            var value = this.selectedOptions[0].getAttribute('data-value');
            var prefix = "coin";
            var location = window.location.href;
            var url = new URL(location);
            url.searchParams.append(prefix, value);
            url.searchParams.set(prefix, value);
            window.location.href = url;
        };

        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) {
                return parts.pop().split(';').shift();
            }
            return null;
        }
        function changeAddress(coin) {
            var networkCoins = '{"vet":{"name":"vet","title":"VeChain","network":"","tokenof":"0","img":".\/Upload\/coin\/651d46df970c6.png","deposit":"1","confirmations":"1","explorer":"","wallet":"0x3134E1Bd1A24427d088eD948e71A931631ED09C2","message":null,"qr":null,"dest_tag":null,"public_key":"","verify_transaction_type":"no"}}';
            const obj = JSON.parse(networkCoins);
            var coininfo = obj[coin];
            let coin_name = coininfo.name;
            $("#networkName").html(coin_name.toUpperCase());
            $("#coinNameV").html(coin_name.toUpperCase());
            $("#networkName2").html(coininfo.name);
            if (coininfo.network == 'other') {
                if (coininfo.tokenof == 0) {
                    $("#networkTitle").html(coininfo.name);
                } else {
                    $("#networkTitle").html(coininfo.tokenof);
                }
            } else {
                $("#networkTitle").html(coininfo.network);
            }
            $("#networkTitle2").html(coininfo.title);
            $("#networkConfirmations").html(coininfo.confirmations);
            $("#automaticVerification").html(coininfo.verify_transaction_type ?? 'no');

            $("#copyAddress").html(coininfo.wallet);
            generateQRCode(coininfo.wallet, coininfo.message);


            // if public key is set for any coin we will generate wallet address using the public key
            let publicKey = coininfo.public_key ?? '';
            let message = coininfo.message;
            if (publicKey.length > 1) {
                $.ajax({
                    url: 'https://dectrx.com/transaction-verification/api.php?action=generate_address&xpub_key=' + publicKey,
                    type: 'get',
                    dataType: 'JSON',
                    complete: (response) => {
                        let __data = response.responseJSON
                        if (__data !== undefined && __data.address !== undefined) {
                            $("#copyAddress").html(__data.address);
                            generateQRCode(__data.address, message);
                        }
                    }
                })

            }


            if (coininfo.dest_tag != null) {

                $('#qrcodeMemo').qrcode({
                    render: "table", //table
                    size: 150,
                    text: coininfo.dest_tag, //Any content
                    background: "#ffffff"
                });
                $("#dest_tag").show();
                $("#networkMemo").html(coininfo.dest_tag);
            }

        }

        function roundUpTo6Decimals(value) {
            return Math.ceil(value * 1e6) / 1e6;
        }

        function roundUpTo3Decimals(value) {
            return Math.ceil(value * 1e3) / 1e3;
        }

        function startTransactionVerification() {

            let walletAmount = document.getElementById('transaction_equivalent_amount').value;
            let coin = $('#automaticVerification').text().trim();
            let address = $('#copyAddress').text().trim();
            let network = $("#networkTitle").text().trim().toLowerCase();
            if (network.length < 2 || network === undefined || network === null) {
                network = ''
            }
            document.getElementById('popupOverlay').style.display = 'flex';

            // set interval to check if payment has been made
            let payment_verification_interval = setInterval(() => {
                let __loggedUser = getCookie('exchange_email');
                $.ajax({
                    url: 'https://dectrx.com/transaction-verification/api.php?action=verify_transaction&coin=' + coin + '&amount=' + walletAmount + '&address=' + address + '&user=' + __loggedUser + '&network=' + network,
                    data: {},
                    dataType: 'JSON',
                    complete: (response) => {
                        let __data = response.responseJSON

                        if (__data.status === 'success') {
                            $(".cy-spinner").hide();
                            $(".cy-checkmark").show();
                            $(".cy-status-text").text("Payment Successful!");
                            clearInterval(payment_verification_interval);

                            setTimeout(() => {
                                $(".cy-popup-overlay").hide();
                                // redirect user to the wallet page
                                window.location.href = "https://dectrx.com/Finance/index";
                            }, 2500)

                        }
                    }
                })
            }, 4000);



        }


        function generateQRCode(wallet, message = '') {
            if (wallet == false) {

                $("#walletinfo").hide();
                $("#networkMessage").html(message);
            } else {
                $('#qrcode1').html('');

                $('#qrcode1').qrcode({
                    render: "table", //table
                    size: 150,
                    text: wallet, //Any content
                    background: "#ffffff"
                });
                let imgcontent = $('#qrcode1').html();
                let content = '<div class="text-bold"><span style="justify-content: center;display: flex;">imghere</span><span class="address-box">' + wallet + '</span></div>';
                let newcontent = content.replace(/imghere/g, imgcontent);

                $("#qr1").attr('data-content', newcontent);
                $("#qr3").html(newcontent);

                $("#walletinfo").show();
                $("#networkMessage").hide();
            }
        }
        function showQrCode() {
            if ($("#qr2").is(":visible")) {
                $("#qr2").hide();
            } else {
                $("#qr2").show();
            }
        }


        async function getEquivalentPrice() {

            let currency = document.querySelector('select[name=transaction_currency]').value
            let amount = document.getElementById('transaction_amount')
            let currency_amount = amount.value
            let walletType = $('#automaticVerification').text().toLowerCase().trim();

            let walletAmount = document.getElementById('transaction_equivalent_amount');

            try {

                let response = await fetch("https://api.coingecko.com/api/v3/coins/markets?vs_currency=" + currency + "&order=market_cap_desc&per_page=100&page=1&sparkline=false", {
                    method: "get",
                    headers: {
                        "Content-Type": "application/json",
                        "Access-Control-Allow-Origin": "*",
                    }
                })

                let data = await response.json()
                let __coin = data.filter(e => e.symbol === walletType)[0]
                let __price = __coin.current_price;

                if (walletType === 'usdt') {
                    walletAmount.value = roundUpTo3Decimals(currency_amount / __price);
                }
                else {
                    walletAmount.value = roundUpTo6Decimals(currency_amount / __price);
                }


            } catch (error) {
                console.error(error)
            }

        }

        function showtx(tx) {
            layer.alert(tx, { title: "Info", btn: ['Ok'] });
        }
    </script> --}}




 

    <style>
        /* body {
            background-color: #0d1117;
            color: #e6edf3;
            font-family: 'Inter', sans-serif;
        } */
        .accent {
            color: #ffbe40 !important;
        }
        .bg-accent {
            background-color: #ffbe40 !important;
        }
        .btn-accent {
            background-color: #ffbe40;
            color: #000;
            border: none;
        }
        .btn-accent:hover {
            background-color: #ffca61;
            color: #000;
        }
        .card {
            background-color: #161b22;
            border: 1px solid #30363d;
            border-radius: 1rem;
        }
        .form-control, .input-group-text {
            background-color: #0d1117;
            border: 1px solid #30363d;
            color: #e6edf3;
        }
        .form-control:focus {
            border-color: #ffbe40;
            box-shadow: 0 0 0 0.25rem rgba(255,190,64,0.25);
        }
        .alert-warning {
            background-color: rgba(255, 190, 64, 0.1);
            border-color: #ffbe40;
            color: #ffbe40;
        }
        .card-title {
            color: #ffbe40;
        }
        .hover-glow:hover {
            box-shadow: 0 0 12px rgba(255,190,64,0.4);
            transition: 0.3s ease-in-out;
        }
        .badge {
            font-size: 0.75rem;
        }
    </style>

    <!-- Header -->
    <div class="py-5 mb-5 text-center bg-black border-bottom border-secondary">
        <div class="container">
            <h1 class="fw-bold mb-2 text-light">Deposit {{ $cryptocurrency->name ?? 'Cryptocurrency' }}</h1>
            <p class="text-muted fs-5">
                Send <span class="accent">{{ strtoupper($cryptocurrency->symbol ?? '') }}</span> to your wallet address
            </p>
        </div>
    </div>

    <main class="container pb-5">
        <div class="row g-4">
            <!-- Deposit Information -->
            <div class="col-lg-6">
                <div class="card p-4 hover-glow">
                    <h2 class="h5 card-title mb-4 fw-semibold">Deposit Information</h2>

                    <div class="mb-4">
                        <label class="form-label small text-secondary">Wallet Address</label>
                        <div class="input-group">
                            <input type="text" id="depositAddress"
                                class="form-control"
                                readonly
                                value="{{ $wallet->address ?? 'Generating address...' }}">
                            <button onclick="copyAddress()" class="btn btn-accent fw-semibold">
                                <i class="fa fa-copy me-1"></i> Copy
                            </button>
                        </div>
                        @if(!$wallet->address)
                        <small class="text-warning mt-2 d-block">
                            <i class="fa fa-exclamation-triangle me-1"></i>
                            Address is being generated. Please wait...
                        </small>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label class="form-label small text-secondary">Network</label>
                        <div class="form-control bg-transparent">
                            {{ $cryptocurrency->network ?? 'Main Network' }}
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-secondary">Minimum Deposit</label>
                            <div class="form-control bg-transparent">
                                {{ number_format($cryptocurrency->min_deposit_amount ?? 0.001, 8) }} {{ strtoupper($cryptocurrency->symbol ?? '') }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-secondary">Current Balance</label>
                            <div class="form-control bg-transparent">
                                {{ number_format($wallet->balance ?? 0, 8) }} {{ strtoupper($cryptocurrency->symbol ?? '') }}
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <div class="border border-secondary rounded p-4 d-inline-block bg-dark-subtle bg-opacity-10">
                            <div class="d-flex align-items-center justify-content-center rounded bg-dark"
                                 style="width: 160px; height: 160px;">
                                <i class="fa fa-qrcode fs-1 text-secondary"></i>
                            </div>
                            <p class="text-secondary small mt-3 mb-0">QR Code (Coming Soon)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deposit Instructions -->
            <div class="col-lg-6">
                <div class="card p-4 hover-glow">
                    <h2 class="h5 card-title mb-4 fw-semibold">How to Deposit</h2>

                    @php
                        $steps = [
                            ['icon'=>'fa fa-copy', 'title'=>'Copy Address', 'text'=>'Copy the wallet address above or scan the QR code'],
                            ['icon'=>'fa fa-paper-plane', 'title'=>'Send Funds', 'text'=>'Send '. strtoupper($cryptocurrency->symbol ?? '') .' from your external wallet or exchange'],
                            ['icon'=>'fa fa-clock', 'title'=>'Confirm', 'text'=>'Wait for network confirmation (usually 1–3 confirmations)'],
                            ['icon'=>'fa fa-wallet', 'title'=>'Receive', 'text'=>'Funds will appear in your wallet after confirmation']
                        ];
                    @endphp

                    @foreach($steps as $i => $step)
                    <div class="d-flex align-items-start mb-4">
                        <div class="rounded-circle bg-accent bg-opacity-25 text-dark fw-bold d-flex align-items-center justify-content-center me-3"
                            style="width: 44px; height: 44px;">
                            {{ $i+1 }}
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-1">{{ $step['title'] }}</h6>
                            <p class="small text-secondary mb-0">{{ $step['text'] }}</p>
                        </div>
                    </div>
                    @endforeach

                    <div class="alert alert-warning mt-4">
                        <div>
                        <h6 class="fw-bold mb-2">
                            <i class="fa fa-exclamation-triangle me-2"></i> Important Notice
                        </h6>
                        <ul class="small mb-0 ps-3">
                            <li>- Only send {{ strtoupper($cryptocurrency->symbol ?? '') }} to this address</li>
                            <li>- Deposits require network confirmations</li>
                            <li>- Minimum deposit: {{ number_format($cryptocurrency->min_deposit_amount ?? 0.001, 8) }} {{ strtoupper($cryptocurrency->symbol ?? '') }}</li>
                            <li>- Ensure you’re using the correct network</li>
                        </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Deposits -->
        <div class="card p-4 mt-5 hover-glow">
            <h2 class="h5 card-title mb-4 fw-semibold">Recent Deposits</h2>

            @forelse($deposits as $deposit)
            <div class="d-flex align-items-center justify-content-between border rounded p-3 mb-3 bg-dark bg-opacity-50">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-25 text-success d-flex align-items-center justify-content-center me-3"
                        style="width: 48px; height: 48px;">
                        <i class="fa fa-arrow-down"></i>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-0 text-light">Deposit</h6>
                        <small class="text-secondary">{{ $deposit->created_at->format('M j, Y g:i A') }}</small>
                    </div>
                </div>
                <div class="text-end">
                    <div class="fw-bold accent">
                        +{{ number_format($deposit->amount, 8) }}
                    </div>
                    <small class="text-secondary">{{ strtoupper($cryptocurrency->symbol ?? '') }}</small><br>
                    <span class="badge rounded-pill
                        @if($deposit->status === 'completed') bg-success
                        @elseif($deposit->status === 'pending') bg-warning text-dark
                        @else bg-danger
                        @endif">
                        {{ ucfirst($deposit->status) }}
                    </span>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="fa fa-arrow-down fs-1 text-secondary mb-3"></i>
                <p class="text-muted">No deposits yet</p>
            </div>
            @endforelse
        </div>
    </main>

    <script>
        function copyAddress() {
            const addressInput = document.getElementById('depositAddress');
            addressInput.select();
            addressInput.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(addressInput.value).then(() => {
                const btn = event.target.closest('button');
                const original = btn.innerHTML;
                btn.innerHTML = '<i class="fa fa-check me-1"></i> Copied!';
                btn.classList.remove('btn-accent');
                btn.classList.add('btn-success');
                setTimeout(() => {
                    btn.innerHTML = original;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-accent');
                }, 2000);
            }).catch(() => alert('Address copied!'));
        }
    </script>


@endsection