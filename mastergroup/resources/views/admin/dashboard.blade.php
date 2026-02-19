@extends('layouts.admin')
@section('title', $title ?? 'Admin Dashboard')
@section('page_title', 'Dashboard')
@push('page-styles')
    <link rel="stylesheet"
        href="{{ asset('css/admin/dashboard.css') }}?v={{ filemtime(public_path('css/admin/dashboard.css')) }}">
@endpush

@section('content')
<style>
    @media (max-width: 768px){
  .filters{
    gap:10px;
  }
  .filters select,
  .filters input[type="text"]{
    flex:1 1 160px;
    min-width:140px;
  }
  .btn{
    flex:0 0 auto;
  }
      .sidebar_car {
        width: 156px;
        height: 243px;
      }
}

</style>
    <div class="admin-card" style="margin-bottom:16px">
        <h3 style="margin:0 0 10px">Auth activity</h3>
        <form method="GET" action="{{ route('admin.dashboard') }}" class="filters"
            style="display:flex;gap:8px;flex-wrap:wrap">
            <select name="event">
                <option value="">All events</option>
                @foreach (['login' => 'Login', 'logout' => 'Logout', 'login_failed' => 'Login failed'] as $k => $v)
                    <option value="{{ $k }}" @selected(request('event') === $k)>{{ $v }}</option>
                @endforeach
            </select>
            <select name="guard">
                <option value="">All guards</option>
                @foreach (['web' => 'web', 'admin' => 'admin'] as $g)
                    <option value="{{ $g }}" @selected(request('guard') === $g)>{{ $g }}</option>
                @endforeach
            </select>
            <input type="text" name="email" placeholder="Filter by email" value="{{ request('email') }}">
            <button type="submit" class="btn btn--primary">Apply</button>
            <a href="{{ route('admin.dashboard') }}" class="btn">Reset</a>
        </form>
    </div>

    <div class="admin-card">
        <div class="table-wrap">
            <table class="table" style="width:100%;border-collapse:collapse">
                <thead>
                    <tr>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb;">Date</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb;">Event</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb;">Guard</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb;">User</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb;">Email</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb;">IP</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb;">User-Agent</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $row)
                        <tr>
                            <td style="padding:8px;border-bottom:1px solid #f1f5f9;">
                                {{ \Carbon\Carbon::parse($row->created_at)->format('Y-m-d H:i:s') }}
                            </td>
                            <td>
                                @if ($row->event === 'login')
                                    <span class="badge badge--login">login</span>
                                @elseif($row->event === 'logout')
                                    <span class="badge badge--logout">logout</span>
                                @else
                                    <span class="badge badge--failed">login failed</span>
                                @endif
                            </td>

                            <td style="padding:8px;border-bottom:1px solid #f1f5f9;">{{ $row->guard }}</td>
                            <td style="padding:8px;border-bottom:1px solid #f1f5f9;">
                                @if ($row->user)
                                    #{{ $row->user_id }} — {{ $row->user->full_name ?? '—' }}
                                @elseif($row->user_id)
                                    #{{ $row->user_id }}
                                @else
                                    —
                                @endif
                            </td>
                            <td style="padding:8px;border-bottom:1px solid #f1f5f9;">
                                {{ $row->email ?? ($row->user->email ?? '—') }}</td>
                            <td style="padding:8px;border-bottom:1px solid #f1f5f9;">{{ $row->ip ?? '—' }}</td>
                            <td
                                style="padding:8px;border-bottom:1px solid #f1f5f9;max-width:380px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $row->user_agent ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding:16px;color:#6b7280;text-align:center">No auth activity yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        <div class="pagination" style="margin-top:12px">
            {{ $logs->onEachSide(1)->links() }}

        </div>
    </div>
@endsection
