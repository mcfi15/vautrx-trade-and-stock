@extends('layouts.app')

@section('title', 'Dashboard - Crypto Trading Platform')

@section('content')


<style>
  body,
  html {
    overflow-x: hidden;
  }

  .sm_screen {
    display: none;
  }

  .order-book tbody tr {
    flex-shrink: 0;
  }

  @media only screen and (max-width: 768px) {
    .lg_screen {
      display: none;
    }

    .sm_screen {
      display: flex;
    }

    .outflexboxx {
      display: block !important;
    }
  }




  .order-book {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.buysellorders {
    font-size: 0.85rem;
}

/* Table styling */
.table.buysellorders tbody {
    display: block;
    max-height: 300px;
    overflow-y: auto;
}

.table.buysellorders thead,
.table.buysellorders tbody tr {
    display: table;
    width: 100%;
    table-layout: fixed;
}

/* Color coding */
.text-success { color: #0ecb81 !important; }
.text-danger { color: #f6465d !important; }

.buy-row:hover { background-color: rgba(14, 203, 129, 0.1) !important; }
.sell-row:hover { background-color: rgba(246, 70, 93, 0.1) !important; }

/* Button styling */
.filter-buttons button {
    padding: 6px 12px;
    border: 1px solid #ddd;
    background: white;
    cursor: pointer;
    margin-right: 5px;
    border-radius: 4px;
}

.filter-buttons button.active {
    background: #007bff;
    color: white;
    border-color: #007bff;
}

/* Middle price row */
.ob-heading {
    background: #f8f9fa !important;
    font-weight: bold;
}

.ob-heading tr td {
    text-align: center;
    padding: 8px !important;
}
</style>
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
    if (window.innerWidth <= 768) {
      $('.lg_screen').remove();
    }
  });


  
</script>

<div class="container-fluid mtb15 no-fluid">
  <div class="row sm-gutters lg_screen">
    <div class="col-md-3">
      <!-- Order Book Start -->
     @include('trading.partials.order-book')
      <!-- Order Book End -->


    </div>
    <div class="col-md-6">

     @include('trading.partials.chart')

      @include('trading.partials.buysell1')





    </div>
    <div class="col-md-3">
      @include('trading.partials.fav-2')

      @include('trading.partials.shorttradehistory')
      <style>
        div#dealrecords td {
          padding: 4px 14px !important;
        }
      </style>

    </div>

    @include('trading.partials.tradehistory')
  </div>
  <div class="row sm-gutters sm_screen">
    <div class="col-md-12 middd_stats_s1">
  <div class="exchange-headline white-bg">
    <div class="headline-left col-11 p-0">
      <div class="title-big">
        {{ $tradingPair->baseCurrency->symbol }} / {{ $tradingPair->quoteCurrency->symbol }}
        <i class="icon ion-md-star add-to-favorite default"></i>
      </div>

      <div class="headline-item instant-status">
        <div>
          {{-- ✅ Sell Price (last known price) --}}
          <span class="title" id="market_sell_price">
            {{ number_format($tradingPair->last_price ?? 0, 6) }}
          </span>

          {{-- ✅ 24h Change Percentage --}}
          <span class="{{ ($tradingPair->price_change_percent ?? 0) >= 0 ? 'green' : 'red' }}" id="market_change">
            {{ number_format($tradingPair->price_change_percent ?? 0, 2) }}%
          </span>
        </div>

        <div>
          <span>{{ $tradingPair->quoteCurrency->symbol }}</span>
          {{-- ✅ Buy Price (same as sell or derived via spread if you add that later) --}}
          <span id="market_buy_price">
            {{ number_format($tradingPair->last_price ?? 0, 6) }}
          </span>
        </div>
      </div>

      {{-- ✅ 24H HIGH --}}
      <div class="headline-item is-hh">
        <div>24H H</div>
        <div class="title green" id="market_max_price">
          {{ number_format($tradingPair->high_24h ?? 0, 6) }}
        </div>
      </div>

      {{-- ✅ 24H LOW --}}
      <div class="headline-item is-hl">
        <div>24H L</div>
        <div class="title red" id="market_min_price">
          {{ number_format($tradingPair->low_24h ?? 0, 6) }}
        </div>
      </div>

      {{-- ✅ 24H VOLUME --}}
      <div class="headline-item is-hv">
        <div>24H Volume</div>
        <div class="title" id="market_volume">
          {{ number_format($tradingPair->volume_24h ?? 0, 2) }}
        </div>
      </div>
    </div>

    <div class="headline-right col-1 p-0 ml-auto">
      <a href="#!" class="btn changeThemeLight"><i class="icon ion-md-moon"></i></a>
    </div>
  </div>
</div>


    @include('trading.partials.fav')

    @include('trading.partials.chartmobile')

    @include('trading.partials.buysell')
    
    @include('trading.partials.tradehistorymobile')
    <div class="col-md-12 order_s6">
      <!-- Order Book Start -->
     @include('trading.partials.order-book-mobile') 
      <!-- Order Book End -->

    </div>
  </div>
</div>
<input class="hide" style="display:none" id="socket_data" value="0">
<script src="{{ asset('Public/assets/js/core/libraries/node-gzip.js') }}"></script>



@endsection

@push('scripts')
<script>
  // Trading configuration from backend
const tradingConfig = {
    baseSymbol: '{{ $tradingPair->baseCurrency->symbol }}',
    quoteSymbol: '{{ $tradingPair->quoteCurrency->symbol }}',
    currentPrice: {{ $tradingPair->getCurrentPrice() ?? 0 }}
};

// Function to get TradingView symbol format
function getTradingViewSymbol(baseSymbol, quoteSymbol) {
    const symbolMap = {
        'USDT': 'USDT',
        'BTC': 'BTC',
        'ETH': 'ETH',
        'BNB': 'BNB',
        'ADA': 'ADA',
        'DOT': 'DOT',
        'LTC': 'LTC',
        'BCH': 'BCH',
        'XRP': 'XRP',
        'LINK': 'LINK',
        'EUR': 'EUR',
    };

    const formattedBase = symbolMap[baseSymbol] || baseSymbol;
    const formattedQuote = symbolMap[quoteSymbol] || quoteSymbol;
    
    if (quoteSymbol === 'EUR') {
        return `COINBASE:${formattedBase}${formattedQuote}`;
    } else {
        return `BINANCE:${formattedBase}${formattedQuote}`;
    }
}

