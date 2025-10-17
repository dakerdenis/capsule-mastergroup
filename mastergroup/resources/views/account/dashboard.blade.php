@extends('layouts.app')
@section('title', $title ?? 'Homepage')
@section('page_title', 'Welcome to Mastegroup Market')

@push('page-styles')
    {{-- ВАЖНО: у тебя был mismatch в query-параметре: подключаешь account.css, а filemtime берёшь для dashboard.css --}}
    <link rel="stylesheet"
        href="{{ asset('css/market/account.css') }}?v={{ filemtime(public_path('css/market/account.css')) }}">
@endpush

@section('content')
    @php
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $genderMap = [
            'male' => 'Male',
            'female' => 'Female',
            'other' => 'Other',
            null => '—',
            '' => '—',
        ];

        $statusLabel = $user->isApproved() ? 'APPROVED' : ($user->isPending() ? 'PENDING' : 'REJECTED');
        $statusClass = $user->isApproved()
            ? 'badge--approved'
            : ($user->isPending()
                ? 'badge--pending'
                : 'badge--rejected');

        // Красиво форматируем дату рождения, если есть
        $birth = $user->birth_date ? $user->birth_date->format('d.m.Y') : '—';

        // Безопасные фолбэки
        $fullName = $user->full_name ?: ($user->name ?: '—');
        $email = $user->email ?: '—';
        $phone = $user->phone ?: '—';
        $country = $user->country ?: '—';
        $gender = $genderMap[$user->gender ?? null] ?? '—';

        // Баланс бонусов (после миграции ниже)
        $cpsTotal = number_format((int) ($user->cps_total ?? 0));
    @endphp

    <div class="account_wrapper">
        <!----INFORMATION ABOUT ACCOUNT---->
        <div class="account__info">
            <div class="acoount__info-desc">
                Personal information
            </div>
            <div class="acoount__info-container">
                <div class="account__info-wrapper">
                    <div class="account__profile-foto">
                        <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('images/avatar-default.png') }}"
                            alt="Avatar">
                    </div>

                    <div class="account__profile-input account__profile-name">
                        <p>{{ $fullName }}</p>
                    </div>

                    <div class="account__profile-birthname">
                        <div class="account__profile-input account__profile-birth">
                            <p>{{ $birth }}</p>
                        </div>
                        <div class="account__profile-input account__profile-gender">
                            <p>{{ $gender }}</p>
                        </div>
                    </div>

                    <div class="account__profile-input account__profile-email">
                        <p>{{ $email }}</p>
                    </div>

                    <div class="account__profile-input account__profile-number">
                        <p>{{ $phone }}</p>
                    </div>

                    <div class="account__profile-input account__profile-country">
                        <p>{{ $country }}</p>
                    </div>



                    {{-- Опционально специфичные поля по типу клиента --}}
                    @if ($user->isIndividual() && $user->workplace)
                        <div class="account__profile-input account__placeofwork">
                            <p>Workplace: {{ $user->workplace }}</p>
                        </div>
                    @endif

                    @if ($user->isCompany())
                        @if ($user->company_logo_path)
                            <div class="account__profile-company_logo">
                                <img src="{{ asset('storage/' . $user->company_logo_path) }}" alt="Company logo">
                            </div>
                        @endif
                        @if ($user->instagram)
                            @php $ig = ltrim($user->instagram, '@'); @endphp
                            <div class="account__profile-input">
                                <p>
                                    Instagram:
                                    <a href="https://instagram.com/{{ $ig }}" target="_blank" rel="noopener">
                                        {{ '@' . $ig }}
                                    </a>
                                </p>
                            </div>
                        @endif

                    @endif
                    <div class="account__profile-password">
                        <button type="button" class="js-trigger-generate" data-email="{{ $user->email }}"
                            data-success-modal="#modal-change-password">
                            CHANGE PASSWORD
                        </button>
                    </div>

                    <div class="account__profile-changedata">
                        <button type="button" aria-haspopup="dialog" aria-controls="modal-change-data"
                            data-modal-open="#modal-change-data">
                            Change data
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!----Bonuses collection part---->
        <div class="account__bonuses_collection">
            <div class="account__bonuses-desc_collect">
                <p>Bonuses collection history</p>
                <form id="codeForm" action="{{ route('codes.activate') }}" method="POST" autocomplete="off"
                    onsubmit="return false;">
                    @csrf
                    <input id="codeInput" type="text" name="code" placeholder="Enter the product code"
                        inputmode="latin" maxlength="128">
                    <button id="codeBtn" type="submit">REQUEST CPS</button>
                    <div id="codeMsg" class="code-msg" aria-live="polite"></div>
                </form>
            </div>


            {{-- Таблица (как мы переделали ранее) --}}
            <div class="account_bonuses__wrapper">
                <table class="account_bonuses-table">
                    <thead>
                        <tr>
                            <th class="code">PRODUCT CODE</th>
                            <th class="date">ACTIVATION DATE</th>
                            <th class="amount">CPS</th>

                        </tr>
                    </thead>
                    <tbody>
                        {{-- Пример. Позже сюда подставишь реальные записи из БД/сервиса --}}
                        <tr class="account_bonuses-element">
                            <td class="code">#242332228</td>
                            <td class="date">08/20/22</td>
                            <td class="amount">499</td>

                        </tr>
                    </tbody>
                </table>

                <div class="account_bonuses-navigation">
                    <button type="button">Previous</button>
                    <span>1-10 of 100</span>
                    <button type="button">Next</button>
                </div>
            </div>
        </div>
    </div>


    {{-- ==== MODALS ==== --}}
    <div class="modal-overlay" data-modal-overlay hidden></div>

    {{-- Change Data Modal --}}
    <div class="modal" id="modal-change-data" role="dialog" aria-modal="true" aria-labelledby="modal-change-data-title"
        hidden>
        <div class="modal__content" role="document">
            <div class="change__password__wrapper">
                <button type="button" class="modal__close" data-modal-close aria-label="Close">
                    <img src="{{ asset('images/common/close.svg') }}" alt="Close">
                </button>

                <div class="change__password-green">
                    <img src="{{ asset('images/common/thum.png') }}" alt="">
                    <p>Contact with us !</p>
                </div>

                @php $supportEmail = config('mail.from.address') ?? 'profile@capsuleppf.com'; @endphp
                <div class="change__password-desc">
                    Please contact our administrators to change the profile's data —
                    <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a>
                </div>


            </div>
        </div>
    </div>

    {{-- Change Password Modal --}}
    <div class="modal" id="modal-change-password" role="dialog" aria-modal="true"
        aria-labelledby="modal-change-password-title" hidden>
        <div class="modal__content" role="document">
            <div class="change__password__wrapper">
                <button type="button" class="modal__close" data-modal-close>
                    <img src="{{ asset('images/common/close.svg') }}">
                </button>

                <div class="change__password-green">
                    <img src="{{ asset('images/common/thum.png') }}" alt="">
                    <p>A link has been sent to you!</p>
                </div>

                <div class="change__password-desc">
                    Please follow the link sent to the email address you provided to continue the password recovery process.
                </div>


            </div>
        </div>
    </div>

    <!-- Confirm Reset Password (CR) -->
    <div class="cr-overlay" id="cr-overlay" hidden></div>

    <div class="cr-modal" id="cr-modal" aria-hidden="true" role="dialog" aria-modal="true"
        aria-labelledby="cr-title" hidden>
        <div class="cr-dialog" role="document">
            <button type="button" class="cr-close" id="cr-close" aria-label="Close">
                ✕
            </button>

            <div class="cr-header">
                <div class="cr-icon">!</div>
                <h3 class="cr-title" id="cr-title">Confirm action</h3>
            </div>

            <div class="cr-body">
                <p>Are you sure you want to reset your password?</p>
                <p>A new password will be generated and sent to: <strong id="cr-email">—</strong></p>
                <div class="cr-error" id="cr-error" hidden></div>
            </div>

            <div class="cr-actions">
                <button type="button" class="cr-btn cr-btn--danger" id="cr-yes">Yes, reset</button>
                <button type="button" class="cr-btn" id="cr-cancel">Cancel</button>
            </div>
        </div>
    </div>





    @push('page-scripts')
        <script>
            (function() {
                const CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const routeGenerate = @json(route('password.generate'));

                // элементы модалки
                const overlay = document.getElementById('cr-overlay');
                const modal = document.getElementById('cr-modal');
                const emailEl = document.getElementById('cr-email');
                const btnYes = document.getElementById('cr-yes');
                const btnCancel = document.getElementById('cr-cancel');
                const btnClose = document.getElementById('cr-close');
                const errBox = document.getElementById('cr-error');

                let pending = null; // { email, successModal, triggerBtn, originalText }

                function crOpen(email, successModal, triggerBtn) {
                    pending = {
                        email,
                        successModal,
                        triggerBtn,
                        originalText: triggerBtn?.textContent || ''
                    };
                    if (emailEl) emailEl.textContent = email;
                    errBox?.setAttribute('hidden', '');
                    modal?.removeAttribute('hidden');
                    overlay?.removeAttribute('hidden');
                    modal?.setAttribute('aria-hidden', 'false');
                    document.documentElement.style.overflow = 'hidden';
                    btnYes?.focus();
                }

                function crClose() {
                    modal?.setAttribute('hidden', '');
                    overlay?.setAttribute('hidden', '');
                    modal?.setAttribute('aria-hidden', 'true');
                    document.documentElement.style.overflow = '';
                    if (pending?.triggerBtn && typeof pending.triggerBtn.focus === 'function') pending.triggerBtn.focus();
                    pending = null;
                }

                async function postGenerate(email) {
                    const res = await fetch(routeGenerate, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': CSRF,
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            email
                        }),
                        credentials: 'same-origin'
                    });
                    if (!res.ok) {
                        let message = 'Failed to start password reset.';
                        try {
                            const d = await res.json();
                            if (d?.message) message = d.message;
                        } catch (_) {}
                        throw new Error(message);
                    }
                    return true;
                }

                // Клик на CHANGE PASSWORD (кнопка .js-trigger-generate)
                document.addEventListener('click', (e) => {
                    const btn = e.target.closest('.js-trigger-generate');
                    if (!btn) return;

                    e.preventDefault();
                    const email = btn.getAttribute('data-email') || '';
                    const successModal = btn.getAttribute('data-success-modal') || '#modal-change-password';
                    if (!email) return; // нет email — ничего не делаем

                    crOpen(email, successModal, btn);
                });

                // Подтвердить
                btnYes?.addEventListener('click', async () => {
                    if (!pending) return;
                    const {
                        email,
                        successModal,
                        triggerBtn,
                        originalText
                    } = pending;

                    // Заблокировать кнопки на время запроса
                    btnYes.disabled = true;
                    btnCancel.disabled = true;
                    btnClose.disabled = true;
                    if (triggerBtn) {
                        triggerBtn.disabled = true;
                        triggerBtn.textContent = 'Processing…';
                    }

                    try {
                        await postGenerate(email);
                        crClose();

                        // Открыть успех-модалку (используем твою систему, fallback — прозрачный)
                        if (typeof window.openModal === 'function') {
                            window.openModal(successModal);
                        } else {
                            const overlay2 = document.querySelector('[data-modal-overlay]');
                            const m2 = document.querySelector(successModal);
                            if (overlay2 && m2) {
                                m2.hidden = false;
                                overlay2.hidden = false;
                                document.body.setAttribute('data-modal-open', 'true');
                                document.documentElement.style.overflow = 'hidden';
                                (m2.querySelector(
                                    'button,[href],input,textarea,[tabindex]:not([tabindex="-1"])') || m2).focus
                                    ();
                            }
                        }
                    } catch (err) {
                        if (errBox) {
                            errBox.textContent = String(err.message || err);
                            errBox.removeAttribute('hidden');
                        }
                    } finally {
                        btnYes.disabled = false;
                        btnCancel.disabled = false;
                        btnClose.disabled = false;
                        if (triggerBtn) {
                            triggerBtn.disabled = false;
                            triggerBtn.textContent = originalText;
                        }
                    }
                });

                // Закрыть/отмена
                function onCancel() {
                    crClose();
                }
                overlay?.addEventListener('click', onCancel);
                btnCancel?.addEventListener('click', onCancel);
                btnClose?.addEventListener('click', onCancel);
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && !modal?.hasAttribute('hidden')) crClose();
                });

            })();
        </script>
        <script>
            (function() {
                const overlay = document.querySelector('[data-modal-overlay]');

                function actuallyClose(modal) {
                    if (!modal) return;
                    modal.hidden = true;

                    // если открытых модалок больше нет — прячем оверлей и возвращаем скролл
                    const anyOpen = !!document.querySelector('.modal:not([hidden])');
                    if (!anyOpen) {
                        overlay && (overlay.hidden = true);
                        document.body.removeAttribute('data-modal-open');
                        document.documentElement.style.overflow = '';
                    }
                }

                // Глобальные функции (если вдруг их нет)
                window.openModal = window.openModal || function(selector) {
                    const m = document.querySelector(selector);
                    if (!m) return;
                    m.hidden = false;
                    if (overlay) overlay.hidden = false;
                    document.body.setAttribute('data-modal-open', 'true');
                    document.documentElement.style.overflow = 'hidden';
                    (m.querySelector('button,[href],input,textarea,[tabindex]:not([tabindex="-1"])') || m).focus();
                };

                window.closeModal = window.closeModal || function(selOrEl) {
                    const modal = typeof selOrEl === 'string' ? document.querySelector(selOrEl) : selOrEl;
                    actuallyClose(modal);
                };

                // Крестик внутри модалки
                document.addEventListener('click', (e) => {
                    const btn = e.target.closest('[data-modal-close]');
                    if (!btn) return;
                    const modal = btn.closest('.modal');
                    actuallyClose(modal);
                });

                // Клик по оверлею
                overlay?.addEventListener('click', () => {
                    const active = document.querySelector('.modal:not([hidden])');
                    actuallyClose(active);
                });

                // Escape
                document.addEventListener('keydown', (e) => {
                    if (e.key !== 'Escape') return;
                    const active = document.querySelector('.modal:not([hidden])');
                    if (active) actuallyClose(active);
                });
            })();
        </script>
        <!----CODE CHECK---->
        <script>
            (function() {
                const form = document.getElementById('codeForm');
                const input = document.getElementById('codeInput');
                const btn = document.getElementById('codeBtn');
                const msg = document.getElementById('codeMsg');
                const CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const url = form?.getAttribute('action') || '{{ route('codes.activate') }}';

                const regex = /^[A-Z][A-Z0-9]{3,}$/;
                const tbody = document.querySelector('.account_bonuses-table tbody');

                function setMsg(text, type) {
                    if (!msg) return;
                    msg.textContent = text || '';
                    msg.classList.remove('code-msg--error', 'code-msg--ok', 'code-msg--muted');
                    if (type) msg.classList.add(type);
                }

                async function post(url, payload) {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': CSRF || '',
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams(payload),
                        credentials: 'same-origin'
                    });
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok) {
                        const err = new Error(data?.message || 'Request error');
                        err.data = data;
                        throw err;
                    }
                    return data;
                }

                function appendHistoryRow(code, isoDate, cps) {
                    if (!tbody) return;
                    const tr = document.createElement('tr');
                    tr.className = 'account_bonuses-element';
                    const d = isoDate ? new Date(isoDate) : new Date();
                    const mm = String(d.getMonth() + 1).padStart(2, '0');
                    const dd = String(d.getDate()).padStart(2, '0');
                    const yy = String(d.getFullYear()).slice(-2);
                    tr.innerHTML = `
      <td class="code">#${code}</td>
      <td class="date">${mm}/${dd}/${yy}</td>
      <td class="amount">${(cps||0)}</td>
    `;
                    // вставим в начало
                    tbody.insertBefore(tr, tbody.firstChild);
                }

                function bumpHeaderCps(newCps) {
                    try {
                        const top = document.querySelector('.topbar__bonuses p');
                        if (top && typeof newCps === 'number') top.textContent = String(newCps);
                    } catch (_) {}
                }

                form?.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const raw = (input?.value || '').trim().toUpperCase();

                    // локальная проверка
                    if (!raw) {
                        setMsg('Enter the code.', 'code-msg--error');
                        return;
                    }
                    if (!regex.test(raw)) {
                        setMsg('Invalid code format.', 'code-msg--error');
                        return;
                    }

                    // отправка
                    btn.disabled = true;
                    setMsg('Checking…', 'code-msg--muted');
                    try {
                        const data = await post(url, {
                            code: raw
                        });

                        // успех
                        setMsg(`Code activated. +${data.bonus_cps} CPS`, 'code-msg--ok');
                        input.value = '';

                        // обновим историю/баланс
                        appendHistoryRow(data.code || raw, data.activated_at, data.bonus_cps);
                        bumpHeaderCps(data.new_cps);

                    } catch (err) {
                        const e = err.data || {};
                        let text = e.message || 'Activation failed.';
                        if (e.error === 'NOT_FOUND') text = 'Code not found.';
                        if (e.error === 'ALREADY_USED') text = 'This code has already been activated.';
                        if (e.error === 'INVALID_FORMAT') text = 'Invalid code format.';
                        if (e.error === 'NO_BONUS') text = 'This code has no bonus configured.';
                        setMsg(text, 'code-msg--error');
                    } finally {
                        btn.disabled = false;
                    }
                });

            })();
        </script>
    @endpush


@endsection
