{{-- resources/views/admin/users/show.blade.php --}}
@extends('layouts.admin')
@section('title', $title ?? 'User')
@section('page_title', 'User #'.$user->id)

@section('content')
  @php
    $clientType = is_object($user->client_type) ? ($user->client_type->value ?? (string)$user->client_type) : $user->client_type;
    $statusVal  = is_object($user->status) ? ($user->status->value ?? (string)$user->status) : $user->status;
    $badgeCls = match($statusVal){ 'pending'=>'badge--pending','approved'=>'badge--approved','rejected'=>'badge--rejected', default=>'badge--pending', };
  @endphp

  @if(session('status'))
    <div class="card" style="margin-bottom:12px">{{ session('status') }}</div>
  @endif

  <div class="user-show">
    <div class="user-show__grid">
      <div class="user-show__main card">
        <div style="display:flex;align-items:center;gap:10px;justify-content:space-between;margin-bottom:10px">
          <h3 class="card-title" style="margin:0">{{ $user->full_name ?? $user->name }}</h3>

          {{-- Кнопки смены статуса --}}
          <form id="statusForm" action="{{ route('admin.users.status', $user) }}" method="POST" class="inline">
            @csrf @method('PATCH')
            <input type="hidden" name="status" id="statusInput">
            <input type="hidden" name="rejected_reason" id="rejectReason">
            <div style="display:flex;gap:6px">
              <button type="button" class="btn btn--sm" data-status="approved">Approve</button>
              <button type="button" class="btn btn--sm" data-status="pending">Pending</button>
              <button type="button" class="btn btn--sm" data-status="rejected">Reject</button>
            </div>
          </form>
        </div>

        <dl class="dl">
          <div><dt>Client type</dt><dd>{{ ucfirst(strtolower($clientType ?? '')) }}</dd></div>
          <div><dt>Status</dt><dd><span class="badge {{ $badgeCls }}">{{ ucfirst(strtolower($statusVal ?? '')) }}</span></dd></div>
          <div><dt>Email</dt><dd>{{ $user->email }}</dd></div>
          <div><dt>Phone</dt><dd>{{ $user->phone ?? '—' }}</dd></div>
          <div><dt>Country</dt><dd>{{ $user->country ?? '—' }}</dd></div>
          <div><dt>Birth date</dt><dd>{{ $user->birth_date ?? '—' }}</dd></div>
          <div><dt>Gender</dt><dd>{{ $user->gender ?? '—' }}</dd></div>
          <div><dt>Workplace</dt><dd>{{ $user->workplace ?? '—' }}</dd></div>
          <div><dt>Instagram</dt><dd>{{ $user->instagram ?? '—' }}</dd></div>
          <div><dt>Created</dt><dd>{{ optional($user->created_at)->format('Y-m-d H:i') }}</dd></div>
          <div><dt>Approved at</dt><dd>{{ optional($user->approved_at)->format('Y-m-d H:i') ?? '—' }}</dd></div>
          @if($user->rejected_reason)
            <div><dt>Rejected reason</dt><dd>{{ $user->rejected_reason }}</dd></div>
          @endif
        </dl>
      </div>

      <div class="user-show__media card">
        <h3 class="card-title">Media</h3>
        <div class="media-list">
          @if($user->profile_photo_path)
            <div class="media-item">
              <div class="media-label">Profile photo</div>
              <a href="{{ asset('storage/'.$user->profile_photo_path) }}" target="_blank" rel="noopener">
                <img src="{{ asset('storage/'.$user->profile_photo_path) }}" alt="" class="media-img">
              </a>
            </div>
          @endif
          @if($user->identity_photo_path)
            <div class="media-item">
              <div class="media-label">Identity</div>
              <a href="{{ asset('storage/'.$user->identity_photo_path) }}" target="_blank" rel="noopener">
                <img src="{{ asset('storage/'.$user->identity_photo_path) }}" alt="" class="media-img">
              </a>
            </div>
          @endif
          @if($user->company_logo_path)
            <div class="media-item">
              <div class="media-label">Company logo</div>
              <a href="{{ asset('storage/'.$user->company_logo_path) }}" target="_blank" rel="noopener">
                <img src="{{ asset('storage/'.$user->company_logo_path) }}" alt="" class="media-img">
              </a>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const form = document.getElementById('statusForm');
      if (!form) return;
      const inputStatus = document.getElementById('statusInput');
      const rejectReason = document.getElementById('rejectReason');

      form.querySelectorAll('[data-status]').forEach(btn => {
        btn.addEventListener('click', () => {
          const val = btn.getAttribute('data-status');
          inputStatus.value = val;

          if (val === 'rejected') {
            const r = prompt('Rejected reason (optional):', '');
            if (r === null) return; // нажал Cancel — не отправляем
            rejectReason.value = r.trim();
          } else {
            rejectReason.value = '';
          }

          form.submit();
        });
      });
    });
  </script>
  @endpush
@endsection