// Chart instance variable
let tvWidget = null;

// Initialize chart
function initializeChart() {
    const symbol = getTradingViewSymbol(tradingConfig.baseSymbol, tradingConfig.quoteSymbol);
    
    const chartHeight = window.innerWidth <= 768 ? 300 : 370;
    const containerId = window.innerWidth <= 768 ? 'tradingview_chart_sm' : 'tradingview_chart';

    $('.charttcontnr').attr('id', containerId);

    const chartParams = {
        "width": "100%",
        "height": chartHeight,
        "symbol": symbol,
        "interval": "D",
        "timezone": "Etc/UTC",
        "theme": 'dark',
        "style": "1",
        "locale": "en",
        "toolbar_bg": "#f1f3f6",
        "enable_publishing": false,
        "withdateranges": true,
        "hide_side_toolbar": false,
        "allow_symbol_change": true,
        "show_popup_button": true,
        "popup_width": "1000",
        "popup_height": "650",
        "hide_legend": true,
        "container_id": containerId
    };

    tvWidget = new TradingView.widget(chartParams);
    console.log('Chart initialized with symbol:', symbol);
}

// Initialize on page load
$(document).ready(function () {
    initializeChart();
    
    $(window).resize(function() {
        setTimeout(initializeChart, 300);
    });
});
</script>
@endpush

@push('scripts')
<script>
    function generateFakeTradingData() {
        const tbody = document.getElementById('recent-orders-list');
        const now = new Date();
        
        // Generate 10-12 random trades
        const newRows = Array.from({length: Math.floor(Math.random() * 3) + 10}, (_, i) => {
            const isBuy = Math.random() > 0.5;
            const textColorClass = isBuy ? 'text-success' : 'text-danger';
            const price = parseFloat((Math.random() * 400 + 800).toFixed(8));
            const quantity = parseFloat((Math.random() * 10 + 0.5).toFixed(8));
            const total = (price * quantity).toFixed(8);
            
            // Generate random time within last hour
            const randomMinutes = Math.floor(Math.random() * 60);
            const randomSeconds = Math.floor(Math.random() * 60);
            const tradeTime = new Date(now - (randomMinutes * 60000) - (randomSeconds * 1000));
            const timeStr = `${String(tradeTime.getMonth() + 1).padStart(2, '0')}-${String(tradeTime.getDate()).padStart(2, '0')} ${String(tradeTime.getHours()).padStart(2, '0')}:${String(tradeTime.getMinutes()).padStart(2, '0')}:${String(tradeTime.getSeconds()).padStart(2, '0')}`;
            
            return `
                <tr>
                    <td>${timeStr}</td>
                    <td class="${textColorClass}">${isBuy ? 'BUY' : 'SELL'}</td>
                    <td class="${textColorClass}">${price.toFixed(8)}</td>
                    <td>${quantity.toFixed(8)}</td>
                    <td>${total}</td>
                </tr>
            `;
        }).join('');
        
        tbody.innerHTML = newRows;
    }

    // Refresh with new fake data every 2 seconds
    setInterval(generateFakeTradingData, 2000);
</script>
@endpush

@push('scripts')
<script>
    function generateFakeTradingData() {
        const tbody = document.getElementById('recent-orders-list2');
        const now = new Date();
        
        // Generate 10-12 random trades
        const newRows = Array.from({length: Math.floor(Math.random() * 3) + 10}, (_, i) => {
            const isBuy = Math.random() > 0.5;
            const textColorClass = isBuy ? 'text-success' : 'text-danger';
            const price = parseFloat((Math.random() * 400 + 800).toFixed(8));
            const quantity = parseFloat((Math.random() * 10 + 0.5).toFixed(8));
            const total = (price * quantity).toFixed(8);
            
            // Generate random time within last hour
            const randomMinutes = Math.floor(Math.random() * 60);
            const randomSeconds = Math.floor(Math.random() * 60);
            const tradeTime = new Date(now - (randomMinutes * 60000) - (randomSeconds * 1000));
            const timeStr = `${String(tradeTime.getMonth() + 1).padStart(2, '0')}-${String(tradeTime.getDate()).padStart(2, '0')} ${String(tradeTime.getHours()).padStart(2, '0')}:${String(tradeTime.getMinutes()).padStart(2, '0')}:${String(tradeTime.getSeconds()).padStart(2, '0')}`;
            
            return `
                <tr>
                    <td>${timeStr}</td>
                    <td class="${textColorClass}">${isBuy ? 'BUY' : 'SELL'}</td>
                    <td class="${textColorClass}">${price.toFixed(8)}</td>
                    <td>${quantity.toFixed(8)}</td>
                    <td>${total}</td>
                </tr>
            `;
        }).join('');
        
        tbody.innerHTML = newRows;
    }

    // Refresh with new fake data every 2 seconds
    setInterval(generateFakeTradingData, 2000);
</script>
@endpush

