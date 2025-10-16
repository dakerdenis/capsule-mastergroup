@extends('layouts.admin')
@section('title', $title ?? 'Codes')
@section('page_title', 'Codes')

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/admin/codes.css') }}?v={{ filemtime(public_path('css/admin/codes.css')) }}">
@endpush

@section('content')
    <div class="codes-page">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="actions-bar">
            <a href="{{ route('admin.codes.create') }}" class="btn btn--primary">+ Add codes</a>
        </div>

        <form class="filters" method="GET" action="{{ route('admin.codes.index') }}">
            <input class="input" type="text" name="q" value="{{ $q }}" placeholder="Search by code…">

            <select class="select" name="status">
                <option value="">All statuses</option>
                <option value="new" @selected($status === 'new')>New</option>
                <option value="activated" @selected($status === 'activated')>Activated</option>
            </select>

            <select class="select" name="type">
                <option value="">All types</option>
                @foreach ($types as $t)
                    <option value="{{ $t }}" @selected($type === $t)>{{ ucfirst($t) }}</option>
                @endforeach
            </select>

            <div class="right">
                <a href="{{ route('admin.codes.index') }}" class="btn btn--ghost" title="Reset filters">Reset</a>
                <button class="btn btn--primary">Apply</button>
            </div>
        </form>



        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Type</th>
                        <th class="t-right">Bonus (CPS)</th>
                        <th>Status</th>
                        <th>Activated by</th>
                        <th>Activated at</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($codes as $c)
                        @php
                            $statusClass = $c->status === 'new' ? 'badge--pending' : 'badge--approved';
                        @endphp
                        <tr>
                            <td><strong>{{ $c->code }}</strong></td>
                            <td><span class="pill">{{ $c->type ?? '—' }}</span></td>
                            <td class="t-right">{{ is_null($c->bonus_cps) ? '—' : number_format($c->bonus_cps) }}</td>
                            <td><span class="badge {{ $statusClass }}">{{ ucfirst($c->status) }}</span></td>
                            <td>
                                @if ($c->activatedBy)
                                    <div class="tbl-meta">
                                        <span class="t-primary">{{ $c->activatedBy->full_name }}</span>
                                        <small class="muted">{{ $c->activatedBy->email }}</small>
                                    </div>
                                @else
                                    <span class="muted">—</span>
                                @endif
                            </td>
                            <td>{{ optional($c->activated_at)->format('Y-m-d H:i') ?? '—' }}</td>
                            <td>{{ $c->created_at?->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="t-empty">No codes yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            {{ $codes->onEachSide(1)->links('vendor.pagination.clean') }}
        </div>

    </div>
@endsection
