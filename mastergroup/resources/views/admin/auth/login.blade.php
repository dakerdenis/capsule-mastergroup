@extends('layouts.auth')

@section('title', $title ?? 'Sign in')
@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}?v={{ filemtime(public_path('css/auth/login.css')) }}">
@endpush
@section('content')
    <div class="auth_page-container">
        <div class="auth_page-wrapper">
            <!--------FORM---->
            <div class="auth__form">
                <div class="auth__form-back">
                    <img src="{{ asset('images/auth/back_.png') }}" alt="Capsuleppf Back">
                </div>
                <div class="auth__form-container">
                    <div class="auth__form__wrapper">
                        <!--- logo with link to /--->
                        <a href="{{ route('home') }}" class="auth__form-logo">
                            <img src="{{ asset('images/common/capsule_logo-white.png') }}" alt="Capsuleppf Logo">
                        </a>

                        <!---form block with text and buttons---->
                        <div class="auth__form-name">
                            <h2>
                                Welcome to Mastegroup Portal
                            </h2>

                            <!-----Lined text----->
                            <div class="auth__form-lined">
                                <div class="auth-line"></div>
                                <div class="auth__form-desc">
                                    enter your credentials
                                </div>
                                <div class="auth-line"></div>
                            </div>
                        </div>
                        <!-----form and input fields----->
                        <form action="{{ route('admin.login') }}" method="POST" class="auth__form-form" id="loginForm" novalidate>
                            @csrf
                            <div class="form-block" id="emailBlock">
                                <input id="email" name="email" type="email" placeholder="Login (e-mail)" autocomplete="username" required>
                            </div>
                        
                            <div class="form-block form-block--with-eye" id="passBlock">
                                <input id="password" name="password" type="password" placeholder="Password" autocomplete="current-password" required minlength="8" maxlength="128">
                                <button type="button" class="input-eye" aria-label="Show password" aria-pressed="false">
                                    <span class="eye-icon" aria-hidden="true"></span>
                                </button>
                            </div>
                        
                            @if ($errors->any())
                                <div class="form-errors" style="color:#ff6b6b; font-size:14px; margin-top:8px;">
                                    {{ $errors->first() }}
                                </div>
                            @endif
                        
                            <div class="form-button">
                                <button type="submit">
                                    <p>LOG IN</p>
                                </button>
                            </div>
                        </form>
                        

                    </div>
                </div>
            </div>

            <!------block and car--->
            <div class="auth__car">
                <div class="auth__car-container">

                    <!-------->

                    <!---car image--->
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

            // Флаг: начал ли пользователь попытку отправки
            let triedSubmit = false;

            const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            function validateAll() {
                // правила валидности
                const emailVal = email.value.trim();
                const passVal = password.value;

                const emailOk = emailVal === '' ? false : emailRe.test(
                    emailVal); // пустой email — считаем невалидным на сабмите
                const passOk = !(emailVal !== '' && passVal ===
                    ''); // если email есть, пароль не должен быть пустым

                // Подсветка только после попытки сабмита
                if (triedSubmit) {
                    emailBlk.classList.toggle('has-error', !emailOk);
                    passBlk.classList.toggle('has-error', !passOk);
                }

                return emailOk && passOk;
            }

            // При вводе пересчёт делаем ТОЛЬКО если уже была попытка отправить
            function maybeRevalidate() {
                if (triedSubmit) validateAll();
            }

            email.addEventListener('input', maybeRevalidate);
            password.addEventListener('input', maybeRevalidate);

            form.addEventListener('submit', (e) => {
                triedSubmit = true; // с этого момента можно подсвечивать в инпутах
                const ok = validateAll();
                if (!ok) e.preventDefault(); // блокируем отправку только если не прошло
            });
        });
    </script>


@endsection
