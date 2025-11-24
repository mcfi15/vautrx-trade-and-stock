<!doctype html>
<html>
<head><meta charset="utf-8"></head>
<body>
    <p>Hi {{ $withdrawal->user->name ?? 'user' }},</p>

    <p>Your withdrawal request has been submitted:</p>
    <ul>
        <li>Coin: {{ $withdrawal->cryptocurrency->symbol }}</li>
        <li>Amount: {{ $withdrawal->amount }}</li>
        <li>Fee: {{ $withdrawal->fee }}</li>
        <li>Net: {{ $withdrawal->net_amount }}</li>
        <li>Address: {{ $withdrawal->withdrawal_address }}</li>
        <li>Status: {{ $withdrawal->status }}</li>
    </ul>

    <p>We will process this request shortly. If this wasn't you, contact support immediately.</p>

    <p>Regards,<br/>{{ config('app.name') }}</p>
</body>
</html>
