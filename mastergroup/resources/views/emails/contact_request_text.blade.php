New contact request

[User]
ID: {{ $user->id }}
Name: {{ $user->full_name ?? $user->name ?? '—' }}
Email: {{ $user->email ?? '—' }}
Phone: {{ $user->phone ?? '—' }}
Country: {{ $user->country ?? '—' }}
Client type: {{ $user->clientTypeValue() ?? '—' }}
CPS total: {{ (int) $user->cps_total }}

[Message]
{{ $body }}

[Meta]
IP: {{ $ip ?? '—' }}
UA: {{ $ua ?? '—' }}
Sent at: {{ now()->toDateTimeString() }}
