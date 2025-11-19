<!DOCTYPE html>
<html>
<head>
    <title>Faucet Claimed</title>
</head>
<body>
    <h2>Hello {{ $user->name }},</h2>
    <p>You have successfully claimed the faucet: <strong>{{ $faucet->title }}</strong>.</p>
    <p>Amount credited: <strong>{{ $amount }} {{ $faucet->coin->symbol ?? '' }}</strong></p>
    <p>Claimed at: {{ now()->format('Y-m-d H:i:s') }}</p>
    <p>Thank you for using our platform!</p>
</body>
</html>
