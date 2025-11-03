<div class="col-md-12 search_s2">
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
              <tbody id="STAR-DATA">
                  @foreach($marketsUSDT->merge($marketsBTC)->merge($marketsETH)->merge($marketsEUR) as $m)
                  @if($favorites->contains($m->id))
                  <tr onclick="window.location='{{ url('trading', $m->id) }}'" style="cursor:pointer;">
                    <td><i class="icon ion-md-star"></i> {{ $m->symbol }}</td>
                    <td>{{ number_format($m->last_price, 8) }}</td>
                    <td class="{{ $m->change < 0 ? 'red' : 'green' }}">{{ $m->change }}%</td>
                  </tr>
                  @endif
                  @endforeach
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
              <tbody id="coinleftmenu-usdt">
                @foreach($marketsUSDT as $m)
                <tr onclick="window.location='{{ url('trading', $m->id) }}'" style="cursor:pointer;">
                  <td>{{ $m->symbol }}</td>
                  <td>{{ number_format($m->last_price, 8) }}</td>
                  <td class="{{ $m->change < 0 ? 'red' : 'green' }}">{{ $m->change }}%</td>
                </tr>
                @endforeach
                </tbody>

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
              <tbody id="coinleftmenu-btc">
              @foreach($marketsBTC as $m)
              <tr onclick="window.location='{{ url('trading', $m->id) }}'" style="cursor:pointer;">
                <td>{{ $m->symbol }}</td>
                <td>{{ number_format($m->last_price, 8) }}</td>
                <td class="{{ $m->change < 0 ? 'red' : 'green' }}">{{ $m->change }}%</td>
              </tr>
              @endforeach
              </tbody>

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
              <tbody id="coinleftmenu-eth">
              @foreach($marketsETH as $m)
              <tr onclick="window.location='{{ url('trading', $m->id) }}'" style="cursor:pointer;">
                <td>{{ $m->symbol }}</td>
                <td>{{ number_format($m->last_price, 8) }}</td>
                <td class="{{ $m->change < 0 ? 'red' : 'green' }}">{{ $m->change }}%</td>
              </tr>
              @endforeach
              </tbody>

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
              <tbody id="coinleftmenu-eur">
              @foreach($marketsEUR as $m)
              <tr onclick="window.location='{{ url('trading', $m->id) }}'" style="cursor:pointer;">
                <td>{{ $m->symbol }}</td>
                <td>{{ number_format($m->last_price, 8) }}</td>
                <td class="{{ $m->change < 0 ? 'red' : 'green' }}">{{ $m->change }}%</td>
              </tr>
              @endforeach
              </tbody>

            </table>
          </div>
        </div>
      </div>

    </div>