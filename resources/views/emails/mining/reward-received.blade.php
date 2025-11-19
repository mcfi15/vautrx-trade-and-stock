<x-mail::message>
# Mining Reward Received

Hello {{ $reward->user->name }},

You have received a mining reward!

**Details:**  
- Machine: {{ $reward->miningPool->name }}  
- Amount: {{ $reward->amount }} LTC  
- Date: {{ $reward->reward_date }}  

The reward has been automatically added to your LTC wallet.

<x-mail::button :url="route('pool.myRewards')">
View My Rewards
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>