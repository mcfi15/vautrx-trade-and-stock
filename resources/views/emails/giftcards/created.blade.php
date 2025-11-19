<x-mail::message>
# Gift Card Created Successfully

Hello {{ $giftCard->user->name }},

Your gift card has been created successfully!

**Gift Card Details:**  
- Title: {{ $giftCard->title }}  
- Amount: {{ $giftCard->amount }} {{ $giftCard->cryptocurrency->symbol }}  
- Card Number: {{ $giftCard->public_code }}  
- Secret Code: {{ $giftCard->secret_code }}  
@if($giftCard->expires_at)
- Expires: {{ $giftCard->expires_at->format('M j, Y') }}  
@endif

**Important:** Keep your secret code safe and share it only with the intended recipient.

<x-mail::button :url="route('giftcard.index')">
View My Gift Cards
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>