@extends('emails.layout.app')

@section('title', 'Stock Purchase Confirmed')
@section('header-title', 'Stock Purchase Confirmed')

@section('content')
<p>Hello {{ $transaction->user->name }},</p>

<p>Your stock purchase order has been successfully executed.</p>

<div class="alert alert-success">
    <strong>📈 Trade Executed Successfully!</strong><br>
    You have successfully purchased {{ number_format($transaction->quantity) }} shares of {{ $transaction->stock->symbol }}.
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
        <td><strong style="color: #28a745;">BUY</strong></td>
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
        <th>Transaction ID</th>
        <td>#{{ $transaction->id }}</td>
    </tr>
</table>

<div class="alert alert-info">
    <h4>📁 Portfolio Update</h4>
    These shares have been added to your portfolio. You can view your complete holdings in your dashboard.
</div>

<a href="{{ route('portfolio.index') }}" class="btn">
    View Portfolio
</a>

<a href="{{ route('stocks.show', $transaction->stock) }}" class="btn" style="margin-left: 10px; background-color: #17a2b8;">
    View {{ $transaction->stock->symbol }} Details
</a>

<p class="text-muted">
    <strong>Remaining Balance:</strong> ${{ number_format($transaction->user->balance, 2) }}
</p>
@endsection
