<div wire:poll.5s="fetchLatestPrices">
    <div class="card">
        <div class="card-header border-0">
            <h3 class="card-title">Live Market Feed</h3>
            <div class="card-tools">
                <span class="badge badge-success animate-pulse">● LIVE</span>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>Symbol</th>
                        <th>Name</th>
                        <th class="text-right">Price</th>
                        <th class="text-right">Change</th>
                        <th>Last Sync</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stocks as $stock)
                    <tr>
                        <td><strong>{{ $stock->symbol }}</strong></td>
                        <td>{{ $stock->name }}</td>
                        <td class="text-right font-weight-bold">${{ number_format($stock->current_price, 2) }}</td>
                        <td class="text-right {{ $stock->change_percentage >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($stock->change_percentage, 2) }}%
                        </td>
                        <td><small class="text-muted">{{ $stock->last_updated->format('H:i:s') }}</small></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>