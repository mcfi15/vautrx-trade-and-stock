@extends('emails.layout.app')

@section('title', 'Large Trade Alert')
@section('header-title', 'Large Trade Alert')

@section('content')
<p>Hello Admin,</p>

<p>A significant trading activity has occurred on the platform that may require your attention.</p>

<div class="alert alert-warning">
    <strong>🚨 Large Trade Alert</strong><br>
    This trade exceeds the normal trading threshold and has been flagged for your review.
</div>

<table class="detail-table">
    <tr>
        <th>User</th>
        <td>
            <strong>{{ $transaction->user->name }}</strong><br>
            <span class="text-muted">{{ $transaction->user->email }}</span>
        </td>
    </tr>
    <tr>
        <th>Transaction ID</th>
        <td>#{{ $transaction->id }}</td>
    </tr>
    <tr>
        <th>Stock</th>
        <td>
            <strong>{{ $transaction->stock->symbol }}</strong><br>
            <span class="text-muted">{{ $transaction->stock->name }}</span>
        </td>
    </tr>
    <tr>
        <th>Transaction Type</th>
        <td>
            <strong style="color: {{ $transaction->type === 'buy' ? '#28a745' : '#dc3545' }};">
                {{ strtoupper($transaction->type) }}
            </strong>
        </td>
    </tr>
    <tr>
        <th>Quantity</th>
        <td class="amount">{{ number_format($transaction->quantity) }} shares</td>
    </tr>
    <tr>
        <th>Price per Share</th>
        <td class="amount">${{ number_format($transaction->price, 2) }}</td>
    </tr>
    <tr>
        <th>Total Amount</th>
        <td class="amount">${{ number_format($transaction->total_amount, 2) }}</td>
    </tr>
    <tr>
        <th>Transaction Date</th>
        <td>{{ $transaction->created_at->format('M d, Y H:i:s') }}</td>
    </tr>
    <tr>
        <th>User Balance (After)</th>
        <td class="amount">${{ number_format($transaction->user->balance, 2) }}</td>
    </tr>
</table>

<div class="alert alert-info">
    <h4>📋 User Trading Summary</h4>
    <strong>Total Transactions (Last 30 days):</strong> {{ $transaction->user->transactions()->where('created_at', '>=', now()->subDays(30))->count() }}<br>
    <strong>Total Trading Volume (Last 30 days):</strong> ${{ number_format($transaction->user->transactions()->where('created_at', '>=', now()->subDays(30))->sum('total_amount'), 2) }}<br>
    <strong>Portfolio Value:</strong> ${{ number_format($transaction->user->portfolios()->sum('current_value'), 2) }}<br>
    <strong>Account Status:</strong> {{ $transaction->user->is_active ? 'Active' : 'Inactive' }}<br>
    <strong>KYC Verified:</strong> {{ $transaction->user->kyc_verified ? 'Yes' : 'No' }}
</div>

<div class="alert alert-warning">
    <h4>📈 Stock Information</h4>
    <strong>Current Price:</strong> ${{ number_format($transaction->stock->current_price, 2) }}<br>
    <strong>Day Change:</strong> 
    <span class="{{ $transaction->stock->change_percentage >= 0 ? 'profit' : 'loss' }}">
        {{ $transaction->stock->change_percentage >= 0 ? '+' : '' }}{{ number_format($transaction->stock->change_percentage, 2) }}%
    </span><br>
    <strong>Market Cap:</strong> ${{ number_format($transaction->stock->market_cap ?? 0, 2) }}<br>
    <strong>Volume:</strong> {{ number_format($transaction->stock->volume ?? 0) }}
</div>

<a href="{{ route('admin.transactions.show', $transaction) }}" class="btn">
    View Transaction Details
</a>

<a href="{{ route('admin.users.show', $transaction->user) }}" class="btn" style="margin-left: 10px; background-color: #17a2b8;">
    View User Profile
</a>

<a href="{{ route('admin.stocks.show', $transaction->stock) }}" class="btn" style="margin-left: 10px; background-color: #28a745;">
    View Stock Details
</a>

<p class="text-muted">
    <strong>Note:</strong> Large trades are automatically flagged for review to ensure platform security and compliance.
</p>
@endsection
