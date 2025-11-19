<!DOCTYPE html>
<html>
<head>
    <title>Airdrop Claim Submitted</title>
</head>
<body>
    <h2>Hello {{ $user->name }},</h2>

    <p>You have successfully submitted a claim for the airdrop: <strong>{{ $airdrop->title }}</strong>.</p>

    <p>Claim Amount: <strong>{{ $claimAmount }} {{ $airdrop->airdropCurrency->symbol ?? '' }}</strong></p>

    <p>Status: <strong>Pending admin approval</strong></p>

    <p>Submitted at: {{ now()->format('Y-m-d H:i:s') }}</p>

    <p>Thank you for participating!</p>
</body>
</html>
