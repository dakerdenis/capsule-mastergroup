@extends('layouts.admin')
@section('title', $title ?? 'Add codes')
@section('page_title', 'Add codes')

@push('page-styles')
<link rel="stylesheet" href="{{ asset('css/admin/codes.css') }}?v={{ filemtime(public_path('css/admin/codes.css')) }}">
@endpush

@section('content')
  @php
    $map = collect(config('codes.prefix_map'));
  @endphp

  <form action="{{ route('admin.codes.store') }}" method="POST" class="form-card">
    @csrf

    <div class="form-head">
      <div>
        <strong>Bulk add</strong>
        <span class="help">Up to {{ $max }} codes. One per line. Types/bonuses are auto-detected by the first two letters.</span>
      </div>
      <a href="{{ route('admin.codes.index') }}" class="btn">Back</a>
    </div>

    {{-- Legend of prefixes --}}
    <section class="bonus-legend">
      <header class="legend-head">
        <h4>Prefixes & bonuses</h4>
        <span class="legend-note">Example: <code>AA12345</code> → Type: <b>{{ $map['AA']['type'] ?? '—' }}</b>, +<b>{{ $map['AA']['bonus_cps'] ?? 0 }}</b> CPS</span>
      </header>

      <div class="legend-grid">
        @foreach($map as $prefix => $meta)
          <div class="legend-card">
            <div class="legend-card__line">
              <span class="legend-prefix" aria-label="Prefix">{{ $prefix }}</span>
              <span class="legend-type">{{ ucfirst($meta['type']) }}</span>
            </div>
            <div class="legend-bonus">
              <span class="legend-bonus__num">{{ (int)($meta['bonus_cps'] ?? 0) }}</span>
              <span class="legend-bonus__unit">CPS</span>
            </div>
          </div>
        @endforeach
      </div>
    </section>

    @error('codes') <div class="alert alert-danger" style="white-space:pre-line">{{ $message }}</div> @enderror

    <div class="field" style="margin-top:10px">
      <label class="label">Codes *</label>
      <textarea class="textarea" name="codes" rows="10" placeholder="AA12345
AB9XYZ
AC0001
…">{{ old('codes') }}</textarea>
      <div class="hint">Allowed: 2 letters + letters/digits ({{ config('codes.regex') }}).</div>
    </div>

    <div class="actions">
      <button class="btn btn--primary">Add</button>
      <a href="{{ route('admin.codes.index') }}" class="btn">Cancel</a>
    </div>
  </form>
@endsection
