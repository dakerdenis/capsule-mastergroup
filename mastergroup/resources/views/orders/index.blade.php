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
                                    <th class="mobile-remove">Quantity</th>
                                    <th>CPS</th>
                                    <th class="mobile-remove">Date of order</th>
                                    <th class="mobile-remove">Date of execution</th>
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
                                        <td class="mobile-remove">{{ (int) ($o->total_qty ?? 0) }}</td>
                                        <td><span class="mobile-remove">CPS</span>
                                            {{ number_format((int) $o->total_cps, 0, '.', ' ') }}</td>
                                        <td class="mobile-remove">{{ $o->created_at?->format('m/d/y') }}</td>
                                        <td class="mobile-remove">{{ $o->executed_at?->format('m/d/y') ?? '—' }}</td>
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
            <button class="modal__close" type="button" aria-label="Close" data-close-modal>
                <img src="{{ asset('images/common/close.svg') }}" alt="">
            </button>
            <div class="modal__body">
                <div class="order_popup__name">
                    <p id="om-id">—</p>
                    <h3 id="order-modal-title" class="modal__title">Order</h3>
                    <!-- ОСТАВЛЯЕМ ТОЛЬКО ОДИН om-status -->
                    <span id="om-status" class="badge">—</span>
                </div>

                <div class="order_popup__desc">
                    <div class="order_popup-data">
                        <p>Order date:</p>
                        <!-- БЫЛО: id="om-status" — это ошибка -->
                        <span id="om-date">—</span>
                    </div>
                    <div class="order_popup-price">
                        <p>Total price:</p> <span id="om-total">0</span>
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
            const itemsEl = document.getElementById('om-items');
            const idEl = document.getElementById('om-id');
            const titleEl = document.getElementById('order-modal-title');
            const statusEl = document.getElementById('om-status');
            const dateEl = document.getElementById('om-date');
            const totalEl = document.getElementById('om-total');

            // === МАППИНГ КАК В PHP (ordered|completed|cancelled [+ опционально processing]) ===
            function setStatusBadge(el, statusRaw) {
                if (!el) return;
                const s = String(statusRaw || '').trim().toLowerCase(); // на входе может прийти 'ORDERED'
                const map = {
                    ordered: 'badge--ordered',
                    processing: 'badge--processing', // на всякий случай, если появится
                    completed: 'badge--completed',
                    cancelled: 'badge--cancelled',
                };
                // убрать прошлые модификаторы
                el.classList.remove('badge--ordered', 'badge--processing', 'badge--completed', 'badge--cancelled');
                // гарантируем базовый .badge
                if (!el.classList.contains('badge')) el.classList.add('badge');
                // навесить нужный
                el.classList.add(map[s] || 'badge--ordered');
                // текст в UPPERCASE как в таблице
                el.textContent = (statusRaw ? String(statusRaw).toUpperCase() : '—');
            }

            function openModal() {
                modal?.classList.add('is-open');
                document.body.classList.add('body--modal-open');
            }

            function closeModal() {
                modal?.classList.remove('is-open');
                document.body.classList.remove('body--modal-open');
            }
            modal?.addEventListener('click', e => {
                if (e.target.matches('[data-close-modal]')) closeModal();
            });
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape' && modal?.classList.contains('is-open')) closeModal();
            });

            const fmt = n => (Math.round((n || 0) * 100) / 100).toLocaleString(undefined, {
                maximumFractionDigits: 2
            });
            const esc = s => (s ?? '').replace(/[&<>"']/g, m => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            } [m]));

            async function fetchOrder(id) {
                const res = await fetch(`/orders/${id}/json`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            }

            function renderItems(items) {
                if (!itemsEl) return;
                itemsEl.innerHTML = '';
                if (!items || !items.length) {
                    itemsEl.innerHTML = '<div style="color:#97a2b6;padding:8px 0;">No items.</div>';
                    return;
                }
                for (const it of items) {
                    const row = document.createElement('div');
                    row.className = 'order_pup-element';
                    row.innerHTML = `
        <div class="order_pup-element-image"><img src="${esc(it.image)}" alt=""></div>
        <div class="order_pup-element-desc">
          <div class="order_pup-element-namecode">
            <div class="order_pup-element-name">${esc(it.name)}</div>
            <div class="order_pup-element-code">${esc((it.type?it.type+' - ':'') + (it.code||''))}</div>
          </div>
          <div class="order_pup-element-price">CPS ${fmt(it.price)} × ${it.qty}</div>
        </div>`;
                    itemsEl.appendChild(row);
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
                    idEl && (idEl.textContent = String(data.id ?? ''));
                    titleEl && (titleEl.textContent = 'Order ' + (data.number || num));
                    setStatusBadge(statusEl, data.status); // ← применяем классы как в PHP
                    dateEl && (dateEl.textContent = data.created_at || '—');
                    totalEl && (totalEl.textContent = fmt(data.total_cps));
                    renderItems(data.items || []);
                    openModal();
                } catch (err) {
                    console.error(err);
                }
            });
        })();
    </script>
@endpush
