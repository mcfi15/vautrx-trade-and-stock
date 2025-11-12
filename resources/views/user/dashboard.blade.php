@extends('layouts.app')

@section('title', 'Dashboard - Crypto Trading Platform')

@section('content')

<div class="container wallet-page">
    <div class="row">
        <div class="col-xl-12 m-t-30">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-md-3 col-sm-6 m-b-15">
                            <h5 class="text-muted">Total balance</h5>
                            
                            <h2><span id="total_balance_est">{{ number_format($totalValue ?? 0, 2) }}</span> <span> USDT </span></h2>
                        </div>
                        <div class="col-md-9 col-sm-6 text-right hide-mobile">
                            <a href="/deposit/?coin=btc" class="btn-2"><i class="fa fa-download"></i> Deposit</a>
                            <a href="/withdraw/?coin=btc" class="btn-1"><i class="fa fa-upload"></i> Withdraw</a>
                            <a href="/gift/" class="btn-1"><i class="fa fa-gift"></i> Gift Cards</a>
                            <a href="/deposit/?coin=btc" class="btn-2"><i class="fa fa-download"></i> Stock</a>

                        </div>
                        <div class=" d-lg-none w-100">
                            <div class="row w-100">
                                <div class="col-6"><a href="/deposit/?coin=btc" class="btn-2 btn-block"><i
                                            class="fa fa-download"></i> Deposit</a></div>
                                <div class="col-6 text-right"><a href="/withdraw/?coin=btc"
                                        class="btn-1 btn-block"><i class="fa fa-upload"></i> Withdraw</a></div>
                            </div>
                            <div class="row w-100 mt-2">
                                <div class="col-6"><a href="#" class="btn-1 btn-block"><i
                                            class="fa fa-exchange"></i> Transfer</a></div>
                                <div class="col-6 text-right"><a href="#" class="btn-1 btn-block"><i
                                            class="fa fa-exchange"></i> P2p Wallet</a></div>
                            </div>
                            <div class="row w-100 mt-2">
                                <div class="col-12">
                                    <a href="/gift/" class="btn-1 btn-block"><i class="fa fa-gift"></i> Gift
                                        Cards</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header p-l-5 p-r-5 hide-mobile">

                    <div class="col-4">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="hideLowValues" value="1"> <label
                                class="custom-control-label" for="hideLowValues">Hide Low Balances</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <a href="/account/dust" class="btn btn-warning btn-sm">Convert low balance to bnb</a>
                    </div>
                    <div class="col-4">
                        <input class="form-control" placeholder="Search" id="searchFilter">
                    </div>
                </div>
                <!-- <div class="card-header p-l-5 p-r-5"> -->
                <div class="d-lg-none">

                    <div class="row  pl-1 pr-1">
                        <div class="col-6 align-baseline">
                            <div class="custom-control custom-checkbox mt-3">
                                <input type="checkbox" class="custom-control-input" id="hideLowValues" value="1"> <label
                                    class="custom-control-label" for="hideLowValues">Hide Low Balances</label>
                            </div>
                        </div>
                        <div class="col-6 mt-1">
                            <input class="form-control" placeholder="Search" id="searchFilterMob">
                        </div>
                    </div>
                    <div class="row text-center mt-3 ">
                        <div class="col-12">
                            <a href="/Finance/dust" class="btn btn-warning btn-sm">Convert low balance to bnb</a>
                        </div>
                    </div>
                </div>
                <!-- </div> -->
                <div class="card-body">

                    
                    <div class="table-responsive">
                        <table class="table wallet-table table-hover text-left" id="cryptoTable">
                            <thead>
                                <tr>
                                    <th scope="col">Coin</th>
                                    <th>Balance</th>

                                    <th scope="col" class="hide-mobile">Order</th>
                                    <th scope="col" class="hide-mobile">Total</th>
                                    <th scope="col" class="hide-mobile">Price</th>
                                    <th class="hide-mobile"><span title="USDT Convert">USDT</span></th>
                                    <th scope="col">Options</th>

                                </tr>
                            </thead>
                            <tbody>

                                @foreach($wallets as $wallet)
                                <tr>
                                    <td class="col-3">
                                        <div class="pair-name">
                                            <div class="d-block d-sm-none">
                                                <div class="icon"
                                                    style="background-image: url('/Upload/coin/651a8af250cf6.png')"
                                                    onerror="this.onerror=null; this.src='/Upload/coin/default.png'">
                                                </div>
                                                <small>BTC</small>
                                            </div>
                                            <div class="hide-mobile flex-box">
                                                <div class="icon"
                                                    style="background-image: url('https://cryptofonts.com/img/SVG/btc.svg')"
                                                    onerror="this.onerror=null; this.src='/Upload/coin/default.png'">
                                                </div>
                                                <small>BTC [ none ]</small>
                                            </div>

                                            <p class="data_title" style="display: none;">usdt</p>

                                        </div>
                                    </td>
                                    <td class="data_balance btc">
                                        
                                    
                                    </td>

                                    <td class="hide-mobile">
                                        
                                    
                                    </td>
                                    <td class="hide-mobile data_total">
                                        
                                        
                                    </td>
                                    <td class="hide-mobile data_price"><img src="https://cryptofonts.com/img/SVG/usdt.svg"
                                            width="16px" height="16px" alt="USDT"> <span>
                                                
                                        
                                            </span></td>
                                    <td class="hide-mobile data_usdt"><img src="https://cryptofonts.com/img/SVG/usdt.svg"
                                            width="16px" height="16px" alt="USDT"> <span>
                                                0.0
                                            </span></td>
                                    <td class="col-2 text-right">
                                        <a href="/deposit/?coin=btc" class="green"><i class="fa fa-download"></i>
                                            Deposit</a> /
                                        <a href="/withdraw/?coin=btc" class="red"><i class="fa fa-upload"></i>
                                            Withdraw</a>
                                    </td>
                                </tr>
                                @endforeach
                                
                            </tbody>
                        </table>

                       
                    </div>

                    
                </div>



            </div>
        </div>
    </div>
</div>

@endsection