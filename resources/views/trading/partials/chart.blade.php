<div class="h-40">
    <div class="main-chart mb15">
        <!-- TradingView Widget Start -->
        <div class="tradingview-widget-container">
            <div class="charttcontnr" id="tradingview_chart_container"></div>
            
            <script>
                // Trading configuration from backend - FIXED
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

// SIMPLIFIED Chart initialization - This is all you need
function initializeTradingViewChart() {
    // Get the correct symbol for the current trading pair
    const symbol = getTradingViewSymbol(tradingConfig.baseSymbol, tradingConfig.quoteSymbol);
    
    // Set appropriate height
    const chartHeight = window.innerWidth <= 768 ? 300 : 370;
    
    // Use consistent container ID
    const containerId = 'tradingview_chart_container';
    
    // Make sure our container has the correct ID
    const chartContainer = document.querySelector('.charttcontnr');
    if (chartContainer) {
        chartContainer.id = containerId;
    }

    console.log('Initializing TradingView chart with:', {
        symbol: symbol,
        baseSymbol: tradingConfig.baseSymbol,
        quoteSymbol: tradingConfig.quoteSymbol,
        containerId: containerId
    });

    // TradingView widget configuration
    new TradingView.widget({
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
        "container_id": containerId,
        "studies": [
            "RSI@tv-basicstudies",
            "StochasticRSI@tv-basicstudies", 
            "MASimple@tv-basicstudies"
        ]
    });
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeTradingViewChart();
});

// Reinitialize on window resize
window.addEventListener('resize', function() {
    // Small delay to ensure resize is complete
    setTimeout(initializeTradingViewChart, 300);
});
            </script>
        </div>
        <!-- TradingView Widget End -->
    </div>
</div>