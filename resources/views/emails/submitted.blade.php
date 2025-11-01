@component('mail::message')
# Withdrawal Request Submitted

Hello {{ $withdrawal->user->name }},

Your withdrawal request has been submitted successfully.

**Amount:** {{ $withdrawal->amount }} {{ $withdrawal->cryptocurrency->symbol }}  
**Fee:** {{ $withdrawal->fee }}  
**Net Amount:** {{ $withdrawal->net_amount }}  
**Address:** {{ $withdrawal->withdrawal_address }}  
**Status:** Pending Approval

We will notify you once it is processed.

Thanks,  
**{{ config('app.name') }} Team**
@endcomponent