@push('scripts')
<script>
    function generateFakeTradingData() {
        const tbody = document.getElementById('recent-orders');
        const now = new Date();
        
        // Generate 10-12 random trades
        const newRows = Array.from({length: Math.floor(Math.random() * 3) + 10}, (_, i) => {
            const isBuy = Math.random() > 0.5;
            const textColorClass = isBuy ? 'text-success' : 'text-danger';
            const price = parseFloat((Math.random() * 400 + 800).toFixed(8));
            const quantity = parseFloat((Math.random() * 10 + 0.5).toFixed(8));
            const total = (price * quantity).toFixed(8);
            
            // Generate random time within last hour
            const randomMinutes = Math.floor(Math.random() * 60);
            const randomSeconds = Math.floor(Math.random() * 60);
            const tradeTime = new Date(now - (randomMinutes * 60000) - (randomSeconds * 1000));
            const timeStr = `${String(tradeTime.getMonth() + 1).padStart(2, '0')}-${String(tradeTime.getDate()).padStart(2, '0')} ${String(tradeTime.getHours()).padStart(2, '0')}:${String(tradeTime.getMinutes()).padStart(2, '0')}:${String(tradeTime.getSeconds()).padStart(2, '0')}`;
            
            return `
                <tr>
                    <td>${timeStr}</td>
                    <td class="${textColorClass}">${isBuy ? 'BUY' : 'SELL'}</td>
                    <td class="${textColorClass}">${price.toFixed(8)}</td>
                    <td>${quantity.toFixed(8)}</td>
                    <td>${total}</td>
                </tr>
            `;
        }).join('');
        
        tbody.innerHTML = newRows;
    }

    // Refresh with new fake data every 2 seconds
    setInterval(generateFakeTradingData, 2000);
</script>
@endpush

@push('scripts')
<script>
    let currentDecimalPlaces = 8;
    
    // Function to generate fake order book data with larger quantities
    function generateFakeOrderBook() {
        const basePrice = parseFloat(document.getElementById('currentPrice').textContent.replace(/,/g, '')) || 1000;
        
        // Generate sell orders (prices above current price)
        const sellOrders = Array.from({length: 10}, (_, i) => {
            const price = basePrice * (1 + ((i + 1) * 0.001) + (Math.random() * 0.005));
            const quantity = parseFloat((Math.random() * 495 + 5).toFixed(6)); // 5 to 500 units
            const total = (price * quantity).toFixed(currentDecimalPlaces);
            
            return {
                price: price.toFixed(currentDecimalPlaces),
                quantity: quantity.toFixed(6),
                total: total
            };
        });
        
        // Generate buy orders (prices below current price)
        const buyOrders = Array.from({length: 10}, (_, i) => {
            const price = basePrice * (1 - ((i + 1) * 0.001) - (Math.random() * 0.005));
            const quantity = parseFloat((Math.random() * 495 + 5).toFixed(6)); // 5 to 500 units
            const total = (price * quantity).toFixed(currentDecimalPlaces);
            
            return {
                price: price.toFixed(currentDecimalPlaces),
                quantity: quantity.toFixed(6),
                total: total
            };
        });
        
        // Update sell orders
        const sellTbody = document.getElementById('sellorderlist');
        sellTbody.innerHTML = sellOrders.map(order => `
            <tr class="sell-row local-order" data-price="${order.price}" data-amount="${order.quantity}">
                <td class="text-danger price">${order.price}</td>
                <td class="amount">${order.quantity}</td>
                <td class="total">${order.total}</td>
            </tr>
        `).join('');
        
        // Update buy orders
        const buyTbody = document.getElementById('buyorderlist');
        buyTbody.innerHTML = buyOrders.map(order => `
            <tr class="buy-row local-order" data-price="${order.price}" data-amount="${order.quantity}">
                <td class="text-success price">${order.price}</td>
                <td class="amount">${order.quantity}</td>
                <td class="total">${order.total}</td>
            </tr>
        `).join('');
        
        // Update current price with small random movement
        const priceChange = (Math.random() - 0.5) * 0.002; // ±0.1%
        const newPrice = basePrice * (1 + priceChange);
        document.getElementById('currentPrice').textContent = newPrice.toFixed(currentDecimalPlaces);
        document.getElementById('currentPriceRight').textContent = newPrice.toFixed(currentDecimalPlaces);
    }
    
    // Decimal places selector
    document.getElementById('decimalplaces').addEventListener('change', function(e) {
        currentDecimalPlaces = parseInt(e.target.value);
        generateFakeOrderBook(); // Regenerate with new decimal places
    });
    
    // Filter buttons functionality
    document.getElementById('defaultModeButton').addEventListener('click', function() {
        document.getElementById('sellorderlist').style.display = '';
        document.getElementById('buyorderlist').style.display = '';
        this.classList.add('active');
        document.getElementById('buyModeButton').classList.remove('active');
        document.getElementById('sellModeButton').classList.remove('active');
    });
    
    document.getElementById('buyModeButton').addEventListener('click', function() {
        document.getElementById('sellorderlist').style.display = 'none';
        document.getElementById('buyorderlist').style.display = '';
        this.classList.add('active');
        document.getElementById('defaultModeButton').classList.remove('active');
        document.getElementById('sellModeButton').classList.remove('active');
    });
    
    document.getElementById('sellModeButton').addEventListener('click', function() {
        document.getElementById('sellorderlist').style.display = '';
        document.getElementById('buyorderlist').style.display = 'none';
        this.classList.add('active');
        document.getElementById('defaultModeButton').classList.remove('active');
        document.getElementById('buyModeButton').classList.remove('active');
    });
    
    // Initialize and refresh every 2 seconds
    generateFakeOrderBook();
    setInterval(generateFakeOrderBook, 2000);
</script>
@endpush

{{-- @push('scripts')
<script>
    // Search functionality
    document.getElementById('searchFilter').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const visibleTab = document.querySelector('.tab-pane.active');
        const tbody = visibleTab.querySelector('tbody');
        
        if (tbody) {
            const rows = tbody.getElementsByTagName('tr');
            Array.from(rows).forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        }
    });

    // Auto-refresh market data every 3 seconds
    function refreshMarketData() {
        const allTabs = ['STAR-DATA', 'coinleftmenu-usdt', 'coinleftmenu-btc', 'coinleftmenu-eth', 'coinleftmenu-eur'];
        
        allTabs.forEach(tableId => {
            const tbody = document.getElementById(tableId);
            if (tbody) {
                const rows = tbody.getElementsByTagName('tr');
                Array.from(rows).forEach(row => {
                    const priceCell = row.cells[1];
                    const changeCell = row.cells[2];
                    
                    if (priceCell && changeCell) {
                        const currentPrice = parseFloat(priceCell.textContent.replace(/,/g, ''));
                        const currentChange = parseFloat(changeCell.textContent);
                        
                        // Small random price change (±0.8%)
                        const priceChange = (Math.random() - 0.5) * 0.016;
                        const newPrice = Math.max(0.00000001, currentPrice * (1 + priceChange));
                        const newChange = currentChange + (Math.random() - 0.5);
                        
                        priceCell.textContent = newPrice.toFixed(8);
                        changeCell.textContent = newChange.toFixed(2) + '%';
                        
                        // Update color based on change
                        changeCell.className = newChange < 0 ? 'red' : 'green';
                    }
                });
            }
        });
    }

    // Initialize tab functionality
    document.querySelectorAll('#crypt-tab .nav-link').forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            document.querySelectorAll('#crypt-tab .nav-link').forEach(t => t.classList.remove('active'));
            // Add active class to clicked tab
            this.classList.add('active');
        });
    });

    // Start auto-refresh
    setInterval(refreshMarketData, 3000);
