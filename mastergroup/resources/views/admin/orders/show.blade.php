{{-- resources/views/admin/orders/show.blade.php --}}
@extends('layouts.admin')
@section('title', $title ?? 'Order #' . $order->number)
@section('page_title', 'Order #' . $order->number)

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/admin/order.css') }}?v={{ filemtime(public_path('css/admin/order.css')) }}">
@endpush

@section('content')
@php
    $status = $order->status;
    $badge = match ($status) {
        'ordered' => 'badge--primary',
        'completed' => 'badge--success',
        'cancelled' => 'badge--danger',
        default => 'badge--secondary',
    };

    // Подсчёты
    $itemsCount = $order->items?->sum('qty') ?? 0;
    $subtotal   = $order->items?->reduce(fn($c, $i) => $c + (($i->price_cps ?? 0) * ($i->qty ?? 0)), 0) ?? 0;
    $totalCps   = $order->total_cps ?? $subtotal;
@endphp


    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    <div class="show__order-wrapper">

        <div class="order-head">
            <div class="order-head__left">
                <div class="order-id">
                    <strong>#{{ $order->number }}</strong>
                    <span class="muted">ID {{ $order->id }}</span>
                </div>

                <div class="status">
                    <span class="badge {{ $badge }}">{{ strtoupper($status) }}</span>

                    <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="status-form"
                        id="statusForm">
                        @csrf @method('PATCH')
                        <select name="status" class="select" aria-label="Change status" id="statusSelect">
                            <option value="ordered" @selected($status === 'ordered')>ordered</option>
                            <option value="completed" @selected($status === 'completed')>completed</option>
                            <option value="cancelled" @selected($status === 'cancelled')>cancelled</option>
                        </select>
                        <button class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>

            <div class="order-head__right">
                <a class="btn" href="{{ route('admin.orders.index') }}">Back to list</a>
                <button class="btn" type="button" onclick="window.print()">Print</button>
            </div>
        </div>

        <div class="order-grid">
            {{-- LEFT: ITEMS --}}
            <div class="order-card">
                <div class="card-title">Items ({{ $itemsCount }})</div>

                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width:68px">Photo</th>
                                <th>Product</th>
                                <th style="width:120px">Price</th>
                                <th style="width:90px">Qty</th>
                                <th style="width:140px; text-align:right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->items as $it)
@php
    $p = $it->product;
    $photoPath = optional($p->primaryImage)->path ?? optional($p->images->first())->path;
    if ($photoPath) {
        $img = \Illuminate\Support\Str::startsWith($photoPath, ['http://', 'https://'])
            ? $photoPath : asset('storage/' . ltrim($photoPath, '/'));
    } else {
        $img = asset('images/common/placeholder.png');
    }
    $lineTotal = number_format(((float)($it->price_cps ?? 0) * (int)($it->qty ?? 0)), 2, '.', ' ');
@endphp


                                <tr>
                                    <td>
                                        <div class="thumb"><img src="{{ $img }}" alt=""></div>
                                    </td>
                                    <td>
                                        <div class="tbl-meta">
                                            <span class="t-primary">{{ $p?->name ?? '—' }}</span>
                                            <small class="muted">Code: {{ $p?->code ?? '—' }}</small>
                                        </div>
                                    </td>
                                    <td>{{ number_format((float)($it->price_cps ?? 0), 2, '.', ' ') }}</td>
<td>{{ (int)($it->qty ?? 0) }}</td>
<td class="t-right"><strong>{{ $lineTotal }}</strong></td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="t-empty">No items.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- RIGHT: META --}}
            <div class="order-aside">
                <div class="order-card">
                    <div class="card-title">Customer</div>
                    <dl class="dl">
                        <div>
                            <dt>Name</dt>
                            <dd>{{ $order->user?->full_name ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt>Email</dt>
                            <dd>{{ $order->user?->email ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt>Phone</dt>
                            <dd>{{ $order->user?->phone ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt>Country</dt>
                            <dd>{{ $order->user?->country ?? '—' }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="order-card">
                    <div class="card-title">Summary</div>
                    <ul class="kv">
                        <li><span>Subtotal</span><strong>{{ number_format((float) $subtotal, 2, '.', ' ') }} CPS</strong>
                        </li>
                        <li><span>Discounts</span><strong>0.00 CPS</strong></li>
                        <li class="sep"></li>
                        <li class="total"><span>Total</span><strong>{{ number_format((float) $totalCps, 2, '.', ' ') }}
                                CPS</strong></li>
                    </ul>
                </div>

                <div class="order-card">
                    <div class="card-title">Metadata</div>
                    <ul class="kv">
                        <li><span>Created</span><strong>{{ $order->created_at?->format('Y-m-d H:i') ?? '—' }}</strong></li>
                        <li><span>Executed</span><strong>{{ $order->executed_at?->format('Y-m-d H:i') ?? '—' }}</strong>
                        </li>
                        <li><span>Status</span><strong class="mono">{{ $order->status }}</strong></li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
    @push('scripts')
        <script>
            // Можно авто-сабмитить при изменении статуса — если не надо, удали handler:
            document.getElementById('statusSelect')?.addEventListener('change', function() {
                // document.getElementById('statusForm')?.submit();
            });
        </script>
    @endpush
@endsection
