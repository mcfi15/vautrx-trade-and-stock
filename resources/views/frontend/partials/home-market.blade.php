<tr>
    <td>
        <div class="d-flex align-items-center">
            <img src="{{ $pair->baseCurrency->logo_url }}" style="width:30px;height:30px;border-radius:50%;margin-right:10px;">
            <div>
                <strong>{{ $pair->baseCurrency->symbol }}/{{ $pair->quoteCurrency->symbol }}</strong>
                <div class="text-muted small">{{ $pair->baseCurrency->name }}</div>
            </div>
        </div>
    </td>

    <td>{{ number_format($pair->getCurrentPrice(), 6) }}</td>
    <td>{{ number_format($pair->getCurrentPrice(), 6) }}</td>
    <td>{{ number_format($pair->getCurrentPrice(), 6) }}</td>

    <td>{{ number_format($pair->baseCurrency->volume_24h, 2) }}</td>
    <td>{{ number_format($pair->baseCurrency->market_cap, 2) }}</td>

    <td>
        <span style="color:{{ $pair->baseCurrency->price_change_24h >= 0 ? 'green' : 'red' }}">
            {{ number_format($pair->baseCurrency->price_change_24h, 2) }}%
        </span>
    </td>

    <td>
        <a href="{{ url('trade/'.$pair->id) }}" class="btn-2">Trade</a>
    </td>
</tr>