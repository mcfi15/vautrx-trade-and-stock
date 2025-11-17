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
                            <a href="{{ url('wallet/deposit') }}" class="btn-2"><i class="fa fa-download"></i> Deposit</a>
                            <a href="{{ url('wallet/withdraw') }}" class="btn-1"><i class="fa fa-upload"></i> Withdraw</a>
                            <a href="/gift/" class="btn-1"><i class="fa fa-gift"></i> Gift Cards</a>
                            <a href="{{ url('stocks') }}" class="btn-2"><i class="fa fa-line-chart"></i> Stocks</a>

                        </div>
                        <div class=" d-lg-none w-100">
                            <div class="row w-100">
                                <div class="col-6"><a href="{{ url('wallet/deposit') }}" class="btn-2 btn-block"><i
                                            class="fa fa-download"></i> Deposit</a></div>
                                <div class="col-6 text"><a href="{{ url('wallet/wallet') }}"
                                        class="btn-2 btn-block"><i class="fa fa-upload"></i> Withdraw</a></div>
                            </div>
                            <div class="row w-100 mt-2">
                                <div class="col-6"><a href="#" class="btn-1 btn-block"><i
                                            class="fa fa-exchange"></i> Transfer</a></div>
                                <div class="col-6 text"><a href="{{ url('stocks') }}" class="btn-1 btn-block"><i
                                            class="fa fa-line-chart"></i> Stocks</a></div>
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
          <div class="row w-100 align-items-center">
            <div class="col-md-4">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="hideLowValuesDesktop">
                <label class="custom-control-label" for="hideLowValuesDesktop">
                  Hide Low Balances
                </label>
              </div>
            </div>
            <div class="col-md-4 text-center">
              <a href="/account/dust" style="background-color:#1e90ff;border-color:#1e90ff;" class="btn btn-warning btn-sm">
                Convert low balance to BNB
              </a>
            </div>
            <div class="col-md-4">
              <input class="form-control" placeholder="Search" id="searchFilterDesktop">
            </div>
          </div>
                </div>

                <!-- Mobile filter controls -->
                <div class="d-lg-none p-3">
                <div class="row">
                    <div class="col-6">
                    <div class="custom-control custom-checkbox mt-2">
                        <input type="checkbox" class="custom-control-input" id="hideLowValuesMobile">
                        <label class="custom-control-label" for="hideLowValuesMobile">
                        Hide Low Balances
                        </label>
                    </div>
                    </div>
                    <div class="col-6">
                    <input class="form-control" placeholder="Search" id="searchFilterMobile">
                    </div>
                </div>
                <div class="row text-center mt-3">
                    <div class="col-12">
                    <a href="/account/dust" style="background-color:#1e90ff;border-color:#1e90ff;" class="btn btn-warning btn-sm">
                        Convert low balance to BNB
                    </a>
                    </div>
                </div>
                </div>
                <!-- </div> -->
                <div class="card-body">

                    
                    <div class="table-responsive">
                        <table class="table wallet-table table-hover text-left" id="walletTable">
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
                                <tr class="wallet-row"
                                    data-symbol="{{ strtolower($wallet->cryptocurrency->symbol) }}"
                                    data-balance="{{ $wallet->balance }}">
                                <td>
                                    <div class="pair-name d-flex align-items-center">
                                    <div class="icon mr-2"
                                        style="background-image: url('{{ $wallet->cryptocurrency->logo_url }}'); width:24px; height:24px; background-size:cover;"
                                        onerror="this.onerror=null; this.src='{{ asset('Upload/coin/default.png') }}'"></div>
                                    <small>{{ strtoupper($wallet->cryptocurrency->symbol ?? 'N/A') }} [{{ $wallet->cryptocurrency->name ?? 'Unknown' }}]</small>
                                    </div>
                                </td>
                                <td class="text balance">
                                    {{ number_format($wallet->balance, 8) }}
                                </td>
                                <td class="hide-mobile text">
                                    {{ number_format($wallet->locked_balance ?? 0, 8) }}
                                </td>
                                <td class="hide-mobile text">
                                    {{ number_format($wallet->balance + ($wallet->locked_balance ?? 0), 8) }}
                                </td>
                                <td class="hide-mobile text">
                                    ${{ number_format($wallet->cryptocurrency->current_price ?? 0, 2) }}
                                </td>
                                <td class="hide-mobile text">
                                    ${{ number_format(($wallet->balance * ($wallet->cryptocurrency->current_price ?? 0)), 2) }}
                                </td>
                                <td class="text">
                                    <a href="{{ url('wallet/deposit/' . $wallet->cryptocurrency_id) }}" class="green">
                                    <i class="fa fa-download"></i> Deposit
                                    </a> /
                                    <a href="{{ url('wallet/withdraw/' . $wallet->cryptocurrency_id) }}" class="red">
                                    <i class="fa fa-upload"></i> Withdraw
                                    </a>
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

{{-- ✅ JS for Hide Low & Search --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  const hideLowDesktop = document.getElementById('hideLowValuesDesktop');
  const hideLowMobile = document.getElementById('hideLowValuesMobile');
  const searchDesktop = document.getElementById('searchFilterDesktop');
  const searchMobile = document.getElementById('searchFilterMobile');
  const rows = document.querySelectorAll('#walletTable tbody tr');

  function filterWallets() {
    const hideLow = hideLowDesktop.checked || hideLowMobile.checked;
    const searchTerm = (searchDesktop.value || searchMobile.value).toLowerCase();

    rows.forEach(row => {
      const symbol = row.dataset.symbol;
      const balance = parseFloat(row.dataset.balance);
      const matchSearch = symbol.includes(searchTerm);
      const matchBalance = !hideLow || balance > 0.0001;
      row.style.display = matchSearch && matchBalance ? '' : 'none';
    });
  }

  [hideLowDesktop, hideLowMobile].forEach(el => el.addEventListener('change', filterWallets));
  [searchDesktop, searchMobile].forEach(el => el.addEventListener('keyup', filterWallets));
});
</script>

@endsection