<x-mail::message>
# Mining Purchase Confirmed

Hello {{ $machine->user->name }},

Your mining machine purchase has been confirmed!

**Details:**  
- Machine: {{ $machine->miningPool->name }}  
- Quantity: {{ $machine->quantity }}  
- Total Cost: {{ $machine->total_cost }} LTC  
- Daily Reward: {{ $machine->daily_reward }} LTC  
- Duration: {{ $machine->miningPool->duration_days }} days  
- Start Date: {{ $machine->start_date->format('M j, Y') }}  
- End Date: {{ $machine->end_date->format('M j, Y') }}  

You will start receiving daily rewards from tomorrow.

<x-mail::button :url="route('pool.myMachines')">
View My Machines
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>