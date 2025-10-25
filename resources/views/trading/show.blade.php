@extends('layouts.app')

@section('title', 'Trade ' . $tradingPair->symbol)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="tradingPage()">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                @if($tradingPair->baseCurrency->logo_url)
                <img src="{{ $tradingPair->baseCurrency->logo_url }}" class="h-12 w-12 rounded-full mr-4" alt="{{ $tradingPair->baseCurrency->symbol }}">
                @endif
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $tradingPair->symbol }}</h1>
                    <p class="text-gray-500">{{ $tradingPair->baseCurrency->name }} / {{ $tradingPair->quoteCurrency->name }}</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold text-gray-900">${{ number_format($tradingPair->getCurrentPrice(), 2) }}</div>
                <div class="text-sm {{ $tradingPair->baseCurrency->price_change_24h >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ number_format($tradingPair->baseCurrency->price_change_24h, 2) }}% (24h)
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Order Book & Recent Trades -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Order Book -->
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="text-lg font-bold mb-4">Order Book</h3>
                
                <!-- Sell Orders -->
                <div class="mb-4">
                    <div class="text-xs text-gray-500 mb-2 flex justify-between">
                        <span>Price ({{ $tradingPair->quoteCurrency->symbol }})</span>
                        <span>Amount ({{ $tradingPair->baseCurrency->symbol }})</span>
                    </div>
                    <div class="space-y-1 max-h-64 overflow-y-auto">
                        @foreach($sellOrders->take(10) as $order)
                        <div class="flex justify-between text-sm text-red-600 hover:bg-red-50 p-1 rounded cursor-pointer" @click="fillPrice({{ $order->price }})">
                            <span>{{ number_format($order->price, 2) }}</span>
                            <span>{{ rtrim(rtrim(number_format($order->remaining_quantity, 8), '0'), '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Current Price -->
                <div class="text-center py-2 bg-gray-100 rounded font-bold">
                    ${{ number_format($tradingPair->getCurrentPrice(), 2) }}
                </div>

                <!-- Buy Orders -->
                <div class="mt-4">
                    <div class="space-y-1 max-h-64 overflow-y-auto">
                        @foreach($buyOrders->take(10) as $order)
                        <div class="flex justify-between text-sm text-green-600 hover:bg-green-50 p-1 rounded cursor-pointer" @click="fillPrice({{ $order->price }})">
                            <span>{{ number_format($order->price, 2) }}</span>
                            <span>{{ rtrim(rtrim(number_format($order->remaining_quantity, 8), '0'), '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Recent Trades -->
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="text-lg font-bold mb-4">Recent Trades</h3>
                <div class="space-y-2 max-h-64 overflow-y-auto">
                    @foreach($recentTrades->take(20) as $trade)
                    <div class="flex justify-between text-sm">
                        <span class="{{ $trade->buyer_id ? 'text-green-600' : 'text-red-600' }}">
                            ${{ number_format($trade->price, 2) }}
                        </span>
                        <span class="text-gray-600">{{ rtrim(rtrim(number_format($trade->quantity, 8), '0'), '.') }}</span>
                        <span class="text-gray-400 text-xs">{{ $trade->created_at->format('H:i:s') }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Center Column: Chart (Placeholder) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="text-lg font-bold mb-4">Price Chart</h3>
                <div class="h-96 flex items-center justify-center bg-gray-50 rounded">
                    <canvas id="priceChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Right Column: Trading Forms -->
        <div class="lg:col-span-1">
            <!-- Wallet Balances -->
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <h3 class="text-lg font-bold mb-4">Balances</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ $tradingPair->baseCurrency->symbol }}</span>
                        <span class="font-semibold">{{ rtrim(rtrim(number_format($baseWallet->availableBalance, 8), '0'), '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ $tradingPair->quoteCurrency->symbol }}</span>
                        <span class="font-semibold">{{ rtrim(rtrim(number_format($quoteWallet->availableBalance, 8), '0'), '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Trading Forms -->
            <div class="bg-white rounded-lg shadow">
                <div class="border-b">
                    <div class="flex">
                        <button @click="orderSide = 'buy'" :class="orderSide === 'buy' ? 'bg-green-50 text-green-600 border-b-2 border-green-600' : 'text-gray-600'" class="flex-1 py-3 font-medium">
                            Buy {{ $tradingPair->baseCurrency->symbol }}
                        </button>
                        <button @click="orderSide = 'sell'" :class="orderSide === 'sell' ? 'bg-red-50 text-red-600 border-b-2 border-red-600' : 'text-gray-600'" class="flex-1 py-3 font-medium">
                            Sell {{ $tradingPair->baseCurrency->symbol }}
                        </button>
                    </div>
                </div>

                <div class="p-4">
                    <!-- Order Type Tabs -->
                    <div class="flex space-x-2 mb-4">
                        <button @click="orderType = 'market'" :class="orderType === 'market' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700'" class="flex-1 py-2 px-4 rounded text-sm font-medium">
                            Market
                        </button>
                        <button @click="orderType = 'limit'" :class="orderType === 'limit' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700'" class="flex-1 py-2 px-4 rounded text-sm font-medium">
                            Limit
                        </button>
                        <button @click="orderType = 'stop_loss'" :class="orderType === 'stop_loss' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700'" class="flex-1 py-2 px-4 rounded text-sm font-medium">
                            Stop Loss
                        </button>
                    </div>

                    <form @submit.prevent="placeOrder" class="space-y-4">
                        <!-- Price (for limit orders) -->
                        <div x-show="orderType !== 'market'">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                            <div class="relative">
                                <input type="number" step="0.01" x-model="price" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="0.00">
                                <span class="absolute right-3 top-2 text-gray-500">{{ $tradingPair->quoteCurrency->symbol }}</span>
                            </div>
                        </div>

                        <!-- Stop Price (for stop loss orders) -->
                        <div x-show="orderType === 'stop_loss'">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stop Price</label>
                            <div class="relative">
                                <input type="number" step="0.01" x-model="stopPrice" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="0.00">
                                <span class="absolute right-3 top-2 text-gray-500">{{ $tradingPair->quoteCurrency->symbol }}</span>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                            <div class="relative">
                                <input type="number" step="0.00000001" x-model="quantity" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="0.00">
                                <span class="absolute right-3 top-2 text-gray-500">{{ $tradingPair->baseCurrency->symbol }}</span>
                            </div>
                        </div>

                        <!-- Total -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total</label>
                            <div class="relative">
                                <input type="text" :value="calculateTotal()" readonly class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md" placeholder="0.00">
                                <span class="absolute right-3 top-2 text-gray-500">{{ $tradingPair->quoteCurrency->symbol }}</span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" :class="orderSide === 'buy' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700'" class="w-full py-3 text-white font-bold rounded-md transition">
                            <span x-show="orderSide === 'buy'">Buy {{ $tradingPair->baseCurrency->symbol }}</span>
                            <span x-show="orderSide === 'sell'">Sell {{ $tradingPair->baseCurrency->symbol }}</span>
                        </button>

                        <div class="text-xs text-gray-500">
                            Trading Fee: {{ number_format($tradingPair->trading_fee * 100, 2) }}%
                        </div>
                    </form>
                </div>
            </div>

            <!-- Active Orders -->
            @if($userOrders->count() > 0)
            <div class="bg-white rounded-lg shadow p-4 mt-6">
                <h3 class="text-lg font-bold mb-4">Your Active Orders</h3>
                <div class="space-y-2">
                    @foreach($userOrders as $order)
                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                        <div>
                            <span class="text-sm font-medium {{ $order->side == 'buy' ? 'text-green-600' : 'text-red-600' }}">
                                {{ strtoupper($order->side) }}
                            </span>
                            <span class="text-sm text-gray-600 ml-2">
                                {{ rtrim(rtrim(number_format($order->remaining_quantity, 8), '0'), '.') }} @ ${{ number_format($order->price, 2) }}
                            </span>
                        </div>
                        <button @click="cancelOrder({{ $order->id }})" class="text-red-600 hover:text-red-800 text-sm">
                            Cancel
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function tradingPage() {
    return {
        orderSide: 'buy',
        orderType: 'market',
        price: {{ $tradingPair->getCurrentPrice() }},
        stopPrice: 0,
        quantity: 0,
        
        fillPrice(priceValue) {
            this.price = priceValue;
            if (this.orderType === 'market') {
                this.orderType = 'limit';
            }
        },
        
        calculateTotal() {
            const priceToUse = this.orderType === 'market' ? {{ $tradingPair->getCurrentPrice() }} : parseFloat(this.price) || 0;
            const qty = parseFloat(this.quantity) || 0;
            const total = priceToUse * qty;
            return total > 0 ? total.toFixed(2) : '0.00';
        },
        
        async placeOrder() {
            try {
                const response = await fetch('{{ route("trading.order.place") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        trading_pair_id: {{ $tradingPair->id }},
                        type: this.orderType,
                        side: this.orderSide,
                        quantity: this.quantity,
                        price: this.orderType !== 'market' ? this.price : null,
                        stop_price: this.orderType === 'stop_loss' ? this.stopPrice : null,
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Order placed successfully!');
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to place order');
            }
        },
        
        async cancelOrder(orderId) {
            if (!confirm('Are you sure you want to cancel this order?')) {
                return;
            }
            
            try {
                const response = await fetch(`/trading/order/${orderId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Order cancelled successfully!');
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to cancel order');
            }
        }
    }
}

// Simple price chart
const ctx = document.getElementById('priceChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: Array.from({length: 24}, (_, i) => `${i}:00`),
        datasets: [{
            label: 'Price (USD)',
            data: Array.from({length: 24}, () => {{ $tradingPair->getCurrentPrice() }} + (Math.random() - 0.5) * 100),
            borderColor: 'rgb(79, 70, 229)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
@endpush
@endsection
