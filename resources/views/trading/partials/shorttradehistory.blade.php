<div class="market-history">
        <ul class="nav nav-pills" role="tablist">
          <li class="nav-item">
            <a aria-selected="true" class="nav-link active" data-toggle="pill" href="#latest-trades" role="tab">Recent
              Trades</a>
          </li>
        </ul>
        <div class="tab-content" id="DealRecordTable">
          <div class="tab-pane fade show active" id="dealrecords" role="tabpanel">
            <table class="table">
              <thead>
                <tr class="row">
                  <th scope="col" class="col-4">
                    <span class="time">Time</span>
                  </th>
                  <th scope="col" class="col-4">
                    <span class="price">Price</span>
                  </th>
                  <th scope="col" class="col-4">
                    <span class="quantity">Volume</span>
                  </th>
                </tr>
              </thead>
              <tbody id="recent-orders">
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
                            
                            <td class="{{ $textColorClass }}">{{ number_format($fakePrice, 8) }}</td>
                            <td>{{ number_format($fakeQuantity, 8) }}</td>
                            
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
                                
                                <td class="{{ $textColorClass }}">{{ number_format($fakePrice, 8) }}</td>
                                <td>{{ number_format($fakeQuantity, 8) }}</td>
                                
                            </tr>
                        @endfor
                    @endforelse
                </tbody>
            </table>
          </div>
        </div>
      </div>