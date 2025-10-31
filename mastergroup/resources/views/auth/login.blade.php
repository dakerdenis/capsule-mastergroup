@extends('layouts.auth')

@section('title', $title ?? 'Sign in')
@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}?v={{ filemtime(public_path('css/auth/login.css')) }}">

    <style>
        /* мобилка — показываем блок под паролем */
        @media (max-width: 767.98px) {
            .auth-error--mobile {
                display: block;
                margin-top: 8px;
                font-size: 14px;
                line-height: 1.35;
                color: #DC2626;
            }

            .auth-error--desktop {
                display: none;
            }
        }

        /* десктоп — показываем общий блок над формой */
        @media (min-width: 768px) {
            .auth-error--mobile {
                display: none;
            }

            .auth-error--desktop {
                display: block;

                font-size: 18px;
                font-family: 'Inter', sans-serif;
                color: #B91C1C;
                border-radius: 8px;
                font-size: 14px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="auth_page-container">
        <div class="auth_page-wrapper">
            <div class="auth__form">
                <div class="auth__form-back">
                    <img src="{{ asset('images/auth/back_.png') }}" alt="Capsuleppf Back">
                </div>
                <div class="auth__form-container">
                    <div class="auth__form__wrapper">
                        <a href="{{ route('home') }}" class="auth__form-logo">
                            <img src="{{ asset('images/common/capsule_logo-white.png') }}" alt="Capsuleppf Logo">
                        </a>

                        <div class="auth__form-name">
                            <h2>Welcome to Mastergroup Portal</h2>
                            <p>Masters marketpace. Use bonuses - get gifts.</p>
                            <div class="auth__form-lined">
                                <div class="auth-line"></div>
                                <div class="auth__form-desc">enter your credentials</div>
                                <div class="auth-line"></div>
                            </div>
                        </div>

                        <form action="{{ route('auth.login.submit') }}" method="POST" class="auth__form-form"
                            id="loginForm" novalidate>
                            @csrf

                            <div class="form-block" id="emailBlock">
                                <input id="email" name="email" type="email" placeholder="Login (e-mail)"
                                    autocomplete="username" value="{{ old('email') }}">
                            </div>

                            <div class="form-block form-block--with-eye" id="passBlock">
                                <input id="password" name="password" type="password" placeholder="Password"
                                    autocomplete="current-password">
                                <button type="button" class="input-eye" aria-label="Show password" aria-pressed="false">
                                    <span class="eye-icon" aria-hidden="true"></span>
                                </button>


                            </div>
                            {{-- Ошибка только для мобильных экранов --}}
                            @if ($errors->has('email'))
                                <div class="auth-error--mobile" role="alert">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                            {{-- Ошибка для десктопа (>=768px) --}}
                            @if ($errors->has('email'))
                                <div class="auth-error--desktop" role="alert">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                            <div class="form-forgot">
                                <a href="{{ route('password.forgot') }}">Forgot password ?</a>
                            </div>

                            <div class="form-button">
                                <button type="submit">
                                    <p>LOG IN</p>
                                </button>
                            </div>
                        </form>

                        <div class="auth__form-register">
                            <p>Have no registration ?</p>
                            <a href="{{ route('auth.register') }}">Register</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="auth__car">
                <div class="auth__car-container">
                    @php
                        $regOK =
                            session('registration_success') ||
                            session('status') === 'registered' ||
                            session('success') === 'registered';
                    @endphp
                    <div class="auth__car-block {{ $regOK ? 'is-success' : '' }}">
                        <div class="auth__car-mainmessage">
                            <img src="{{ asset('images/auth/class.png') }}" alt="Class Capsuleppf">
                            <p>{{ $regOK ? 'Your registration request has been accepted!' : 'New bonus program for partners' }}
                            </p>
                        </div>
                        <div class="auth__car-text">
                            @if ($regOK)
                                <p>Your registration request has been accepted and sent to the administrator for review.</p>
                                <p style="font-size:19px; line-height:27px;">Once your registration is confirmed, you will
                                    receive a notification via email.</p>
                            @else
                                <p>Earn bonuses for every product you purchase. Exchange your bonuses for useful gifts to
                                    use with your product.</p>
                                <p>No extra charges!</p>
                            @endif
                        </div>
                    </div>
                    <div class="auth__car-image">
                        <img src="{{ asset('images/auth/car.png') }}" alt="Capsuleppf Back">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const emailBlk = document.getElementById('emailBlock');
            const passBlk = document.getElementById('passBlock');
            const eyeBtn = passBlk.querySelector('.input-eye');

            // Показ/скрытие пароля
            eyeBtn.addEventListener('click', () => {
                const isShown = password.type === 'text';
                password.type = isShown ? 'password' : 'text';
                eyeBtn.classList.toggle('is-on', !isShown);
                eyeBtn.setAttribute('aria-pressed', String(!isShown));
            });

            // Клиентская подсветка (не мешает серверной ошибке)
            let triedSubmit = false;
            const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            function validateAll() {
                const emailVal = email.value.trim();
                const passVal = password.value;
                const emailOk = emailVal === '' ? false : emailRe.test(emailVal);
                const passOk = !(emailVal !== '' && passVal === '');

                if (triedSubmit) {
                    emailBlk.classList.toggle('has-error', !emailOk);
                    passBlk.classList.toggle('has-error', !passOk);
                }
                return emailOk && passOk;
            }

            function maybeRevalidate() {
                if (triedSubmit) validateAll();
            }

            email.addEventListener('input', maybeRevalidate);
            password.addEventListener('input', maybeRevalidate);

            form.addEventListener('submit', (e) => {
                triedSubmit = true;
                const ok = validateAll();
                if (!ok) e.preventDefault();
            });
        });
    </script>
@endsection