</script>
@endpush --}}

{{-- @push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Only target the specific trading pairs section
    const tradingTabs = document.querySelector('[data-trading-tabs]');
    if (!tradingTabs) return;
    
    const tabLinks = tradingTabs.querySelectorAll('.nav-link[data-toggle="tab"]');
    const tabPanes = tradingTabs.querySelectorAll('.tab-pane');
    
    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetPane = tradingTabs.querySelector(targetId);
            
            if (!targetPane) return;
            
            // Remove active class from all tabs and panes in this section
            tabLinks.forEach(tab => tab.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('show', 'active'));
            
            // Add active class to clicked tab and target pane
            this.classList.add('active');
            targetPane.classList.add('show', 'active');
        });
    });
});
</script>
@endpush --}}

@push('scripts')

<script>
(function(){
  // Defensive startup
  if (!document) return;
  const START_LABEL = '[GLOBAL-MARKET-SEARCH]';
  console.clear();
  console.log(START_LABEL, 'init');

  const searchInput = document.getElementById('searchFilter');
  if (!searchInput) {
    console.error(START_LABEL, 'search input #searchFilter not found');
    return;
  }

  // Collect all tbody elements we expect to filter:
  // STAR could use id "STAR-DATA", the rest use coinleftmenu-*
  function getAllTBodies() {
    const tbodies = [];
    // specific STAR id
    const star = document.getElementById('STAR-DATA');
    if (star) tbodies.push(star);
    // any coinleftmenu-* matches
    document.querySelectorAll('[id^="coinleftmenu-"]').forEach(el => tbodies.push(el));
    // fallback: any tab-pane tbody (avoid duplicates)
    document.querySelectorAll('.tab-content .tab-pane tbody').forEach(tb => {
      if (!tbodies.includes(tb)) tbodies.push(tb);
    });
    return tbodies;
  }

  function safeRemoveNoResults(tbody) {
    const existing = tbody.querySelector('tr.__no_search_results');
    if (existing) existing.remove();
  }

  function safeAppendNoResults(tbody, cols) {
    safeRemoveNoResults(tbody);
    const tr = document.createElement('tr');
    tr.className = '__no_search_results';
    const td = document.createElement('td');
    td.setAttribute('colspan', cols);
    td.style.textAlign = 'center';
    td.style.color = '#999';
    td.style.padding = '8px 0';
    td.textContent = 'No matching results';
    tr.appendChild(td);
    tbody.appendChild(tr);
  }

  function filterAllTabs() {
    const term = (searchInput.value || '').toLowerCase().trim();
    const tbodies = getAllTBodies();

    console.log(START_LABEL, 'running filter', { term, tbodiesCount: tbodies.length });

    if (tbodies.length === 0) {
      console.warn(START_LABEL, 'no tbodies found to filter - check your markup');
      return;
    }

    tbodies.forEach(tbody => {
      // find rows directly under tbody
      const rows = Array.from(tbody.querySelectorAll('tr')).filter(r => !r.classList.contains('__no_search_results'));
      let visible = 0;

      rows.forEach(row => {
        // if row has no text (or is a nested header), still handle gracefully
        const text = (row.textContent || '').toLowerCase();
        const match = term === '' || text.includes(term);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
      });

      // handle no-results marker
      if (term !== '' && visible === 0) {
        // compute columns (table header count) fallback 3
        let cols = 1;
        const table = tbody.closest('table');
        if (table) {
          const ths = table.querySelectorAll('thead th');
          cols = Math.max(ths.length, 1);
        }
        safeAppendNoResults(tbody, cols);
      } else {
        safeRemoveNoResults(tbody);
      }
    });

    // do not touch tab headers/classes — let Bootstrap manage which pane is visible.
    // This ensures switching tabs keeps filtered state.
    console.log(START_LABEL, 'filter complete');
  }

  // debounce helper to avoid excessive runs while typing quickly
  function debounce(fn, wait){
    let t;
    return function(...args){
      clearTimeout(t);
      t = setTimeout(() => fn.apply(this, args), wait);
    }
  }

  // bind input (debounced)
  const debouncedFilter = debounce(filterAllTabs, 120);
  searchInput.addEventListener('input', debouncedFilter);

  // run once initially to set state
  filterAllTabs();

  // Expose for debugging in console
  window.__globalMarketSearch = {
    run: filterAllTabs,
    getTbodies: getAllTBodies,
    startLabel: START_LABEL
  };

  console.log(START_LABEL, 'ready - use __globalMarketSearch.run() to invoke manually');
})();
</script>


    
@endpush

@push('scripts')

