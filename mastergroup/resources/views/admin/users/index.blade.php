@extends('layouts.admin')
@section('title', $title ?? 'Users')
@section('page_title', 'Users')

@section('content')
  <div class="table-wrap">
    <table class="table">
      <thead>
      <tr>
        <th>ID</th>
        <th>Name / Email</th>
        <th>Type</th>
        <th>Phone</th>
        <th>Status</th>
        <th>Created</th>
        <th></th>
      </tr>
      </thead>
<tbody>
@forelse($users as $u)
  @php
    // безопасно приводим к строкам, если это PHP 8.1 enums
    $clientType = is_object($u->client_type)
      ? ($u->client_type->value ?? (string)$u->client_type)
      : $u->client_type;

    $statusVal = is_object($u->status)
      ? ($u->status->value ?? (string)$u->status)
      : $u->status;

    $statusClass = match($statusVal){
      'pending'  => 'badge--pending',
      'approved' => 'badge--approved',
      'rejected' => 'badge--rejected',
      default    => 'badge--pending',
    };
  @endphp
  <tr>
    <td>#{{ $u->id }}</td>
    <td>
      <div class="t-primary">{{ $u->full_name ?? $u->name }}</div>
      <div class="t-muted">{{ $u->email }}</div>
    </td>
    <td>
      <span class="pill">{{ ucfirst(strtolower($clientType ?? '')) }}</span>
    </td>
    <td>{{ $u->phone ?? '—' }}</td>
    <td>
      <span class="badge {{ $statusClass }}">{{ ucfirst(strtolower($statusVal ?? '')) }}</span>
    </td>
    <td>{{ optional($u->created_at)->format('Y-m-d H:i') }}</td>
    <td class="t-actions">
      <a class="btn btn--sm" href="{{ route('admin.users.show', $u) }}">Open</a>
    </td>
  </tr>
@empty
  <tr><td colspan="7" class="t-empty">No users.</td></tr>
@endforelse
</tbody>

    </table>
  </div>

  @if(method_exists($users,'links'))
    <div class="pagination-wrap">
      {{ $users->links() }}
    </div>
  @endif
@endsection
