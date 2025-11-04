window.binanceSockets = {};

function connectBinanceWebSocket(symbol) {
    if (window.binanceSockets[symbol]) {
        console.log(symbol + " WebSocket already open");
        return;
    }

    let ws = new WebSocket(`wss://stream.binance.com:9443/ws/${symbol.toLowerCase()}@ticker`);

    ws.onopen = () => {
        console.log("✅ Binance WebSocket connected for", symbol);
    };

    ws.onmessage = (event) => {
        let data = JSON.parse(event.data);
        let price = parseFloat(data.c).toFixed(2);

        // Update price in HTML
        document.querySelectorAll(`[data-symbol="${symbol}"] .live-price`)
            .forEach(el => el.textContent = price);
    };

    ws.onerror = () => console.log("❌ WebSocket error for", symbol);
    ws.onclose = () => {
        console.log("⚠️ WebSocket closed, reconnecting...", symbol);
        delete window.binanceSockets[symbol];
        setTimeout(() => connectBinanceWebSocket(symbol), 3000);
    };

    window.binanceSockets[symbol] = ws;
}

window.startLivePrices = function(symbols) {
    symbols.forEach(symbol => connectBinanceWebSocket(symbol));
};
