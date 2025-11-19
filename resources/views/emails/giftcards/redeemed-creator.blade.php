<x-mail::message>
# Your Gift Card Was Redeemed

Hello {{ $giftCard->user->name }},

Your gift card has been redeemed!

**Gift Card Details:**  
- Title: {{ $giftCard->title }}  
- Amount: {{ $giftCard->amount }} {{ $giftCard->cryptocurrency->symbol }}  
- Redeemed By: {{ $giftCard->redeemedBy->name }}  
- Redeemed At: {{ $giftCard->redeemed_at->format('M j, Y H:i') }}  

The gift card is now marked as redeemed and cannot be used again.

<x-mail::button :url="route('giftcard.index')">
View My Gift Cards
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>