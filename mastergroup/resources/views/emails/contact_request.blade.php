<!doctype html>
<html>
  <body style="font-family:Arial,Helvetica,sans-serif; color:#111;">
    <h2>New contact request</h2>

    <h3>User</h3>
    <table cellpadding="6" cellspacing="0" border="0" style="border-collapse:collapse;">
      <tr><td><strong>ID:</strong></td><td>{{ $user->id }}</td></tr>
      <tr><td><strong>Name:</strong></td><td>{{ $user->full_name ?? $user->name ?? '—' }}</td></tr>
      <tr><td><strong>Email:</strong></td><td>{{ $user->email ?? '—' }}</td></tr>
      <tr><td><strong>Phone:</strong></td><td>{{ $user->phone ?? '—' }}</td></tr>
      <tr><td><strong>Country:</strong></td><td>{{ $user->country ?? '—' }}</td></tr>
      <tr><td><strong>Client type:</strong></td><td>{{ $user->clientTypeValue() ?? '—' }}</td></tr>
      <tr><td><strong>CPS total:</strong></td><td>{{ (int) $user->cps_total }}</td></tr>
    </table>

    <h3>Message</h3>
    <div style="white-space:pre-wrap; border:1px solid #eee; padding:12px; border-radius:8px;">
      {{ $body }}
    </div>

    <h3>Meta</h3>
    <p>
      <strong>IP:</strong> {{ $ip ?? '—' }}<br>
      <strong>UA:</strong> {{ $ua ?? '—' }}<br>
      <strong>Sent at:</strong> {{ now()->toDateTimeString() }}
    </p>
  </body>
</html>
