<tr>
            <td><strong>{{ $stock->symbol }}</strong></td>
            <td>{{ $stock->name }}</td>
            <td>${{ number_format($stock->current_price, 2) }}</td>

            <td class="{{ $stock->change >= 0 ? 'text-success' : 'text-danger' }}">
              {{ $stock->change >= 0 ? '+' : '' }}{{ number_format($stock->change, 2) }}
            </td>

            <td class="{{ $stock->change_percentage >= 0 ? 'text-success' : 'text-danger' }}">
              {{ $stock->change_percentage >= 0 ? '+' : '' }}{{ number_format($stock->change_percentage, 2) }}%
            </td>

            <td>{{ number_format($stock->volume) }}</td>

            <td>
              @if($stock->market_cap >= 1000000000)
                ${{ number_format($stock->market_cap / 1000000000, 1) }}B
              @elseif($stock->market_cap >= 1000000)
                ${{ number_format($stock->market_cap / 1000000, 1) }}M
              @else
                ${{ number_format($stock->market_cap / 1000, 1) }}K
              @endif
            </td>

            <td>
              <span class="badge badge-secondary">{{ $stock->sector ?? 'N/A' }}</span>
            </td>

            @auth
            <td>
              <a href="{{ route('stocks.show', $stock) }}" class="btn btn-sm btn-outline-primary mr-1">
                <i class="fa fa-eye"></i>
              </a>
              <a href="{{ route('trading.index', ['stock' => $stock->id]) }}" class="btn btn-sm btn-outline-success">
                <i class="fa fa-exchange"></i>
              </a>
            </td>
            @endauth
          </tr>