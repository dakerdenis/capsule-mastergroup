@extends('layouts.app')
@section('title', $title ?? 'My Orders')
@section('page_title', 'My Orders')

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/orders/style.css') }}?v={{ filemtime(public_path('css/orders/style.css')) }}">
@endpush

@section('content')
    <div class="orders">
        <div class="orders__container">
            <div class="orders-card">
                <div class="orders-card__body">
                    <div class="table-scroll">
                        <table class="orders-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order</th>
                                    <th>Quantity</th>
                                    <th>CPS</th>
                                    <th>Date of order</th>
                                    <th>Date of execution</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $o)
                                    @php
                                        $statusUpper = strtoupper($o->status); // ORDERED | COMPLETED | CANCELLED
                                        $badge = match ($o->status) {
                                            'completed' => 'badge--completed',
                                            'cancelled' => 'badge--cancelled',
                                            default => 'badge--ordered',
                                        };
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="js-order-cell" data-order="{{ $o->number }}"
                                            data-id="{{ $o->id }}">#{{ $o->number }}</td>
                                        <td>{{ (int) ($o->total_qty ?? 0) }}</td>
                                        <td>CPS {{ number_format((int) $o->total_cps, 0, '.', ' ') }}</td>
                                        <td>{{ $o->created_at?->format('m/d/y') }}</td>
                                        <td>{{ $o->executed_at?->format('m/d/y') ?? '—' }}</td>
                                        <td><span class="badge {{ $badge }}">{{ $statusUpper }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" style="text-align:center;color:#97a2b6">You have no orders yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div><!-- /.table-scroll -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal" id="order-modal" aria-hidden="true">
        <div class="modal__overlay" data-close-modal></div>
        <div class="modal__dialog" role="dialog" aria-modal="true" aria-labelledby="order-modal-title">
            <button class="modal__close" type="button" aria-label="Close" data-close-modal>×</button>
            <div class="modal__body">
                <div class="order_popup__name">
                    <p id="om-id">—</p>
                    <h3 id="order-modal-title" class="modal__title">Order</h3>
                    <span id="om-status">—</span>
                </div>
                <div class="order_popup__desc">
                    <div class="order_popup-data">
                        <p>Order date:</p>
                        <span id="om-date">—</span>
                    </div>
                    <div class="order_popup-price">
                        Total price: <span id="om-total">0</span>
                    </div>
                </div>
                <div class="order_popup-wrapper" id="om-items"></div>
            </div>
        </div>
    </div>

@endsection

@push('page-scripts')
    <script>
        (function() {
            const modal = document.getElementById('order-modal');
            const itemsWrap = document.getElementById('om-items');
            const elId = document.getElementById('om-id');
            const elTitle = document.getElementById('order-modal-title');
            const elStatus = document.getElementById('om-status');
            const elDate = document.getElementById('om-date');
            const elTotal = document.getElementById('om-total');

            function openModal() {
                modal.classList.add('is-open');
                document.body.classList.add('body--modal-open');
            }

            function closeModal() {
                modal.classList.remove('is-open');
                document.body.classList.remove('body--modal-open');
            }
            modal.addEventListener('click', e => {
                if (e.target.matches('[data-close-modal]')) closeModal();
            });
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
            });

            function fmt(n) {
                return (Math.round(n * 100) / 100).toLocaleString(undefined, {
                    maximumFractionDigits: 2
                });
            }

            function esc(s) {
                return (s ?? '').replace(/[&<>"']/g, m => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                } [m]));
            }

            async function fetchOrder(id) {
                const res = await fetch(`/orders/${id}/json`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return await res.json();
            }

            function renderItems(items) {
                itemsWrap.innerHTML = '';
                if (!items || !items.length) {
                    itemsWrap.innerHTML = '<div style="color:#97a2b6;padding:8px 0;">No items.</div>';
                    return;
                }
                for (const it of items) {
                    const row = document.createElement('div');
                    row.className = 'order_pup-element';
                    row.innerHTML = `
        <div class="order_pup-element-image"><img src="${it.image}" alt=""></div>
        <div class="order_pup-element-desc">
          <div class="order_pup-element-namecode">
            <div class="order_pup-element-name">${esc(it.name)}</div>
            <div class="order_pup-element-code">${esc((it.type?it.type+' - ':'') + (it.code||''))}</div>
          </div>
          <div class="order_pup-element-price">CPS ${fmt(it.price)} × ${it.qty}</div>
        </div>
      `;
                    itemsWrap.appendChild(row);
                }
            }

            document.addEventListener('click', async (e) => {
                const cell = e.target.closest('.js-order-cell');
                if (!cell) return;
                e.preventDefault();
                const id = cell.dataset.id;
                const num = cell.dataset.order || cell.textContent.trim();
                try {
                    const data = await fetchOrder(id);
                    elId.textContent = String(data.id);
                    elTitle.textContent = 'Order ' + (data.number || num);
                    elStatus.textContent = data.status || '—';
                    elDate.textContent = data.created_at || '—';
                    elTotal.textContent = fmt(data.total_cps || 0);
                    renderItems(data.items || []);
                    openModal();
                } catch (_) {}
            });
        })();
    </script>
@endpush
