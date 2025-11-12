@extends('emails.layout.app')

@section('title', 'Stock Sale Confirmed')
@section('header-title', 'Stock Sale Confirmed')

@section('content')
<p>Hello {{ $transaction->user->name }},</p>

<p>Your stock sale order has been successfully executed.</p>

<div class="alert alert-success">
    <strong>💰 Trade Executed Successfully!</strong><br>
    You have successfully sold {{ number_format($transaction->quantity) }} shares of {{ $transaction->stock->symbol }}.
</div>

<table class="detail-table">
    <tr>
        <th>Stock Symbol</th>
        <td><strong>{{ $transaction->stock->symbol }}</strong></td>
    </tr>
    <tr>
        <th>Company Name</th>
        <td>{{ $transaction->stock->name }}</td>
    </tr>
    <tr>
        <th>Transaction Type</th>
        <td><strong style="color: #dc3545;">SELL</strong></td>
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
    @if($profitLoss != 0)
    <tr>
        <th>Profit/Loss</th>
        <td class="amount {{ $profitLoss >= 0 ? 'profit' : 'loss' }}">
            {{ $profitLoss >= 0 ? '+' : '' }}${{ number_format($profitLoss, 2) }}
        </td>
    </tr>
    @endif
    <tr>
        <th>Transaction Date</th>
        <td>{{ $transaction->created_at->format('M d, Y H:i:s') }}</td>
    </tr>
    <tr>
        <th>Transaction ID</th>
        <td>#{{ $transaction->id }}</td>
    </tr>
</table>

@if($profitLoss > 0)
<div class="alert alert-success">
    <strong>🎉 Congratulations!</strong><br>
    You made a profit of ${{ number_format($profitLoss, 2) }} on this trade.
</div>
@elseif($profitLoss < 0)
<div class="alert alert-warning">
    <strong>📉 Trade Summary</strong><br>
    This trade resulted in a loss of ${{ number_format(abs($profitLoss), 2) }}. Remember that trading involves risks.
</div>
@endif

<div class="alert alert-info">
    <h4>💳 Account Update</h4>
    The sale proceeds have been added to your account balance and are available for trading or withdrawal.
</div>

<a href="{{ route('portfolio.index') }}" class="btn">
    View Portfolio
</a>

<a href="{{ route('transactions.index') }}" class="btn" style="margin-left: 10px; background-color: #17a2b8;">
    View All Transactions
</a>

<p class="text-muted">
    <strong>Current Balance:</strong> ${{ number_format($transaction->user->balance, 2) }}
</p>
@endsection
