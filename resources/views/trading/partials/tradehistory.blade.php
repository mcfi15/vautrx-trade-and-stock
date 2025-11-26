<div class="col-md-12">
      <div class="recent-history market-order card mt-3">
        <div class="header d-flex justify-content-between align-items-center white-bg">
          <ul class="nav nav-pills" role="tablist">
            <li class="nav-item">
              <a aria-selected="true" class="nav-link active" data-toggle="pill" href="#newrecent-trades"
                role="tab">Recent Trades</a>
            </li>
            <li class="nav-item">
              <a aria-selected="false" class="nav-link" data-toggle="pill" href="#closed-orders" role="tab">My
                Orders</a>
            </li>
            <li class="nav-item">
              <a aria-selected="false" class="nav-link" data-toggle="pill" href="#opened-orders" role="tab">Active
                Order</a>
            </li>

          </ul>
          <ul class="list-inline m-r-15 f-s-12 filterMenu">

            <li class="list-inline-item active all-orders"><a href="">All</a></li>
          </ul>
        </div>
        <div class="tab-content">
          <div class="tab-pane fade show active" id="newrecent-trades" role="tabpanel">
            <div class="d-flex justify-content-between market-order-item table-responsive white-bg hello-xqf">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Time</th>
                    <th scope="col">Type</th>
                    <th scope="col">Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Total</th>
                  </tr>
                </thead>
                <tbody id="recent-orders-list">
                    @forelse($recentTrades as $t)
                        @php
                            $side = optional($t->order)->side ?? (rand(0, 1) ? 'buy' : 'sell');
                            // Generate consistent fake data
                            $fakeTime = now()->subSeconds(rand(0, 86400))->format('m-d H:i:s');
                            $fakePrice = floatval(rand(80000, 120000) / 100);
                            $fakeQuantity = floatval(rand(100000, 5000000) / 10000000);
                            $total = $fakePrice * $fakeQuantity;
                            $textColorClass = $side == 'buy' ? 'text-success' : 'text-danger';
                        @endphp
                        <tr>
                            <td>{{ $fakeTime }}</td>
                            <td class="{{ $textColorClass }}">{{ strtoupper($side) }}</td>
                            <td class="{{ $textColorClass }}">{{ number_format($fakePrice, 8) }}</td>
                            <td>{{ number_format($fakeQuantity, 8) }}</td>
                            <td>{{ number_format($total, 8) }}</td>
                        </tr>
                    @empty
                        {{-- If no real data, generate fake data --}}
                        @for ($i = 0; $i < 12; $i++)
                            @php
                                $side = rand(0, 1) ? 'buy' : 'sell';
                                $fakeTime = now()->subMinutes($i * 5)->format('m-d H:i:s');
                                $fakePrice = floatval(rand(80000, 120000) / 100);
                                $fakeQuantity = floatval(rand(100000, 5000000) / 10000000);
                                $total = $fakePrice * $fakeQuantity;
                                $textColorClass = $side == 'buy' ? 'text-success' : 'text-danger';
                            @endphp
                            <tr>
                                <td>{{ $fakeTime }}</td>
                                <td class="{{ $textColorClass }}">{{ strtoupper($side) }}</td>
                                <td class="{{ $textColorClass }}">{{ number_format($fakePrice, 8) }}</td>
                                <td>{{ number_format($fakeQuantity, 8) }}</td>
                                <td>{{ number_format($total, 8) }}</td>
                            </tr>
                        @endfor
                    @endforelse
                </tbody>
              </table>

            </div>

          </div>
          <div class="tab-pane fade" id="opened-orders" role="tabpanel">
            <div class="d-flex justify-content-between table-responsive white-bg market-order-item">
                <table class="table" id="entrust_over">
                    <thead>
                        <tr>
                            <th scope="col">Market</th>
                            <th scope="col">Time</th>
                            <th scope="col">Type</th>
                            <th scope="col">Price</th>
                            <th scope="col">Stop</th>
                            <th scope="col">Qty</th>
                            <th scope="col">Dealt</th>
                            <th scope="col">Total</th>
                            <th scope="col">Option</th>
                        </tr>
                    </thead>
                    <tbody id="entrustlist">
                        @if(Auth::check())
                            @forelse($userOrders as $order)
                            <tr>
                                <td>{{ $tradingPair->baseCurrency->symbol }}/{{ $tradingPair->quoteCurrency->symbol }}</td>
                                <td>{{ $order->created_at->format('m-d H:i:s') }}</td>
                                <td class="{{ $order->side === 'buy' ? 'text-success' : 'text-danger' }}">
                                    {{ strtoupper($order->side) }} {{ strtoupper($order->type) }}
                                </td>
                                <td>{{ $order->price ? number_format($order->price, 8) : 'Market' }}</td>
                                <td>{{ $order->stop_price ? number_format($order->stop_price, 8) : '-' }}</td>
                                <td>{{ number_format($order->quantity, 8) }}</td>
                                <td>{{ number_format($order->filled_quantity, 8) }}</td>
                                <td>{{ number_format($order->total_amount, 8) }}</td>
                                <td>
                                    @if(in_array($order->status, ['pending', 'partial']))
                                    <button class="btn btn-sm btn-outline-danger" 
                                            onclick="cancelOrder({{ $order->id }})">
                                        Cancel
                                    </button>
                                    @else
                                    <span class="badge badge-{{ $order->status === 'completed' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-3">
                                    <i style="color:#1e90ff;" class="fa fa-file"></i>
                                    No active orders found
                                </td>
                            </tr>
                            @endforelse
                        @else
                            <tr>
                                <td colspan="9" class="text-center text-muted py-3">
                                    <i style="color:#1e90ff;" class="fa fa-user
                                    "></i>
                                    <span class="login-menu">
                                        <a class="cd-signin" href="{{ route('login') }}">Login</a> or 
                                        <a class="cd-signup" href="{{ route('register') }}">Signup</a> to view your orders
                                    </span>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane fade" id="closed-orders" role="tabpanel">
            <div class="d-flex justify-content-between table-responsive white-bg market-order-item">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Time</th>
                            <th scope="col">Type</th>
                            <th scope="col">Price</th>
                            <th scope="col">Qty</th>
                            <th scope="col">Total</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody id="closedorderslist">
                        @if(Auth::check())
                            @php
                                // Get completed/cancelled orders for this trading pair
                                $closedOrders = Auth::user()->orders()
                                    ->where('trading_pair_id', $tradingPair->id)
                                    ->whereIn('status', ['completed', 'cancelled', 'failed','partial','open','pending','expired'])
                                    ->latest()
                                    // ->take(20)
                                    ->get();
                            @endphp
                            
                            @forelse($closedOrders as $order)
                            <tr>
                                <td>{{ $order->executed_at?->format('m-d H:i:s') ?? $order->updated_at->format('m-d H:i:s') }}</td>
                                <td class="{{ $order->side === 'buy' ? 'text-success' : 'text-danger' }}">
                                    {{ strtoupper($order->side) }} {{ strtoupper($order->type) }}
                                </td>
                                <td>{{ $order->price ? number_format($order->price, 8) : 'Market' }}</td>
                                <td>{{ number_format($order->quantity, 8) }}</td>
                                <td>{{ number_format($order->total_amount, 8) }}</td>
                                <td>
                                    <span class="badge badge-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">
                                    <i style="color:#1e90ff;" class="fa fa-file"></i>
                                    No order history found
                                </td>
                            </tr>
                            @endforelse
                        @else
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">
                                    <i style="color:#1e90ff;" class="fa fa-user
                                    "></i>
                                    <span class="login-menu">
                                        <a class="cd-signin" href="{{ route('login') }}">Login</a> or 
                                        <a class="cd-signup" href="{{ route('register') }}">Signup</a> to view your order history
                                    </span>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>


        </div>
      </div>
    </div>