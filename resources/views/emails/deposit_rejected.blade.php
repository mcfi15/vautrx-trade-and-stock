<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Deposit Rejected</title>
</head>
<body style="font-family:Arial, sans-serif; background:#f6f7fb; padding:30px;">

<div style="max-width:600px; background:#ffffff; margin:auto; padding:25px; border-radius:8px; border:1px solid #dcdcdc;">
    
    <h2 style="color:#d9534f; text-align:center;">
        Deposit Request Rejected
    </h2>

    <p>Hello {{ $deposit->user->name }},</p>

    <p>
        Your deposit request has been <strong style="color:#d9534f;">rejected</strong>.
    </p>

    <table style="width:100%; margin-top:15px; border-collapse:collapse;">
        <tr>
            <td><strong>Deposit ID:</strong></td>
            <td>#{{ $deposit->id }}</td>
        </tr>
        <tr>
            <td><strong>Amount:</strong></td>
            <td>{{ $deposit->amount }} {{ $deposit->cryptocurrency->symbol }}</td>
        </tr>
        <tr>
            <td><strong>Status:</strong></td>
            <td style="color:#d9534f; font-weight:bold;">Rejected</td>
        </tr>
        <tr>
            <td><strong>Reason:</strong></td>
            <td>{{ $adminNotes }}</td>
        </tr>
        <tr>
            <td><strong>Date:</strong></td>
            <td>{{ now()->format('Y-m-d H:i:s') }}</td>
        </tr>
    </table>

    <br>

    <p>If you believe this was a mistake, please contact support.</p>

    <p>Thank you,<br>
    <strong>{{ config('app.name') }}</strong></p>
</div>
</body>
</html>
