<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Your new password</title>
</head>
<body style="font-family:Arial,Helvetica,sans-serif;line-height:1.5;color:#222">
  <div style="max-width:600px;margin:0 auto;padding:24px">
    <h2 style="margin:0 0 12px">Password reset</h2>
    <p>Hello, {{ $username }}!</p>
    <p>Your new password has been generated:</p>
    <pre style="background:#f5f5f5;padding:12px;border-radius:6px;display:inline-block">{{ $password }}</pre>
    <p style="margin-top:16px">Use it to sign in and then change it in your profile.</p>
    <hr style="margin:24px 0;border:none;border-top:1px solid #eee">
    <p style="font-size:12px;color:#888">If you did not request this, please contact support immediately.</p>
  </div>
</body>
</html>