<script>
(function(){
  // Defensive startup
  if (!document) return;
  const START_LABEL = '[GLOBAL-MARKET-SEARCH]';
  console.clear();
  console.log(START_LABEL, 'init');

  const searchInput = document.getElementById('searchFilterY');
  if (!searchInput) {
    console.error(START_LABEL, 'search input #searchFilter not found');
    return;
  }

  // Collect all tbody elements we expect to filter:
  // STAR could use id "STAR-DATA", the rest use coinleftmenu-*
  function getAllTBodies() {
    const tbodies = [];
    // specific STAR id
    const star = document.getElementById('STAR-DATA');
    if (star) tbodies.push(star);
    // any coinleftmenu-* matches
    document.querySelectorAll('[id^="coinleftmenu-"]').forEach(el => tbodies.push(el));
    // fallback: any tab-pane tbody (avoid duplicates)
    document.querySelectorAll('.tab-content .tab-pane tbody').forEach(tb => {
      if (!tbodies.includes(tb)) tbodies.push(tb);
    });
    return tbodies;
  }

  function safeRemoveNoResults(tbody) {
    const existing = tbody.querySelector('tr.__no_search_results');
    if (existing) existing.remove();
  }

  function safeAppendNoResults(tbody, cols) {
    safeRemoveNoResults(tbody);
    const tr = document.createElement('tr');
    tr.className = '__no_search_results';
    const td = document.createElement('td');
    td.setAttribute('colspan', cols);
    td.style.textAlign = 'center';
    td.style.color = '#999';
    td.style.padding = '8px 0';
    td.textContent = 'No matching results';
    tr.appendChild(td);
    tbody.appendChild(tr);
  }

  function filterAllTabs() {
    const term = (searchInput.value || '').toLowerCase().trim();
    const tbodies = getAllTBodies();

    console.log(START_LABEL, 'running filter', { term, tbodiesCount: tbodies.length });

    if (tbodies.length === 0) {
      console.warn(START_LABEL, 'no tbodies found to filter - check your markup');
      return;
    }

    tbodies.forEach(tbody => {
      // find rows directly under tbody
      const rows = Array.from(tbody.querySelectorAll('tr')).filter(r => !r.classList.contains('__no_search_results'));
      let visible = 0;

      rows.forEach(row => {
        // if row has no text (or is a nested header), still handle gracefully
        const text = (row.textContent || '').toLowerCase();
        const match = term === '' || text.includes(term);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
      });

      // handle no-results marker
      if (term !== '' && visible === 0) {
        // compute columns (table header count) fallback 3
        let cols = 1;
        const table = tbody.closest('table');
        if (table) {
          const ths = table.querySelectorAll('thead th');
          cols = Math.max(ths.length, 1);
        }
        safeAppendNoResults(tbody, cols);
      } else {
        safeRemoveNoResults(tbody);
      }
    });

    // do not touch tab headers/classes — let Bootstrap manage which pane is visible.
    // This ensures switching tabs keeps filtered state.
    console.log(START_LABEL, 'filter complete');
  }

  // debounce helper to avoid excessive runs while typing quickly
  function debounce(fn, wait){
    let t;
    return function(...args){
      clearTimeout(t);
      t = setTimeout(() => fn.apply(this, args), wait);
    }
  }

  // bind input (debounced)
  const debouncedFilter = debounce(filterAllTabs, 120);
  searchInput.addEventListener('input', debouncedFilter);

  // run once initially to set state
  filterAllTabs();

  // Expose for debugging in console
  window.__globalMarketSearch = {
    run: filterAllTabs,
    getTbodies: getAllTBodies,
    startLabel: START_LABEL
  };

  console.log(START_LABEL, 'ready - use __globalMarketSearch.run() to invoke manually');
})();
</script>


    
@endpush

@push('scripts')

