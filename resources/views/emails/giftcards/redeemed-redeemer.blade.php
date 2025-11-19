<x-mail::message>
# Gift Card Redeemed Successfully

Hello {{ $giftCard->redeemedBy->name }},

You have successfully redeemed a gift card!

**Gift Card Details:**  
- Title: {{ $giftCard->title }}  
- Amount: {{ $giftCard->amount }} {{ $giftCard->cryptocurrency->symbol }}  
- Redeemed: {{ $giftCard->redeemed_at->format('M j, Y H:i') }}  

The amount has been credited to your {{ $giftCard->cryptocurrency->symbol }} wallet.

<x-mail::button :url="route('giftcard.index')">
View My Gift Cards
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>