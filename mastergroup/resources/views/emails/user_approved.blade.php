@php
  $name = $user->full_name ?? $user->name ?? 'there';
@endphp
<!doctype html>
<html>
  <body style="font-family:Arial,Helvetica,sans-serif;background:#f7f7fb;padding:24px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width:640px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden;">
      <tr>
        <td style="padding:28px 28px 8px;">
          <h1 style="margin:0 0 8px;font-size:22px;color:#0f172a;">Welcome aboard, {{ $name }}!</h1>
          <p style="margin:0;color:#334155;font-size:15px;">
            Your access to <strong>CAPSULE PPF</strong> has been <strong>approved</strong>. You can now sign in and start using the system.
          </p>
        </td>
      </tr>
      <tr>
        <td style="padding:0 28px 8px;">
          <p style="margin:16px 0 0;color:#334155;font-size:15px;">
            If you didn’t expect this email, please ignore it or contact our support.
          </p>
        </td>
      </tr>
      <tr>
        <td style="padding:0 28px 28px;">
          <p style="margin:18px 0 0;color:#475569;font-size:13px;">
            Best regards,<br>
            CAPSULE PPF Team
          </p>
        </td>
      </tr>
    </table>
  </body>
</html>
