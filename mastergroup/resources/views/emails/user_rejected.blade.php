@php
  $name = $user->full_name ?? $user->name ?? 'there';
@endphp
<!doctype html>
<html>
  <body style="font-family:Arial,Helvetica,sans-serif;background:#f7f7fb;padding:24px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width:640px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden;">
      <tr>
        <td style="padding:28px 28px 8px;">
          <h1 style="margin:0 0 8px;font-size:22px;color:#0f172a;">Access update, {{ $name }}</h1>
          <p style="margin:0;color:#334155;font-size:15px;">
            We’re sorry to inform you that your access request to <strong>CAPSULE PPF</strong> has been <strong>rejected</strong> at this time.
          </p>
        </td>
      </tr>
      @if(!empty($reason))
      <tr>
        <td style="padding:12px 28px 0;">
          <p style="margin:0;color:#334155;font-size:14px;">
            <strong>Reason:</strong> {{ $reason }}
          </p>
        </td>
      </tr>
      @endif
      <tr>
        <td style="padding:12px 28px 28px;">
          <p style="margin:16px 0 0;color:#334155;font-size:15px;">
            For further assistance, please contact the administrator at
            <a href="mailto:contact@capsuleppf.com">contact@capsuleppf.com</a>.
          </p>
          <p style="margin:18px 0 0;color:#475569;font-size:13px;">
            Sincerely,<br>
            CAPSULE PPF Team
          </p>
        </td>
      </tr>
    </table>
  </body>
</html>
