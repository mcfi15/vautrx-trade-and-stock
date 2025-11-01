<p>Hello {{ $withdrawal->user->name }},</p>

<p>Your withdrawal request has been <strong>rejected</strong>.</p>

<p>
    <strong>Amount:</strong> {{ $withdrawal->amount }} {{ $withdrawal->cryptocurrency->symbol }}<br>
    <strong>Date:</strong> {{ $withdrawal->created_at }}
</p>

<p><strong>Reason:</strong></p>
<p>{{ $adminNotes }}</p>

<p>Your funds have been returned to your wallet (if applicable).</p>

<p>Please contact support if you need help.</p>

<p>Best Regards,<br>Support Team</p>