<script>
/*
  Universal Order Form script
  - Switches order type (limit/market/stop)
  - Calculates totals live
  - Percentage quick buttons (uses #base_coin for quote balance and #user_coin for base balance)
  - Non-destructive: checks existence of elements
*/
(function(){
  if (!document) return;
  const qs = (s, root=document) => root.querySelector(s);
  const qsa = (s, root=document) => Array.from(root.querySelectorAll(s));

  // Elements (inputs/buttons) used in your markup
  const tabs = qsa('#orderTypeTabs .nav-link');
  const buyPriceBox = qs('#buypricebox');
  const sellPriceBox = qs('#sellpricebox');
  const buyStopBox = qs('#buystop');
  const sellStopBox = qs('#sellstop');

  const limitBuyBtn = qs('#limitbuybutton');
  const limitSellBtn = qs('#limitsellbutton');
  const marketBuyBtn = qs('#marketbuybutton');
  const marketSellBtn = qs('#marketsellbutton');
  const stopBuyBtn = qs('#stopbuybutton');
  const stopSellBtn = qs('#stopsellbutton');

  const buyPriceInput = qs('#buy_price');
  const buyQtyInput = qs('#buy_num');
  const buyTotalEl = qs('#buy_mum');

  const sellPriceInput = qs('#sell_price');
  const sellQtyInput = qs('#sell_num');
  const sellTotalEl = qs('#sell_mum');

  const feeBuyEl = qs('#buy_fees');
  const feeSellEl = qs('#sell_fees');

  const percentLinks = qsa('.market-trade-list a');

  const baseBalanceEl = qs('#base_coin'); // for quote balance (e.g. USDT)
  const userBaseEl = qs('#user_coin'); // for base balance (e.g. BTC)

  // Helper: safe text -> float
  function toFloat(v){ const n = parseFloat(String(v).replace(/,/g,'')); return isNaN(n)?0:n; }

  // Initialize: hide everything then show 'limit' UI
  function showOrderType(type){
    // hide all sections first
    const hideIfExists = id => { const el = qs('#'+id); if(el) el.style.display = 'none'; };
    ['buypricebox','sellpricebox','buystop','sellstop',
     'limitbuybutton','limitsellbutton','marketbuybutton','marketsellbutton','stopbuybutton','stopsellbutton']
      .forEach(hideIfExists);

    // wiring display
    if (type === 'limit') {
      if (buyPriceBox) buyPriceBox.style.display = 'flex';
      if (sellPriceBox) sellPriceBox.style.display = 'flex';
      if (limitBuyBtn) limitBuyBtn.style.display = 'block';
      if (limitSellBtn) limitSellBtn.style.display = 'block';
    } else if (type === 'market') {
      // hide price inputs (market orders use current best price)
      if (buyPriceBox) buyPriceBox.style.display = 'none';
      if (sellPriceBox) sellPriceBox.style.display = 'none';
      if (marketBuyBtn) marketBuyBtn.style.display = 'block';
      if (marketSellBtn) marketSellBtn.style.display = 'block';
    } else if (type === 'stop') {
      if (buyStopBox) buyStopBox.style.display = 'flex';
      if (sellStopBox) sellStopBox.style.display = 'flex';
      if (stopBuyBtn) stopBuyBtn.style.display = 'block';
      if (stopSellBtn) stopSellBtn.style.display = 'block';
    }

    // update totals after switching
    computeBuyTotal();
    computeSellTotal();
  }

  // attach tab click handlers
  if (tabs.length) {
    tabs.forEach(tab => {
      tab.addEventListener('click', function(evt){
        // prevent default if it's an <a> later
        evt.preventDefault && evt.preventDefault();
        tabs.forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        const type = this.dataset.type;
        showOrderType(type);
      });
    });
  }

  // Live calculations
  function computeBuyTotal() {
    if (!buyQtyInput || !buyTotalEl) return;
    const qty = toFloat(buyQtyInput.value);
    // price for limit orders comes from buy_price, for market orders we attempt to use market_sell_price element
    let price = toFloat(buyPriceInput?.value);
    if (!price) {
      const marketPriceEl = qs('#market_sell_price');
      if (marketPriceEl) price = toFloat(marketPriceEl.textContent || marketPriceEl.innerText);
    }
    const total = qty * price;
    buyTotalEl.textContent = isFinite(total) ? total.toFixed(8).replace(/\.?0+$/,'') : '--';
  }

  function computeSellTotal() {
    if (!sellQtyInput || !sellTotalEl) return;
    const qty = toFloat(sellQtyInput.value);
    let price = toFloat(sellPriceInput?.value);
    if (!price) {
      const marketPriceEl = qs('#market_sell_price');
      if (marketPriceEl) price = toFloat(marketPriceEl.textContent || marketPriceEl.innerText);
    }
    const total = qty * price;
    sellTotalEl.textContent = isFinite(total) ? total.toFixed(8).replace(/\.?0+$/,'') : '--';
  }

  // Percentage buttons handler: percent (string e.g. '25'), side = 'buy'|'sell'
  window.Percentage = function(percent, side){
    percent = toFloat(percent);
    if (side === 'buy') {
      // determine quote balance (e.g. USDT) from #base_coin
      const balance = toFloat(baseBalanceEl?.textContent || baseBalanceEl?.innerText || 0);
      // choose price
      let price = toFloat(buyPriceInput?.value);
      if (!price) {
        const marketPriceEl = qs('#market_sell_price');
        price = toFloat(marketPriceEl?.textContent || marketPriceEl?.innerText || 0);
      }
      if (price <= 0) {
        console.warn('Cannot calculate buy quantity: invalid price');
        return;
      }
      const funds = balance * (percent/100);
      const qty = funds / price;
      if (buyQtyInput) buyQtyInput.value = qty.toFixed(8);
      computeBuyTotal();
    } else {
      // sell uses base token balance (#user_coin)
      const balance = toFloat(userBaseEl?.textContent || userBaseEl?.innerText || 0);
      const qty = balance * (percent/100);
      if (sellQtyInput) sellQtyInput.value = qty.toFixed(8);
      computeSellTotal();
    }
  };

  // Wire live compute events
  [buyPriceInput, buyQtyInput].forEach(el => el && el.addEventListener('input', computeBuyTotal));
  [sellPriceInput, sellQtyInput].forEach(el => el && el.addEventListener('input', computeSellTotal));

  // Also recompute when market price element changes (if you update it via JS)
  (function watchMarketPrice(){
    const marketPriceEl = qs('#market_sell_price');
    if (!marketPriceEl) return;
    let last = marketPriceEl.textContent;
    setInterval(() => {
      const cur = marketPriceEl.textContent;
      if (cur !== last) {
        last = cur;
        computeBuyTotal();
        computeSellTotal();
      }
    }, 700);
  })();

  // Simple trade functions (stubs) — replace with AJAX / form submission
  window.tradeadd_buy = function(type) {
    const qty = toFloat(buyQtyInput?.value);
    const price = toFloat(buyPriceInput?.value) || toFloat(qs('#market_sell_price')?.textContent || 0);
    if (!qty || !price) { alert('Enter quantity and price (or ensure market price is available).'); return; }
    // Example: POST to server via fetch (replace URL and payload as needed)
    console.log('tradeadd_buy', { type, qty, price });
    alert(`BUY order: ${qty} @ ${price} (${type}) — integrate with backend.`);
  };

  window.tradeadd_sell = function(type) {
    const qty = toFloat(sellQtyInput?.value);
    const price = toFloat(sellPriceInput?.value) || toFloat(qs('#market_sell_price')?.textContent || 0);
    if (!qty || !price) { alert('Enter quantity and price (or ensure market price is available).'); return; }
    console.log('tradeadd_sell', { type, qty, price });
    alert(`SELL order: ${qty} @ ${price} (${type}) — integrate with backend.`);
  };

  window.stopadd_buy = function() {
    alert('STOP BUY clicked — integrate stop-limit logic.');
  };
  window.stopadd_sell = function() {
    alert('STOP SELL clicked — integrate stop-limit logic.');
  };

  // Initialize UI to Limit
  showOrderType('limit');

  // Expose compute functions for debugging
  window.__orderForm = { computeBuyTotal, computeSellTotal, showOrderType };

})();
</script>

@endpush

@push('scripts')
<script>
// Trading configuration
const tradingConfig = {
    pairId: {{ $tradingPair->id }},
    baseSymbol: '{{ $tradingPair->baseCurrency->symbol }}',
    quoteSymbol: '{{ $tradingPair->quoteCurrency->symbol }}',
    tradingFee: {{ $tradingPair->trading_fee }},
    currentPrice: {{ $tradingPair->getCurrentPrice() ?? 0 }},
    baseBalance: {{ ($baseWallet->balance ?? 0) - ($baseWallet->locked_balance ?? 0) }},
    quoteBalance: {{ ($quoteWallet->balance ?? 0) - ($quoteWallet->locked_balance ?? 0) }},
    pricePrecision: {{ $tradingPair->price_precision ?? 8 }},
    quantityPrecision: {{ $tradingPair->quantity_precision ?? 8 }}
};

