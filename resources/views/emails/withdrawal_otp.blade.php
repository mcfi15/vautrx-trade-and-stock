<!doctype html>
<html>
<head><meta charset="utf-8"></head>
<body>
    <p>Hi,</p>
    <p>Your withdrawal OTP is: <strong>{{ $otp }}</strong></p>
    <p>This code expires in 10 minutes. If you did not request this, ignore this email.</p>
    <p>Regards,<br/>{{ config('app.name') }}</p>
</body>
</html>
