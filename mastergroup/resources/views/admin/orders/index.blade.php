@extends('layouts.admin')

@section('title', $title ?? 'Orders')
@section('page_title', 'Orders')
    @push('page-styles')
        <link rel="stylesheet"
            href="{{ asset('css/admin/orders.css') }}?v={{ filemtime(public_path('css/admin/orders.css')) }}">
    @endpush
@section('content')
<div class="card">
    <div class="card-body">
        @if($orders->isEmpty())
            <p class="text-muted">No orders yet.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Number</th>
                            <th>User</th>
                            <th>Total (CPS)</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Executed</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td><strong>{{ $order->number }}</strong></td>
                                <td>
                                    <div>
                                        <div>{{ $order->user?->full_name ?? '—' }}</div>
                                        <small class="text-muted">{{ $order->user?->email }}</small><br>
                                        <small class="text-muted">{{ $order->user?->phone }}</small>
                                    </div>
                                </td>
                                <td>{{ number_format($order->total_cps) }}</td>
                                <td>
                                    @php
                                        $color = match($order->status) {
                                            'ordered'   => 'primary',
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                            default     => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ strtoupper($order->status) }}</span>
                                </td>
                                <td>{{ $order->created_at?->format('Y-m-d H:i') }}</td>
                                <td>{{ $order->executed_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                <td>
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.orders.show', $order) }}">Open</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
