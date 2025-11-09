<div class="order-book mb15">
    <h2 class="heading white-bg">Order Book</h2>
    <div class="action-tools">
        <div class="filter-buttons">
            <button data-bn-type="button" id="defaultModeButton" class="active">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11 21v-8H3v8h8zm-6-2v-4h4v4H5z" fill="#2EBD85"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11 11V3H3v8h8zM5 9V5h4v4H5z" fill="#F6465D"></path>
                    <path fill="currentColor" d="M13 3h8v2h-8zM13 19h8v2h-8zM13 11h8v2h-8v-2zM13 7h8v2h-8V7zM13 15h8v2h-8v-2z"></path>
                </svg>
            </button>
            <button data-bn-type="button" id="buyModeButton" class="">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11 21V3H3v18h8zm-6-2V5h4v14H5z" fill="#2EBD85"></path>
                    <path fill="currentColor" d="M13 3h8v2h-8zM13 19h8v2h-8zM13 11h8v2h-8v-2zM13 7h8v2h-8V7zM13 15h8v2h-8v-2z"></path>
                </svg>
            </button>
            <button data-bn-type="button" id="sellModeButton" class="">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11 21V3H3v18h8zm-6-2V5h4v14H5z" fill="#F6465D"></path>
                    <path fill="currentColor" d="M13 3h8v2h-8zM13 19h8v2h-8zM13 11h8v2h-8v-2zM13 7h8v2h-8V7zM13 15h8v2h-8v-2z"></path>
                </svg>
            </button>
        </div>
        <div class="order-book-drop">
            <select class="form-control" data-width="100%" id="decimalplaces">
                <option value="8">0.00000001</option>
                <option value="7">0.0000001</option>
                <option value="6">0.000001</option>
                <option value="5">0.00001</option>
                <option value="4">0.0001</option>
                <option value="3">0.001</option>
            </select>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table buysellorders">
            <thead>
                <tr class="d-flex w-100 justify-content-between">
                    <th scope="col">Price ({{ $tradingPair->quoteCurrency->symbol ?? 'USDT' }})</th>
                    <th scope="col">Amount ({{ $tradingPair->baseCurrency->symbol ?? 'BTC' }})</th>
                    <th scope="col">Total ({{ $tradingPair->quoteCurrency->symbol ?? 'USDT' }})</th>
                </tr>
            </thead>
            
            {{-- SELL ORDERS --}}
            <tbody class="selling sellMobtbody" id="sellorderlist">
                @forelse($sellOrders as $order)
                <tr class="sell-row local-order" data-price="{{ $order->price }}" data-amount="{{ $order->quantity }}">
                    <td class="text-danger price">{{ number_format($order->price, 8) }}</td>
                    <td class="amount">{{ number_format($order->quantity, 6) }}</td>
                    <td class="total">{{ number_format($order->price * $order->quantity, 6) }}</td>
                </tr>
                @empty
                {{-- Generate fake sell orders with larger quantities --}}
                @php
                    $basePrice = floatval(optional($recentTrades->first())->price ?? 1000);
                    $sellOrdersCount = 10;
                @endphp
                @for($i = 0; $i < $sellOrdersCount; $i++)
                    @php
                        $price = $basePrice * (1 + (($i + 1) * 0.001) + (rand(1, 50) / 10000));
                        $quantity = floatval(rand(5000000, 500000000) / 1000000); // 5 to 500 units
                        $total = $price * $quantity;
                    @endphp
                    <tr class="sell-row local-order" data-price="{{ $price }}" data-amount="{{ $quantity }}">
                        <td class="text-danger price">{{ number_format($price, 8) }}</td>
                        <td class="amount">{{ number_format($quantity, 6) }}</td>
                        <td class="total">{{ number_format($total, 6) }}</td>
                    </tr>
                @endfor
                @endforelse
            </tbody>
            
            {{-- MIDDLE PRICE ROW --}}
            <tbody class="ob-heading">
                <tr>
                    <td>
                        <span id="currentPrice">{{ number_format(optional($recentTrades->first())->price ?? 1000, 8) }}</span>
                    </td>
                    <td>{{ $tradingPair->baseCurrency->symbol ?? 'BTC' }}/{{ $tradingPair->quoteCurrency->symbol ?? 'USDT' }}</td>
                    <td id="currentPriceRight">{{ number_format(optional($recentTrades->first())->price ?? 1000, 8) }}</td>
                </tr>
            </tbody>
            
            {{-- BUY ORDERS --}}
            <tbody class="buying buyMobtbody" id="buyorderlist">
                @forelse($buyOrders as $order)
                <tr class="buy-row local-order" data-price="{{ $order->price }}" data-amount="{{ $order->quantity }}">
                    <td class="text-success price">{{ number_format($order->price, 8) }}</td>
                    <td class="amount">{{ number_format($order->quantity, 6) }}</td>
                    <td class="total">{{ number_format($order->price * $order->quantity, 6) }}</td>
                </tr>
                @empty
                {{-- Generate fake buy orders with larger quantities --}}
                @php
                    $basePrice = floatval(optional($recentTrades->first())->price ?? 1000);
                    $buyOrdersCount = 10;
                @endphp
                @for($i = 0; $i < $buyOrdersCount; $i++)
                    @php
                        $price = $basePrice * (1 - (($i + 1) * 0.001) - (rand(1, 50) / 10000));
                        $quantity = floatval(rand(5000000, 500000000) / 1000000); // 5 to 500 units
                        $total = $price * $quantity;
                    @endphp
                    <tr class="buy-row local-order" data-price="{{ $price }}" data-amount="{{ $quantity }}">
                        <td class="text-success price">{{ number_format($price, 8) }}</td>
                        <td class="amount">{{ number_format($quantity, 6) }}</td>
                        <td class="total">{{ number_format($total, 6) }}</td>
                    </tr>
                @endfor
                @endforelse
            </tbody>
        </table>
    </div>
</div>