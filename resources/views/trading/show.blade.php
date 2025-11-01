@extends('layouts.app')

@section('title', 'Trade ' . $tradingPair->symbol)

@section('content')
<div class="container-fluid" x-data="tradingPage()">
    <!-- Header -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                @if($tradingPair->baseCurrency->logo_url)
                <img src="{{ $tradingPair->baseCurrency->logo_url }}" class="rounded-circle mr-3" style="height: 48px; width: 48px;" alt="{{ $tradingPair->baseCurrency->symbol }}">
                @endif
                <div>
                    <h1 class="h3 font-weight-bold mb-0">{{ $tradingPair->symbol }}</h1>
                    <small class="text-muted">{{ $tradingPair->baseCurrency->name }} / {{ $tradingPair->quoteCurrency->name }}</small>
                </div>
            </div>
            <div class="text-right">
                <div class="h3 font-weight-bold mb-1">${{ number_format($tradingPair->getCurrentPrice(), 2) }}</div>
                <div class="{{ $tradingPair->baseCurrency->price_change_24h >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($tradingPair->baseCurrency->price_change_24h, 2) }}% (24h)
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Order Book & Recent Trades -->
        <div class="col-lg-4 mb-4">
            <!-- Order Book -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Order Book</h5>

                    <!-- Sell Orders -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between text-muted small mb-1">
                            <span>Price ({{ $tradingPair->quoteCurrency->symbol }})</span>
                            <span>Amount ({{ $tradingPair->baseCurrency->symbol }})</span>
                        </div>
                        <div class="overflow-auto" style="max-height: 250px;">
                            @foreach($sellOrders->take(10) as $order)
                            <div class="d-flex justify-content-between small text-danger p-1 rounded hover" style="cursor:pointer;" @click="fillPrice({{ $order->price }})">
                                <span>{{ number_format($order->price, 2) }}</span>
                                <span>{{ rtrim(rtrim(number_format($order->remaining_quantity, 8), '0'), '.') }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Current Price -->
                    <div class="text-center py-2 bg-light font-weight-bold">
                        ${{ number_format($tradingPair->getCurrentPrice(), 2) }}
                    </div>

                    <!-- Buy Orders -->
                    <div class="mt-3">
                        <div class="overflow-auto" style="max-height: 250px;">
                            @foreach($buyOrders->take(10) as $order)
                            <div class="d-flex justify-content-between small text-success p-1 rounded hover" style="cursor:pointer;" @click="fillPrice({{ $order->price }})">
                                <span>{{ number_format($order->price, 2) }}</span>
                                <span>{{ rtrim(rtrim(number_format($order->remaining_quantity, 8), '0'), '.') }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Trades -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Recent Trades</h5>
                    <div class="overflow-auto" style="max-height: 250px;">
                        @foreach($recentTrades->take(20) as $trade)
                        <div class="d-flex justify-content-between small">
                            <span class="{{ $trade->buyer_id ? 'text-success' : 'text-danger' }}">
                                ${{ number_format($trade->price, 2) }}
                            </span>
                            <span class="text-muted">{{ rtrim(rtrim(number_format($trade->quantity, 8), '0'), '.') }}</span>
                            <span class="text-muted small">{{ $trade->created_at->format('H:i:s') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Center Column: Chart -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Price Chart</h5>
                    <div class="d-flex justify-content-center align-items-center bg-light" style="height: 400px; border-radius: 5px;">
                        <canvas id="priceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Trading Forms -->
        <div class="col-lg-4 mb-4">
            <!-- Wallet Balances -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Balances</h5>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">{{ $tradingPair->baseCurrency->symbol }}</span>
                        <span class="font-weight-bold">
                            {{ $baseWallet->availableBalance ?? '0.00000000' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">{{ $tradingPair->quoteCurrency->symbol }}</span>
                        <span class="font-weight-bold">
                            {{ $quoteWallet->availableBalance ?? '0.00000000' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Trading Forms -->
            <div class="card mb-3">
                <div class="card-header p-0">
                    <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                        <label class="btn btn-light flex-fill" :class="orderSide === 'buy' ? 'active btn-success' : ''" @click="orderSide = 'buy'">
                            Buy {{ $tradingPair->baseCurrency->symbol }}
                        </label>
                        <label class="btn btn-light flex-fill" :class="orderSide === 'sell' ? 'active btn-danger' : ''" @click="orderSide = 'sell'">
                            Sell {{ $tradingPair->baseCurrency->symbol }}
                        </label>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Order Type Tabs -->
                    <div class="btn-group mb-3 d-flex" role="group">
                        <button type="button" class="btn btn-light flex-fill" :class="orderType === 'market' ? 'btn-primary text-white' : ''" @click="orderType = 'market'">Market</button>
                        <button type="button" class="btn btn-light flex-fill" :class="orderType === 'limit' ? 'btn-primary text-white' : ''" @click="orderType = 'limit'">Limit</button>
                        <button type="button" class="btn btn-light flex-fill" :class="orderType === 'stop_loss' ? 'btn-primary text-white' : ''" @click="orderType = 'stop_loss'">Stop Loss</button>
                    </div>

                    <form @submit.prevent="placeOrder">
                        <!-- Price -->
                        <div class="form-group" x-show="orderType !== 'market'">
                            <label>Price</label>
                            <div class="input-group">
                                <input type="number" step="0.01" x-model="price" class="form-control" placeholder="0.00">
                                <div class="input-group-append">
                                    <span class="input-group-text">{{ $tradingPair->quoteCurrency->symbol }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Stop Price -->
                        <div class="form-group" x-show="orderType === 'stop_loss'">
                            <label>Stop Price</label>
                            <div class="input-group">
                                <input type="number" step="0.01" x-model="stopPrice" class="form-control" placeholder="0.00">
                                <div class="input-group-append">
                                    <span class="input-group-text">{{ $tradingPair->quoteCurrency->symbol }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div class="form-group">
                            <label>Amount</label>
                            <div class="input-group">
                                <input type="number" step="0.00000001" x-model="quantity" required class="form-control" placeholder="0.00">
                                <div class="input-group-append">
                                    <span class="input-group-text">{{ $tradingPair->baseCurrency->symbol }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="form-group">
                            <label>Total</label>
                            <div class="input-group">
                                <input type="text" :value="calculateTotal()" readonly class="form-control bg-light" placeholder="0.00">
                                <div class="input-group-append">
                                    <span class="input-group-text">{{ $tradingPair->quoteCurrency->symbol }}</span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-block" :class="orderSide === 'buy' ? 'btn-success' : 'btn-danger'">
                            <span x-show="orderSide === 'buy'">Buy {{ $tradingPair->baseCurrency->symbol }}</span>
                            <span x-show="orderSide === 'sell'">Sell {{ $tradingPair->baseCurrency->symbol }}</span>
                        </button>
                        <small class="text-muted d-block mt-2">Trading Fee: {{ number_format($tradingPair->trading_fee * 100, 2) }}%</small>
                    </form>
                </div>
            </div>

            <!-- Active Orders -->
            @if($userOrders->count() > 0)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Your Active Orders</h5>
                    @foreach($userOrders as $order)
                    <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded mb-2">
                        <div>
                            <span class="small font-weight-bold {{ $order->side == 'buy' ? 'text-success' : 'text-danger' }}">
                                {{ strtoupper($order->side) }}
                            </span>
                            <span class="small text-muted ml-2">
                                {{ rtrim(rtrim(number_format($order->remaining_quantity, 8), '0'), '.') }} @ ${{ number_format($order->price, 2) }}
                            </span>
                        </div>
                        <button @click="cancelOrder({{ $order->id }})" class="btn btn-link text-danger p-0 small">Cancel</button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