// Debug logging
console.log('Trading Config:', tradingConfig);
console.log('Base Balance:', tradingConfig.baseBalance);
console.log('Quote Balance:', tradingConfig.quoteBalance);

// Order type management
// Order type management - FIXED
function switchOrderType(type) {
    console.log('Switching to order type:', type);
    
    // Update active nav link
    document.querySelectorAll('.nav-pills .nav-link').forEach(link => {
        link.classList.remove('active');
    });
    event.target.classList.add('active');

    // Show/hide relevant elements
    const showStop = type === 'stop_limit'; // Changed from 'stop' to 'stop_limit'
    const showPrice = type !== 'market';
    
    // Buy side elements
    document.getElementById('buystop').style.display = showStop ? 'flex' : 'none';
    document.getElementById('buypricebox').style.display = showPrice ? 'flex' : 'none';
    
    // Sell side elements
    document.getElementById('sellstop').style.display = showStop ? 'flex' : 'none';
    document.getElementById('sellpricebox').style.display = showPrice ? 'flex' : 'none';
    
    // Buy buttons
    document.getElementById('stopbuybutton').style.display = showStop ? 'block' : 'none';
    document.getElementById('limitbuybutton').style.display = type === 'limit' ? 'block' : 'none';
    document.getElementById('marketbuybutton').style.display = type === 'market' ? 'block' : 'none';
    
    // Sell buttons
    document.getElementById('stopsellbutton').style.display = showStop ? 'block' : 'none';
    document.getElementById('limitsellbutton').style.display = type === 'limit' ? 'block' : 'none';
    document.getElementById('marketsellbutton').style.display = type === 'market' ? 'block' : 'none';

    // For market orders, set price to current market price and recalculate
    if (type === 'market') {
        const marketPrice = tradingConfig.currentPrice || 0;
        document.getElementById('buy_price').value = marketPrice.toFixed(tradingConfig.pricePrecision);
        document.getElementById('sell_price').value = marketPrice.toFixed(tradingConfig.pricePrecision);
        calculateBuyTotal();
        calculateSellTotal();
    }
}


// Increment value function
function incrementValue(inputId, increment) {
    const input = document.getElementById(inputId);
    const currentValue = parseFloat(input.value) || 0;
    const newValue = currentValue + increment;
    input.value = newValue > 0 ? newValue.toFixed(tradingConfig.pricePrecision) : '0';
    
    if (inputId.includes('buy')) {
        calculateBuyTotal();
    } else {
        calculateSellTotal();
    }
}

// Percentage calculations
function setPercentage(percentage, side) {
    console.log(`Setting ${percentage}% for ${side} side`);
    
    if (side === 'buy') {
        const availableBalance = tradingConfig.quoteBalance || 0;
        const currentPrice = parseFloat(document.getElementById('buy_price').value) || tradingConfig.currentPrice || 0;
        
        if (currentPrice > 0 && availableBalance > 0) {
            const maxQuantity = (availableBalance * (percentage / 100)) / currentPrice;
            document.getElementById('buy_num').value = maxQuantity.toFixed(tradingConfig.quantityPrecision);
            document.getElementById('buy_range').value = percentage;
            calculateBuyTotal();
        } else {
            console.warn('Invalid price or balance for buy calculation');
            showNotification('Please set a valid price first', 'error');
        }
    } else {
        const availableBalance = tradingConfig.baseBalance || 0;
        if (availableBalance > 0) {
            const quantity = availableBalance * (percentage / 100);
            document.getElementById('sell_num').value = quantity.toFixed(tradingConfig.quantityPrecision);
            document.getElementById('sell_range').value = percentage;
            calculateSellTotal();
        } else {
            console.warn('Invalid balance for sell calculation');
            showNotification('Insufficient balance', 'error');
        }
    }
}

// Update from range slider
function updateFromRange(side, percentage) {
    console.log(`Range updated: ${side} - ${percentage}%`);
    
    if (side === 'buy') {
        const availableBalance = tradingConfig.quoteBalance || 0;
        const currentPrice = parseFloat(document.getElementById('buy_price').value) || tradingConfig.currentPrice || 0;
        
        if (currentPrice > 0 && availableBalance > 0) {
            const maxQuantity = (availableBalance * (percentage / 100)) / currentPrice;
            document.getElementById('buy_num').value = maxQuantity.toFixed(tradingConfig.quantityPrecision);
            calculateBuyTotal();
        }
    } else {
        const availableBalance = tradingConfig.baseBalance || 0;
        if (availableBalance > 0) {
            const quantity = availableBalance * (percentage / 100);
            document.getElementById('sell_num').value = quantity.toFixed(tradingConfig.quantityPrecision);
            calculateSellTotal();
        }
    }
}

// Total calculations
function calculateBuyTotal() {
    const price = parseFloat(document.getElementById('buy_price').value) || 0;
    const quantity = parseFloat(document.getElementById('buy_num').value) || 0;
    const total = price * quantity;
    
    console.log('Buy calculation:', { price, quantity, total });
    
    document.getElementById('buy_mum').textContent = total.toFixed(8);
    
    // Update range slider based on percentage of available balance
    if (tradingConfig.quoteBalance > 0) {
        const percentage = (total / tradingConfig.quoteBalance) * 100;
        document.getElementById('buy_range').value = Math.min(percentage, 100);
    }
}

function calculateSellTotal() {
    const price = parseFloat(document.getElementById('sell_price').value) || 0;
    const quantity = parseFloat(document.getElementById('sell_num').value) || 0;
    const total = price * quantity;
    
    console.log('Sell calculation:', { price, quantity, total });
    
    document.getElementById('sell_mum').textContent = total.toFixed(8);
    
    // Update range slider based on percentage of available balance
    if (tradingConfig.baseBalance > 0) {
        const percentage = (quantity / tradingConfig.baseBalance) * 100;
        document.getElementById('sell_range').value = Math.min(percentage, 100);
    }
}

