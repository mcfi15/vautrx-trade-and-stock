@component('mail::message')
# Verify Your Email Address

Hello {{ $user->name }},

Thank you for registering with {{ config('app.name') }}! 

Please click the button below to verify your email address:

@component('mail::button', ['url' => $verificationUrl])
Verify Email Address
@endcomponent

If you're having trouble clicking the button, copy and paste the URL below into your web browser:

{{ $verificationUrl }}

**This verification link will expire in 24 hours.**

If you did not create this account, no further action is required.

Thanks,<br>
{{ config('app.name') }} Team
@endcomponent