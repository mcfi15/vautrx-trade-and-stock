<div class="col-md-12 chart_s3">
     <div class="h-40">
    <div class="main-chart mb15">
        <!-- TradingView Widget Start -->
        <div class="tradingview-widget-container">
            <div class="charttcontnr" id="tradingview_chart_container"></div>
            <script src="{{ asset('Public/s3.tradingview.com/tv.js') }}"></script>


           <script>
// Prevent multiple chart initializations
let chartInitialized = false;

const tradingConfig = {
    baseSymbol: '{{ $tradingPair->baseCurrency->symbol }}',
    quoteSymbol: '{{ $tradingPair->quoteCurrency->symbol }}',
    currentPrice: {{ $tradingPair->getCurrentPrice() ?? 0 }}
};

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

    return quoteSymbol === 'EUR'
        ? `COINBASE:${formattedBase}${formattedQuote}`
        : `BINANCE:${formattedBase}${formattedQuote}`;
}

function initializeTradingViewChart() {
    // ❌ STOP if already initialized (prevents reload on mobile)
    if (chartInitialized) return;
    chartInitialized = true;

    const symbol = getTradingViewSymbol(tradingConfig.baseSymbol, tradingConfig.quoteSymbol);
    const chartHeight = window.innerWidth <= 768 ? 300 : 370;

    new TradingView.widget({
        "width": "100%",
        "height": chartHeight,
        "symbol": symbol,
        "interval": "D",
        "timezone": "Etc/UTC",
        "theme": "dark",
        "style": "1",
        "locale": "en",
        "toolbar_bg": "#f1f3f6",
        "enable_publishing": false,
        "withdateranges": true,
        "allow_symbol_change": true,
        "show_popup_button": true,
        "popup_width": "1000",
        "popup_height": "650",
        "hide_legend": true,
        "container_id": "tradingview_chart_container",
        "studies": ["RSI@tv-basicstudies","StochasticRSI@tv-basicstudies","MASimple@tv-basicstudies"]
    });
}

document.addEventListener('DOMContentLoaded', initializeTradingViewChart);

// ❌ REMOVE THIS to prevent reload
// window.addEventListener('resize', function() {
//     setTimeout(initializeTradingViewChart, 300);
// });
</script>

        </div>
        <!-- TradingView Widget End -->
    </div>
</div>
    </div>