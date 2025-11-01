@component('mail::message')
# Deposit Submitted Successfully ✅

Hello **{{ $user->name }}**,  
Your deposit request has been received and is pending admin approval.

---

### 📄 Deposit Details
- **Crypto:** {{ strtoupper($crypto->symbol) }}
- **Amount:** {{ number_format($deposit->amount, 8) }}
- **Status:** Pending Review
- **Date:** {{ $deposit->created_at->format('M d, Y H:i A') }}

---

We will notify you once it is approved.

Thanks for using our platform! 🚀  
@endcomponent
