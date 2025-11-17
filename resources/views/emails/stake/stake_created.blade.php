<h2>Stake Successful</h2>

<p>Hello {{ $stake->user->name }},</p>

<p>Your staking was successful:</p>

<ul>
    <li><strong>Plan:</strong> {{ $stake->plan->name }}</li>
    <li><strong>Amount:</strong> {{ $stake->amount }} {{ $stake->cryptocurrency->symbol }}</li>
    <li><strong>Duration:</strong> {{ $stake->duration }} days</li>
    <li><strong>Ends At:</strong> {{ $stake->ends_at }}</li>
</ul>

<p>Thank you for using our platform!</p>
