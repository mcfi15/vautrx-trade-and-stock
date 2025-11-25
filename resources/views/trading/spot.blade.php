@extends('layouts.app')

@section('title', 'Dashboard - Crypto Trading Platform')

@section('content')

<style>
body, html{
    overflow-x: hidden;
}
.sm_screen{
	display: none;
}
.order-book tbody tr{
  flex-shrink: 0;
}
@media only screen and (max-width: 768px){
	.lg_screen{
		display: none;
	}.sm_screen{
		display: flex;
	}
	.outflexboxx {
		display: block !important;
	}
}
</style>
<script type="text/javascript">
     let market = "btc_usdt";
     let market_round = "2";

     let market_round_num="2";
     let market_type="1";
     let userid = "0";
     let trade_moshi = 1;
     let getDepth_tlme = null;
     let trans_lock = 0;
  if(""){
    const colorshade = "";
  }
  else{
    const colorshade = "Dark";
  }
  $(document).ready(function(){
	if(window.innerWidth <= 768){
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
        <i style="color:#1e90ff" class="fa fa-star add-to-favorite default"></i>
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
      <a href="#!" class="btn changeThemeLight"><i style="color:#1e90ff" class="fa fa-moon-o"></i></a>
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
{{-- <input class="hide" style="display:none" id="socket_data" value="0"> --}}
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
// Trading interface functionality with iOS compatibility - COMPLETE FIXED VERSION
document.addEventListener('DOMContentLoaded', function() {
    console.log('Trading interface initializing...');
    
    // Tab switching functionality with complete iOS isolation
    const orderTypeTabs = document.querySelectorAll('#orderTypeTabs .nav-link');
    
    // Function to switch tabs - completely isolated from buy/sell events
    function switchOrderType(type, element) {
        console.log('Switching to order type:', type, ' - element:', element);
        
        // Update active tab
        orderTypeTabs.forEach(tab => {
            tab.classList.remove('active');
        });
        element.classList.add('active');
        
        // Show/hide appropriate elements based on order type
        switch(type) {
            case 'limit':
                // Limit order - show price inputs, hide stop inputs
                document.getElementById('buypricebox').style.display = 'flex';
                document.getElementById('sellpricebox').style.display = 'flex';
                document.getElementById('buystop').style.display = 'none';
                document.getElementById('sellstop').style.display = 'none';
                
                // Show limit buttons, hide others
                document.getElementById('limitbuybutton').style.display = 'block';
                document.getElementById('limitsellbutton').style.display = 'block';
                document.getElementById('marketbuybutton').style.display = 'none';
                document.getElementById('marketsellbutton').style.display = 'none';
                document.getElementById('stopbuybutton').style.display = 'none';
                document.getElementById('stopsellbutton').style.display = 'none';
                break;
                
            case 'market':
                // Market order - hide price and stop inputs
                document.getElementById('buypricebox').style.display = 'none';
                document.getElementById('sellpricebox').style.display = 'none';
                document.getElementById('buystop').style.display = 'none';
                document.getElementById('sellstop').style.display = 'none';
                
                // Show market buttons, hide others
                document.getElementById('marketbuybutton').style.display = 'block';
                document.getElementById('marketsellbutton').style.display = 'block';
                document.getElementById('limitbuybutton').style.display = 'none';
                document.getElementById('limitsellbutton').style.display = 'none';
                document.getElementById('stopbuybutton').style.display = 'none';
                document.getElementById('stopsellbutton').style.display = 'none';
                break;
                
            case 'stop':
                // Stop-limit order - show both price and stop inputs
                document.getElementById('buypricebox').style.display = 'flex';
                document.getElementById('sellpricebox').style.display = 'flex';
                document.getElementById('buystop').style.display = 'flex';
                document.getElementById('sellstop').style.display = 'flex';
                
                // Show stop buttons, hide others
                document.getElementById('stopbuybutton').style.display = 'block';
                document.getElementById('stopsellbutton').style.display = 'block';
                document.getElementById('limitbuybutton').style.display = 'none';
                document.getElementById('limitsellbutton').style.display = 'none';
                document.getElementById('marketbuybutton').style.display = 'none';
                document.getElementById('marketsellbutton').style.display = 'none';
                break;
        }
    }
    
    // COMPLETE ISOLATION: Add event listeners that completely prevent any event propagation
    orderTypeTabs.forEach(tab => {
        // Remove any existing event listeners first
        const newTab = tab.cloneNode(true);
        tab.parentNode.replaceChild(newTab, tab);
    });

    // Re-select tabs after cloning
    const newOrderTypeTabs = document.querySelectorAll('#orderTypeTabs .nav-link');
    
    newOrderTypeTabs.forEach(tab => {
        // Primary click handler - completely isolated
        const handleTabClick = function(e) {
            console.log('Tab click intercepted - preventing all propagation');
            
            // COMPLETE EVENT ISOLATION
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            // Force the event to die here
            if (e.cancelable) e.preventDefault();
            
            const type = this.getAttribute('data-type');
            console.log('Switching to:', type);
            switchOrderType(type, this);
            
            return false;
        };
        
        // Add multiple event listeners with complete isolation
        tab.addEventListener('click', handleTabClick, true); // Use capture phase
        
        tab.addEventListener('touchstart', function(e) {
            console.log('Touch start on tab - preventing all');
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            handleTabClick.call(this, e);
        }, { passive: false, capture: true });
        
        tab.addEventListener('touchend', function(e) {
            console.log('Touch end on tab - preventing all');
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
        }, { passive: false, capture: true });
        
        // Prevent any context menu or long press
        tab.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        });
    });

    // Initialize calculation events
    initializeCalculationEvents();
    
    // Add protection to buy/sell buttons to ensure they're only called intentionally
    protectOrderButtons();
});

// Protect buy/sell buttons from accidental triggers
function protectOrderButtons() {
    const buyButtons = document.querySelectorAll('[onclick*="placeOrder(\'buy\'"]');
    const sellButtons = document.querySelectorAll('[onclick*="placeOrder(\'sell\'"]');
    
    // Remove existing onclick handlers and replace with protected versions
    buyButtons.forEach(button => {
        const originalOnClick = button.getAttribute('onclick');
        button.removeAttribute('onclick');
        button.addEventListener('click', function(e) {
            console.log('Buy button clicked intentionally');
            e.stopPropagation();
            // Extract the type from the original onclick
            const match = originalOnClick.match(/placeOrder\('buy', '([^']+)'\)/);
            if (match) {
                placeOrder('buy', match[1]);
            }
        });
    });
    
    sellButtons.forEach(button => {
        const originalOnClick = button.getAttribute('onclick');
        button.removeAttribute('onclick');
        button.addEventListener('click', function(e) {
            console.log('Sell button clicked intentionally');
            e.stopPropagation();
            // Extract the type from the original onclick
            const match = originalOnClick.match(/placeOrder\('sell', '([^']+)'\)/);
            if (match) {
                placeOrder('sell', match[1]);
            }
        });
    });
}

// Input calculation functions
function initializeCalculationEvents() {
    // Buy side calculations
    const buyPrice = document.getElementById('buy_price');
    const buyNum = document.getElementById('buy_num');
    
    if (buyPrice && buyNum) {
        buyPrice.addEventListener('input', calculateBuyTotal);
        buyNum.addEventListener('input', calculateBuyTotal);
    }
    
    // Sell side calculations
    const sellPrice = document.getElementById('sell_price');
    const sellNum = document.getElementById('sell_num');
    
    if (sellPrice && sellNum) {
        sellPrice.addEventListener('input', calculateSellTotal);
        sellNum.addEventListener('input', calculateSellTotal);
    }
}

function calculateBuyTotal() {
    const price = parseFloat(document.getElementById('buy_price').value) || 0;
    const quantity = parseFloat(document.getElementById('buy_num').value) || 0;
    const total = price * quantity;
    const buyMum = document.getElementById('buy_mum');
    
    if (buyMum) {
        buyMum.textContent = total > 0 ? total.toFixed(8) : '--';
    }
}

function calculateSellTotal() {
    const price = parseFloat(document.getElementById('sell_price').value) || 0;
    const quantity = parseFloat(document.getElementById('sell_num').value) || 0;
    const total = price * quantity;
    const sellMum = document.getElementById('sell_mum');
    
    if (sellMum) {
        sellMum.textContent = total > 0 ? total.toFixed(8) : '--';
    }
}

// Percentage functions
function setPercentage(percent, side) {
    console.log('Setting percentage:', percent, side);
    
    const balance = side === 'buy' ? 
        parseFloat('{{ number_format($quoteWallet->available_balance ?? (($quoteWallet->balance ?? 0) - ($quoteWallet->locked_balance ?? 0)), 8) }}'.replace(/,/g, '')) :
        parseFloat('{{ number_format($baseWallet->available_balance ?? (($baseWallet->balance ?? 0) - ($baseWallet->locked_balance ?? 0)), 8) }}'.replace(/,/g, ''));
    
    const percentage = parseFloat(percent) / 100;
    const amount = balance * percentage;
    
    if (side === 'buy') {
        const buyPrice = parseFloat(document.getElementById('buy_price').value) || 1;
        const quantity = amount / buyPrice;
        document.getElementById('buy_num').value = quantity > 0 ? quantity.toFixed(8) : '';
        document.getElementById('buy_range').value = percent;
        calculateBuyTotal();
    } else {
        document.getElementById('sell_num').value = amount > 0 ? amount.toFixed(8) : '';
        document.getElementById('sell_range').value = percent;
        calculateSellTotal();
    }
}

// Range update functions
function updateFromRange(side, value) {
    console.log('Updating range:', side, value);
    setPercentage(value, side);
}

// Increment functions
function incrementValue(inputId, increment) {
    const input = document.getElementById(inputId);
    if (input) {
        const currentValue = parseFloat(input.value) || 0;
        const newValue = currentValue + increment;
        input.value = newValue.toFixed(8);
        
        // Trigger calculations
        if (inputId.includes('buy')) {
            calculateBuyTotal();
        } else if (inputId.includes('sell')) {
            calculateSellTotal();
        }
    }
}

// Order placement function that actually calls your Laravel controller
async function placeOrder(side, type) {
    console.log('Place order called:', side, type);
    
    // Immediate feedback that function was called intentionally
    if (window.placeOrderBlocked) {
        console.log('Order placement blocked - too frequent');
        return;
    }
    
    // Block multiple rapid calls
    window.placeOrderBlocked = true;
    setTimeout(() => {
        window.placeOrderBlocked = false;
    }, 1000);
    
    // Get values based on order type
    let price, quantity, stopPrice;
    const tradingPairId = document.getElementById('tradingPairId').value;
    
    try {
        // Validate and get order values
        if (type === 'limit') {
            price = side === 'buy' ? 
                document.getElementById('buy_price').value : 
                document.getElementById('sell_price').value;
            quantity = side === 'buy' ? 
                document.getElementById('buy_num').value : 
                document.getElementById('sell_num').value;
        } else if (type === 'market') {
            quantity = side === 'buy' ? 
                document.getElementById('buy_num').value : 
                document.getElementById('sell_num').value;
            price = null; // Market orders don't need price
        } else if (type === 'stop') {
            price = side === 'buy' ? 
                document.getElementById('buy_price').value : 
                document.getElementById('sell_price').value;
            quantity = side === 'buy' ? 
                document.getElementById('buy_num').value : 
                document.getElementById('sell_num').value;
            stopPrice = side === 'buy' ? 
                document.getElementById('buy_stop').value : 
                document.getElementById('sell_stop').value;
        }
        
        console.log('Order details:', { side, type, price, quantity, stopPrice, tradingPairId });
        
        // Validate inputs
        if (!quantity || parseFloat(quantity) <= 0) {
            alert('Please enter a valid quantity');
            return;
        }
        
        if ((type === 'limit' || type === 'stop') && (!price || parseFloat(price) <= 0)) {
            alert('Please enter a valid price');
            return;
        }
        
        if (type === 'stop' && (!stopPrice || parseFloat(stopPrice) <= 0)) {
            alert('Please enter a valid stop price');
            return;
        }
        
        // Show loading state
        const button = document.querySelector(`.${side === 'buy' ? 'buy-trade' : 'sell'}`);
        const originalText = button.textContent;
        button.textContent = 'Placing...';
        button.disabled = true;
        
        // Prepare data for API call
        const formData = new FormData();
        formData.append('trading_pair_id', tradingPairId);
        formData.append('side', side);
        formData.append('type', type === 'stop' ? 'stop_limit' : type); // Map 'stop' to 'stop_limit'
        formData.append('quantity', quantity);
        
        if (price) {
            formData.append('price', price);
        }
        
        if (stopPrice) {
            formData.append('stop_price', stopPrice);
        }
        
        // Add CSRF token for Laravel
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken) {
            formData.append('_token', csrfToken);
        }
        
        console.log('Sending order data to server...');
        
        // Make actual API call to your Laravel backend
        const response = await fetch('/trade/place-order', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken || ''
            },
            body: formData
        });
        
        const result = await response.json();
        
        // Reset button
        button.textContent = originalText;
        button.disabled = false;
        
        if (result.success) {
            console.log('Order placed successfully:', result);
            alert(`Order placed successfully!`);
            
            // Reset form
            if (side === 'buy') {
                document.getElementById('buy_num').value = '';
                document.getElementById('buy_range').value = 0;
                calculateBuyTotal();
            } else {
                document.getElementById('sell_num').value = '';
                document.getElementById('sell_range').value = 0;
                calculateSellTotal();
            }
            
            // Refresh balances if needed
            refreshBalances();
            
        } else {
            console.error('Order failed:', result);
            alert(`Order failed: ${result.message}`);
        }
        
    } catch (error) {
        console.error('Error placing order:', error);
        
        // Reset button in case of error
        const button = document.querySelector(`.${side === 'buy' ? 'buy-trade' : 'sell'}`);
        if (button) {
            button.textContent = side === 'buy' ? 'Buy' : 'Sell';
            button.disabled = false;
        }
        
        alert('Error placing order: ' + error.message);
    }
}

