<div class="col-md-12 search_s2">

  <div class="market-pairs " id="CryptoPriceTable">
    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text" id="inputGroup-sizing-sm"><i style="color:#1e90ff" class="fa fa-search"></i></span>
      </div>
      <input aria-describedby="inputGroup-sizing-sm" class="form-control" placeholder="Search" type="text"
        id="searchFilterY" />
    </div>
    <ul class="nav nav-pills" role="tablist" id="crypt-tab">
      <li class="nav-item">
        <a aria-selected="true" class="nav-link" data-toggle="pill" href="#STAR" role="tab"><i
            style="color:#1e90ff" class="fa fa-star"></i> Fav</a>
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
      <!-- Favorites Tab (Static - Keep as is) -->
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
                <span class="coin"
                    ><i style="color:#1e90ff" class="fa fa-star add-to-favorite"></i> ETH/BTC</span
                >
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
                <span class="coin"
                    ><i style="color:#1e90ff" class="fa fa-star add-to-favorite"></i> KCS/BTC</span
                >
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
                <span class="coin"
                    ><i style="color:#1e90ff" class="fa fa-star add-to-favorite"></i> ETH/BTC</span
                >
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
                <span class="coin"
                    ><i style="color:#1e90ff" class="fa fa-star add-to-favorite"></i> KCS/BTC</span
                >
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

      <!-- USDT Tab (Dynamic) -->
      <div class="tab-pane active" id="usdt" role="tabpanel">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Market</th>
              <th scope="col">Price</th>
              <th scope="col">Change</th>
            </tr>
          </thead>
          <tbody id="coinleftmenu-usdt">
            @foreach($markets['USDT'] as $pair)
            <tr>
              <td>
                <span class="coin">
                  <i style="color:#1e90ff" class="fa fa-star add-to-favorite"></i> 
                  {{ $pair->baseCurrency->symbol }}/{{ $pair->quoteCurrency->symbol }}
                </span>
              </td>
              <td>
                <span class="val">{{ number_format($pair->getCurrentPrice(), 2) }}</span>
              </td>
              <td style="color:{{ $pair->baseCurrency->price_change_24h >= 0 ? 'green' : 'red' }}">
                <span class="degree">{{ number_format($pair->baseCurrency->price_change_24h, 2) }}%</span>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- BTC Tab (Dynamic) -->
      <div class="tab-pane" id="btc" role="tabpanel">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Market</th>
              <th scope="col">Price</th>
              <th scope="col">Change</th>
            </tr>
          </thead>
          <tbody id="coinleftmenu-btc">
            @foreach($markets['BTC'] as $pair)
            <tr>
              <td>
                <span class="coin">
                  <i style="color:#1e90ff" class="fa fa-star add-to-favorite"></i> 
                  {{ $pair->baseCurrency->symbol }}/{{ $pair->quoteCurrency->symbol }}
                </span>
              </td>
              <td>
                <span class="val">{{ number_format($pair->getCurrentPrice(), 2) }}</span>
              </td>
              <td style="color:{{ $pair->baseCurrency->price_change_24h >= 0 ? 'green' : 'red' }}">
                <span class="degree">{{ number_format($pair->baseCurrency->price_change_24h, 2) }}%</span>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- ETH Tab (Dynamic) -->
      <div class="tab-pane" id="eth" role="tabpanel">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Market</th>
              <th scope="col">Price</th>
              <th scope="col">Change</th>
            </tr>
          </thead>
          <tbody id="coinleftmenu-eth">
            @foreach($markets['ETH'] as $pair)
            <tr>
              <td>
                <span class="coin">
                  <i style="color:#1e90ff" class="fa fa-star add-to-favorite"></i> 
                  {{ $pair->baseCurrency->symbol }}/{{ $pair->quoteCurrency->symbol }}
                </span>
              </td>
              <td>
                <span class="val">{{ number_format($pair->getCurrentPrice(), 2) }}</span>
              </td>
              <td style="color:{{ $pair->baseCurrency->price_change_24h >= 0 ? 'green' : 'red' }}">
                <span class="degree">{{ number_format($pair->baseCurrency->price_change_24h, 2) }}%</span>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- EUR Tab (Dynamic) -->
      <div class="tab-pane" id="eur" role="tabpanel">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Market</th>
              <th scope="col">Price</th>
              <th scope="col">Change</th>
            </tr>
          </thead>
          <tbody id="coinleftmenu-eur">
            @foreach($markets['EUR'] as $pair)
            <tr>
              <td>
                <span class="coin">
                  <i style="color:#1e90ff" class="fa fa-star add-to-favorite"></i> 
                  {{ $pair->baseCurrency->symbol }}/{{ $pair->quoteCurrency->symbol }}
                </span>
              </td>
              <td>
                <span class="val">{{ number_format($pair->getCurrentPrice(), 2) }}</span>
              </td>
              <td style="color:{{ $pair->baseCurrency->price_change_24h >= 0 ? 'green' : 'red' }}">
                <span class="degree">{{ number_format($pair->baseCurrency->price_change_24h, 2) }}%</span>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
</div>

</div>