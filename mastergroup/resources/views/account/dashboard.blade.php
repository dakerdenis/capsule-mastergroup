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
                        <button type="button" aria-haspopup="dialog" aria-controls="modal-change-password"
                            data-modal-open="#modal-change-password">
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
                <form action="#">
                    <input type="text" placeholder="Enter the product code">
                    <button type="button">REQUEST CPS</button>
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
                        <tr class="account_bonuses-element">
                            <td class="code">#242332229</td>
                            <td class="date">08/22/22</td>
                            <td class="amount">199</td>

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
                <button type="button" class="modal__close" data-modal-close >
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



    @push('page-scripts')
        <script>
            (function() {
                const overlay = document.querySelector('[data-modal-overlay]');
                let lastFocused = null;

                function openModal(selector) {
                    const modal = document.querySelector(selector);
                    if (!modal) return;

                    lastFocused = document.activeElement;
                    modal.hidden = false;
                    overlay.hidden = false;
                    document.body.setAttribute('data-modal-open', 'true');

                    // фокус на первую интерактивную внутри модалки
                    const focusable = modal.querySelector(
                        'button, [href], input, textarea, [tabindex]:not([tabindex="-1"])');
                    (focusable || modal).focus();

                    // запретим скролл страницы (дальше стили добавишь сам, если нужно)
                    document.documentElement.style.overflow = 'hidden';
                }

                function closeModal(modal) {
                    if (!modal) return;
                    modal.hidden = true;

                    // закрыть, если ни одна модалка не открыта
                    const anyOpen = !!document.querySelector('.modal:not([hidden])');
                    if (!anyOpen) {
                        overlay.hidden = true;
                        document.body.removeAttribute('data-modal-open');
                        document.documentElement.style.overflow = '';
                    }

                    // вернуть фокус туда, откуда пришли
                    if (lastFocused && document.body.contains(lastFocused)) {
                        lastFocused.focus();
                        lastFocused = null;
                    }
                }

                // Открытие
                document.addEventListener('click', function(e) {
                    const opener = e.target.closest('[data-modal-open]');
                    if (opener) {
                        const target = opener.getAttribute('data-modal-open');
                        openModal(target);
                    }
                });

                // Закрытие по кнопкам
                document.addEventListener('click', function(e) {
                    if (e.target.closest('[data-modal-close]')) {
                        const modal = e.target.closest('.modal');
                        closeModal(modal);
                    }
                });

                // Клик по оверлею — закрыть активную модалку
                overlay?.addEventListener('click', function() {
                    const active = document.querySelector('.modal:not([hidden])');
                    closeModal(active);
                });

                // Escape
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        const active = document.querySelector('.modal:not([hidden])');
                        if (active) closeModal(active);
                    }
                });

                // Примитивный фокус-трап (опционально усильшь)
                document.addEventListener('keydown', function(e) {
                    if (e.key !== 'Tab') return;
                    const modal = document.querySelector('.modal:not([hidden])');
                    if (!modal) return;

                    const focusables = modal.querySelectorAll(
                        'button, [href], input, textarea, select, [tabindex]:not([tabindex="-1"])');
                    const list = Array.prototype.slice.call(focusables);
                    if (list.length === 0) return;

                    const first = list[0];
                    const last = list[list.length - 1];

                    if (e.shiftKey && document.activeElement === first) {
                        e.preventDefault();
                        last.focus();
                    } else if (!e.shiftKey && document.activeElement === last) {
                        e.preventDefault();
                        first.focus();
                    }
                });
            })();
        </script>
    @endpush


@endsection