// Function to refresh user balances after order placement
function refreshBalances() {
    console.log('Refresh balances after order placement');
    
    // Option 1: Reload the page to get updated balances
    setTimeout(() => {
        window.location.reload();
    }, 1000);
    
    // Option 2: Make an API call to update balances without reloading
    // updateWalletBalances();
}

// Optional: Function to update wallet balances via AJAX
async function updateWalletBalances() {
    try {
        const response = await fetch('/api/user/wallets');
        const wallets = await response.json();
        
        // Update buy side balance (quote currency)
        const quoteBalanceElement = document.querySelector('.market-trade-buy .w-full span');
        if (quoteBalanceElement && wallets.quote) {
            quoteBalanceElement.textContent = parseFloat(wallets.quote.available_balance).toFixed(8);
        }
        
        // Update sell side balance (base currency)
        const baseBalanceElement = document.getElementById('user_coin');
        if (baseBalanceElement && wallets.base) {
            baseBalanceElement.textContent = parseFloat(wallets.base.available_balance).toFixed(8);
        }
    } catch (error) {
        console.error('Failed to update balances:', error);
    }
}

// Login prompt function
function promptLogin() {
    alert('Please log in to place orders');
    // You can redirect to login page if needed
    // window.location.href = '/login';
}

// Global market search functionality
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

