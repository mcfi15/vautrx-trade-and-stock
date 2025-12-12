<div class="h-40">
    <div class="main-chart mb15">
        <!-- TradingView Widget Start -->
        <div class="tradingview-widget-container">
            <div class="charttcontnr" id="tradingview_chart_container"></div>
            
            <script>
// Global check
let chartInitialized = false;

document.addEventListener('DOMContentLoaded', function() {
    startChartOnce();
});

function startChartOnce() {
    if (chartInitialized) return;
    chartInitialized = true;

    const base = "{{ $tradingPair->baseCurrency->symbol }}";
    const quote = "{{ $tradingPair->quoteCurrency->symbol }}";

    const symbol =
        quote === "EUR"
            ? `COINBASE:${base}${quote}`
            : `BINANCE:${base}${quote}`;

    const height = window.innerWidth <= 768 ? 300 : 370;

    // DO NOT touch container ID
    new TradingView.widget({
        width: "100%",
        height: height,
        symbol: symbol,
        interval: "D",
        theme: "dark",
        timezone: "Etc/UTC",
        style: "1",
        locale: "en",
        allow_symbol_change: true,
        withdateranges: true,
        hide_legend: true,
        enable_publishing: false,
        container_id: "tradingview_chart_container",
        studies: ["RSI@tv-basicstudies", "StochasticRSI@tv-basicstudies", "MASimple@tv-basicstudies"]
    });
}

// DISABLE all resize triggers
// Mobile resize should NOT reload
window.addEventListener("resize", function () {
    // Do nothing
});
</script>

        </div>
        <!-- TradingView Widget End -->
    </div>
</div>