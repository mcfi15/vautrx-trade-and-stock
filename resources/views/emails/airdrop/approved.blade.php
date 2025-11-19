<p>Hello {{ $claim->user->name }},</p>
<p>Your claim for the airdrop "<strong>{{ $claim->airdrop->title }}</strong>" has been approved.</p>
<p>Amount credited: {{ $claim->claim_amount }} {{ $claim->airdrop->airdropCurrency->symbol }}</p>
<p>Thank you.</p>
