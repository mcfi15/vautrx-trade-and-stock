<p>Hello {{ $claim->user->name }},</p>
<p>We're sorry — your claim for "<strong>{{ $claim->airdrop->title }}</strong>" was rejected.</p>
@if($claim->admin_reason)
<p>Reason: {{ $claim->admin_reason }}</p>
@endif
<p>If you think this was a mistake contact support.</p>
