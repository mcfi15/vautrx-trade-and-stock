<div class="order-book mb15">
    <h2 class="heading white-bg">Order Book</h2>

    <div class="action-tools d-flex justify-content-between">
        <div class="filter-buttons">
            <button id="defaultModeButton" class="active">Default</button>
            <button id="buyModeButton">Buy</button>
            <button id="sellModeButton">Sell</button>
        </div>

        <div>
            <select class="form-control" id="decimalplaces">
                <option value="8">0.00000001</option>
                <option value="7">0.0000001</option>
                <option value="6">0.000001</option>
                <option value="5">0.00001</option>
                <option value="4">0.0001</option>
                <option value="3">0.001</option>
            </select>
        </div>
    </div>

    <div id="bsorders">
        <table class="table buysellorders">
            <thead>
                <tr class="d-flex w-100 justify-content-between">
                    <th scope="col">Price ({{ $tradingPair->quoteCurrency->symbol }})</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Total</th>
                </tr>
            </thead>

            {{-- ✅ SELL ORDERS (Asks) --}}
            <tbody class="selling sellMobtbody" id="sellorderlist">
                @foreach($sellOrders as $order)
                <tr class="sell-row local-order" data-price="{{ $order->price }}" data-amount="{{ $order->amount }}">
                    <td class="text-danger price">{{ number_format($order->price, 8) }}</td>
                    <td class="amount">{{ number_format($order->amount, 6) }}</td>
                    <td class="total">{{ number_format($order->price * $order->amount, 6) }}</td>
                </tr>
                @endforeach
            </tbody>

            {{-- ✅ MIDDLE PRICE ROW --}}
            <tbody class="ob-heading">
                <tr>
                    <td>
                        <span id="currentPrice">{{ optional($recentTrades->first())->price ?? '--' }}</span>
                    </td>
                    <td>{{ $tradingPair->baseCurrency->symbol }}/{{ $tradingPair->quoteCurrency->symbol }}</td>
                    <td id="currentPriceRight">{{ optional($recentTrades->first())->price ?? '--' }}</td>
                </tr>
            </tbody>

            {{-- ✅ BUY ORDERS (Bids) --}}
            <tbody class="buying buyMobtbody" id="buyorderlist">
                @foreach($buyOrders as $order)
                <tr class="buy-row local-order" data-price="{{ $order->price }}" data-amount="{{ $order->amount }}">
                    <td class="text-success price">{{ number_format($order->price, 8) }}</td>
                    <td class="amount">{{ number_format($order->amount, 6) }}</td>
                    <td class="total">{{ number_format($order->price * $order->amount, 6) }}</td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>