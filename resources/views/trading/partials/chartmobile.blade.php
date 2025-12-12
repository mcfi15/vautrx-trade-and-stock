<div class="col-md-12 chart_s3">
     <div class="h-40">
    <div class="main-chart mb15">
        <!-- TradingView Widget Start -->
        <div class="tradingview-widget-container">
            <div class="charttcontnr" id="tradingview_chart_container"></div>
            


<script>
let chartLoaded = false;

document.addEventListener('DOMContentLoaded', function () {
    if (!chartLoaded) {
        chartLoaded = true;
        initChart();
    }
});

function getTradingViewSymbol(base, quote) {
    const map = {
        USDT:'USDT', BTC:'BTC', ETH:'ETH', BNB:'BNB', ADA:'ADA',
        DOT:'DOT', LTC:'LTC', BCH:'BCH', XRP:'XRP', LINK:'LINK', EUR:'EUR'
    };
    const b = map[base] || base;
    const q = map[quote] || quote;

    return quote === "EUR"
        ? `COINBASE:${b}${q}`
        : `BINANCE:${b}${q}`;
}

function initChart() {
    const base = "{{ $tradingPair->baseCurrency->symbol }}";
    const quote = "{{ $tradingPair->quoteCurrency->symbol }}";
    const symbol = getTradingViewSymbol(base, quote);

    const height = window.innerWidth <= 768 ? 300 : 370;

    new TradingView.widget({
        autosize: true,
        height: height,
        symbol: symbol,
        interval: "D",
        theme: "dark",
        timezone: "Etc/UTC",
        style: "1",
        locale: "en",
        allow_symbol_change: true,
        hide_legend: true,
        enable_publishing: false,
        withdateranges: true,
        container_id: "tradingview_chart_container",
        studies: [
            "RSI@tv-basicstudies",
            "StochasticRSI@tv-basicstudies",
            "MASimple@tv-basicstudies"
        ]
    });
}

// DO NOT reinitialize chart on resize
window.addEventListener('resize', function () {
    // do nothing
});
</script>



        </div>
        <!-- TradingView Widget End -->
    </div>
</div>
    </div>