// Add iOS-specific CSS improvements
const iosStyle = document.createElement('style');
iosStyle.textContent = `
    #orderTypeTabs .nav-link {
        cursor: pointer;
        -webkit-tap-highlight-color: transparent;
        user-select: none;
        touch-action: manipulation;
        pointer-events: auto;
    }
    
    .btn {
        -webkit-tap-highlight-color: transparent;
        touch-action: manipulation;
        pointer-events: auto;
    }
    
    /* Completely isolate tab area from button area */
    .market-trade {
        pointer-events: auto;
    }
    
    #orderTypeTabs {
        pointer-events: auto;
        z-index: 10;
        position: relative;
    }
    
    .market-trade-buy, .market-trade-sell {
        pointer-events: auto;
    }
    
    /* Prevent zoom on input focus in iOS */
    @media screen and (max-width: 768px) {
        input[type="number"] {
            font-size: 16px !important;
        }
        
        .market-trade input {
            touch-action: manipulation;
        }
    }
    
    /* Improve touch targets for mobile */
    @media (max-width: 768px) {
        .nav-link {
            padding: 12px 16px !important;
            margin: 2px;
        }
        
        .btn {
            padding: 12px 16px !important;
        }
    }
`;
document.head.appendChild(iosStyle);

console.log('Trading interface JavaScript loaded successfully - COMPLETE FIXED VERSION');
</script>

<script>
// =======================
// EXTRA FUNCTIONS ADDED
// FROM SECOND SCRIPT
// =======================

// Cancel order function
async function cancelOrder(orderId) {
    if (!confirm('Are you sure you want to cancel this order?')) return;

    try {
        const response = await fetch(`/trade/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
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


// Notification popup
function showNotification(message, type) {
    // Remove existing notifications
    document.querySelectorAll('.trade-notification').forEach(n => n.remove());

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
        if (alertDiv.parentNode) alertDiv.remove();
    }, 5000);
}

</script>

<script>
function incrementValue(inputId, step) {
    const input = document.getElementById(inputId);

    if (!input) return;

    // If input is empty, treat as zero
    let current = parseFloat(input.value);
    if (isNaN(current)) current = 0;

    // Increase the value
    let newValue = current + step;

    // Format to 8 decimal places (since you use step="0.00000001")
    newValue = parseFloat(newValue).toFixed(8);

    input.value = newValue;

    // Trigger input events so totals update
    input.dispatchEvent(new Event('input'));
}
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