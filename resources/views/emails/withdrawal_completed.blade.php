<p>Hello {{ $withdrawal->user->name }},</p>

<p>Your withdrawal request has been <strong>successfully completed</strong>.</p>

<p>
    <strong>Amount:</strong> {{ $withdrawal->amount }} {{ $withdrawal->cryptocurrency->symbol }} <br>
    <strong>Transaction Hash:</strong> {{ $withdrawal->tx_hash }} <br>
    <strong>Date:</strong> {{ $withdrawal->processed_at }}
</p>

@if($adminNotes)
<p><strong>Admin Notes:</strong><br>{{ $adminNotes }}</p>
@endif

<p>Thank you for using our platform!</p>

<p>Best Regards,<br>Support Team</p>
