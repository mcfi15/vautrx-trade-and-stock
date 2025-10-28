@component('mail::message')
# New Login Alert

Hello {{ $user->name }},

We detected a login to your {{ config('app.name') }} account:

@component('mail::table')
| Detail | Information |
|--------|-------------|
| **Time & Date** | {{ $loginHistory->login_at->format('F d, Y \a\t H:i:s T') }} |
| **IP Address** | {{ $loginHistory->ip_address }} |
| **Location** | {{ $loginHistory->formatted_location }} |
| **Device** | {{ $loginHistory->device_info }} |
| **Browser** | {{ $loginHistory->browser ?? 'Unknown' }} |
| **Platform** | {{ $loginHistory->platform ?? 'Unknown' }} |
@endcomponent

@if(!$loginHistory->success)
**Note:** This login attempt was unsuccessful.
**Reason:** {{ $loginHistory->failure_reason }}
@else
**Important:** If this was you, no further action is needed.

**Security Notice:** If this login was not initiated by you, please:
1. Change your password immediately
2. Contact our support team
3. Review your account for any unauthorized activity
@endif

**To manage your login history or secure your account, log in to your dashboard.**

Thanks,<br>
{{ config('app.name') }} Security Team

---
This is an automated security notification. Please do not reply to this email.
@endcomponent