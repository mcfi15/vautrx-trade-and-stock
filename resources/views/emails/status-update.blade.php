@component('mail::message')
# Withdrawal Status Updated

Hi {{ $withdrawal->user->name }},

Your withdrawal request has been updated.

### **Withdrawal Details**
- **Amount:** {{ $withdrawal->amount }} {{ $withdrawal->cryptocurrency->symbol }}
- **Fee:** {{ $withdrawal->fee }} {{ $withdrawal->cryptocurrency->symbol }}
- **Address:** `{{ $withdrawal->withdrawal_address }}`
- **Status:** **{{ ucfirst($status) }}**

@if(!empty($withdrawal->tx_hash))
- **Transaction Hash:** `{{ $withdrawal->tx_hash }}`
@endif

@if($adminNotes)
### Admin Notes
{{ $adminNotes }}
@endif

@component('mail::button', ['url' => config('app.url')])
Visit Our Website for More Info
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