// Order placement - FIXED VERSION
async function placeOrder(side, type) {
    try {
        const quantityInput = document.getElementById(side + '_num');
        const priceInput = document.getElementById(side + '_price');
        const stopInput = document.getElementById(side + '_stop');

        const quantity = parseFloat(quantityInput.value);
        
        // Handle different order types
        let price = null;
        let stopPrice = null;

        if (type === 'market') {
            // Market orders use current price but don't send it to backend
            price = tradingConfig.currentPrice;
        } else if (type === 'limit') {
            price = parseFloat(priceInput.value);
        } else if (type === 'stop_limit') {
            price = parseFloat(priceInput.value);
            stopPrice = parseFloat(stopInput.value);
        }

        console.log('Order details:', { side, type, quantity, price, stopPrice });

        // Validation
        if (!quantity || quantity <= 0 || isNaN(quantity)) {
            showNotification('Please enter a valid quantity', 'error');
            quantityInput.focus();
            return;
        }

        if ((type === 'limit' || type === 'stop_limit') && (!price || price <= 0 || isNaN(price))) {
            showNotification('Please enter a valid price', 'error');
            priceInput.focus();
            return;
        }

        if (type === 'stop_limit' && (!stopPrice || stopPrice <= 0 || isNaN(stopPrice))) {
            showNotification('Please enter a valid stop price', 'error');
            stopInput.focus();
            return;
        }

        // Balance validation
        if (side === 'buy') {
            const requiredAmount = quantity * (price || tradingConfig.currentPrice);
            const requiredWithFee = requiredAmount + (requiredAmount * tradingConfig.tradingFee);
            if (requiredWithFee > tradingConfig.quoteBalance) {
                showNotification(`Insufficient ${tradingConfig.quoteSymbol} balance. Required: ${requiredWithFee.toFixed(8)}`, 'error');
                return;
            }
        } else {
            if (quantity > tradingConfig.baseBalance) {
                showNotification(`Insufficient ${tradingConfig.baseSymbol} balance. Available: ${tradingConfig.baseBalance}`, 'error');
                return;
            }
        }

        // Prepare form data based on order type
        const formData = {
            trading_pair_id: tradingConfig.pairId,
            type: type === 'stop_limit' ? 'stop_limit' : type, // Map to correct type
            side: side,
            quantity: quantity,
        };

        // Add price only for limit and stop_limit orders
        if (type === 'limit' || type === 'stop_limit') {
            formData.price = price;
        }

        // Add stop_price only for stop_limit orders
        if (type === 'stop_limit') {
            formData.stop_price = stopPrice;
        }

        console.log('Sending order:', formData);

        const response = await fetch('{{ route("trade.place-order") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        const result = await response.json();
        console.log('Order response:', result);

        if (result.success) {
            showNotification('Order placed successfully!', 'success');
            // Reset form
            document.getElementById(side + '_num').value = '';
            document.getElementById(side + '_range').value = 0;
            document.getElementById(side + '_mum').textContent = '0.00000000';
            
            // Reload the page to update balances after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        console.error('Order placement error:', error);
        showNotification('Failed to place order. Please try again.', 'error');
    }
}

// Cancel order function
async function cancelOrder(orderId) {
    if (!confirm('Are you sure you want to cancel this order?')) return;

    try {
        const response = await fetch(`/trade/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });

        const result = await response.json();

        if (result.success) {
            showNotification('Order cancelled successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        console.error('Order cancellation error:', error);
        showNotification('Failed to cancel order. Please try again.', 'error');
    }
}

// Utility function for notifications
function showNotification(message, type) {
    // Remove any existing notifications
    document.querySelectorAll('.trade-notification').forEach(notification => {
        notification.remove();
    });

    const alertType = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert ${alertType} alert-dismissible fade show trade-notification position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Initialize trading interface
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing trading interface...');
    
    // Set initial values for market price
    const initialPrice = tradingConfig.currentPrice || 0;
    document.getElementById('buy_price').value = initialPrice.toFixed(tradingConfig.pricePrecision);
    document.getElementById('sell_price').value = initialPrice.toFixed(tradingConfig.pricePrecision);
    
    // Add event listeners
    document.getElementById('buy_price').addEventListener('input', calculateBuyTotal);
    document.getElementById('buy_num').addEventListener('input', calculateBuyTotal);
    document.getElementById('sell_price').addEventListener('input', calculateSellTotal);
    document.getElementById('sell_num').addEventListener('input', calculateSellTotal);
    
    // Initial calculations
    calculateBuyTotal();
    calculateSellTotal();
    
    console.log('Trading interface initialized successfully');
});
</script>

<style>
.market-trade-buy {
    padding-right: 15px;
}

.market-trade-sell {
    padding-left: 15px;
}

.market-trade-list {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 10px 0;
}

.market-trade-list li {
    flex: 1;
    text-align: center;
}

.market-trade-list li a {
    display: block;
    padding: 5px;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    margin: 0 2px;
    border-radius: 3px;
    text-decoration: none;
    color: #495057;
    font-size: 12px;
}

.market-trade-list li a:hover {
    background-color: #e9ecef;
}

.btn-increment {
    background-color: #f8f9fa;
    border: 1px solid #ced4da;
}

.btn-increment:hover {
    background-color: #e9ecef;
}

.buy-trade {
    background-color: #00c9a7;
    border-color: #00c9a7;
    color: white;
    width: 100%;
}

.sell {
    background-color: #ed4b42;
    border-color: #ed4b42;
    color: white;
    width: 100%;
}

.buy-trade:hover, .sell:hover {
    opacity: 0.9;
    color: white;
}

.green { color: #00c9a7; }
.red { color: #ed4b42; }

.input-group {
    margin-bottom: 10px;
}

input[type="range"] {
    width: 100%;
    margin: 10px 0;
}

@media (max-width: 768px) {
    .market-trade-buy {
        padding-right: 0;
        margin-bottom: 20px;
        border-bottom: 1px solid #eee;
        padding-bottom: 20px;
    }
    
    .market-trade-sell {
        padding-left: 0;
    }
    
    .hide-mobile {
        display: none !important;
    }
}
</style>
@endpush