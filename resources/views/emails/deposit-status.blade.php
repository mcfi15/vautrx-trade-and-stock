@component('mail::message')

# Deposit {{ $statusText }}

Hello {{ $deposit->user->name }},

Your deposit request has been **{{ strtolower($statusText) }}**.

---

### 📌 Deposit Details  
- **Amount:** {{ number_format($deposit->amount, 8) }} {{ strtoupper($deposit->cryptocurrency->symbol) }}  
- **Currency:** {{ $deposit->cryptocurrency->name }}  
- **Status:** {{ ucfirst($statusText) }}  
- **Date:** {{ $deposit->created_at->format('M d, Y h:i A') }}

@isset($adminNotes)
### 📝 Admin Notes
> {{ $adminNotes }}
@endisset

---

@if($statusText === 'Approved' || $statusText === 'Completed')
Your wallet balance has been updated accordingly.
@endif

@component('mail::button', ['url' => route('wallet.index')])
View Wallet
@endcomponent

Thanks,  
{{ config('app.name') }}

@endcomponent
