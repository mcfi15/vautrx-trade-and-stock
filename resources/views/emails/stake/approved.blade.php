<p>Hello {{ $stake->user->name }},</p>

<p>Your stake has been approved.</p>

<p>
Amount: {{ $stake->amount }} {{ $stake->cryptocurrency->symbol }}<br>
Duration: {{ $stake->duration }} days
</p>
