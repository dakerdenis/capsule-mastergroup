@extends('layouts.admin')

@section('title', $title ?? 'Gift CPS')
@section('page_title', 'Gift CPS')

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/admin/gift.css') }}?v={{ filemtime(public_path('css/admin/gift.css')) }}">
@endpush

@section('content')
    <div class="gift__container">
        <div class="admin-card">
            <h3>Gift CPS to a user</h3>

            @if ($errors->any())
                <div class="alert alert--error">
                    <ul>
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert--ok">{{ session('status') }}</div>
            @endif

            <form action="{{ route('admin.gifts.store') }}" method="POST" class="gift-form" id="giftForm" autocomplete="off">

                @csrf

                <div class="form-row">
                    <label for="identifier">User (email or ID) <span class="req">*</span></label>
                    <input type="text" id="identifier" name="identifier" required placeholder="user@example.com or 123"
                        value="{{ old('identifier') }}">
                    <datalist id="usersHints">
                        @foreach ($users as $u)
                            <option value="{{ $u->email }}">{{ $u->full_name }} (ID: {{ $u->id }})</option>
                        @endforeach
                    </datalist>
                    {{-- если хочешь подсказки — добавь list="usersHints" на input --}}
                </div>

                <div class="form-row">
                    <label for="amount">Amount (CPS) <span class="req">*</span></label>
                    <input type="number" id="amount" name="amount" required min="1" max="100000"
                        value="{{ old('amount', 50) }}">
                </div>

                <div class="form-row">
                    <label for="note">Note (optional)</label>
                    <input type="text" id="note" name="note" maxlength="500" value="{{ old('note') }}"
                        placeholder="Reason / internal comment">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn--primary" id="giftBtn">Gift CPS</button>
                </div>
            </form>

            <details class="hints">
                <summary>Quick hints (last users)</summary>
                <ul class="mini-list">
                    @foreach ($users as $u)
                        <li>
                            <code>ID: {{ $u->id }}</code> — {{ $u->full_name ?: '—' }} — {{ $u->email }}
                            {{ $u->phone ? ' — ' . $u->phone : '' }}
                        </li>
                    @endforeach
                </ul>
            </details>
        </div>
    </div>


    @push('page-scripts')
        <script>
            (function() {
                const f = document.getElementById('giftForm');
                const btn = document.getElementById('giftBtn');
                f?.addEventListener('submit', () => {
                    btn?.setAttribute('disabled', 'disabled');
                    btn.textContent = 'Processing...';
                });
            })();
        </script>
    @endpush
@endsection
