@extends('layouts.auth')

@section('title', $title ?? 'Create account')
@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}?v={{ filemtime(public_path('css/auth/login.css')) }}">
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
                        <a href="#" class="auth__form-logo">
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
                                    register your account
                                </div>
                                <div class="auth-line"></div>
                            </div>
                        </div>
                        <!-----Registration Options----->
                        <div class="registraion__options__container">
                            <!--REGISTER AS USER----->
                            <div class="regitration__option">
                                <div class="registration_name">
                                    Register as an Individual
                                </div>

                                <div class="registration_image">
                                    <img src="{{ asset('images/auth/reg-user.svg') }}" alt="Capsuleppf Back">
                                </div>
                                <div class="registration_text">
                                    Your registration request has been accepted and sent to the administrator for review.
                                </div>
                            </div>
                            <!--REGISTER AS COMPANY----->
                            <div class="regitration__option">
                                <div class="registration_name">
                                    Register as a Company
                                </div>
                                <div class="registration_image">
                                    <img src="{{ asset('images/auth/reg-company.svg') }}" alt="Capsuleppf Back">
                                </div>
                                <div class="registration_text">
                                    Your registration request has been accepted and sent to the administrator for review.
                                </div>
                            </div>
                        </div>

                        <!---REGISTRATION NEXT---->
                        <div class="registration__next">
                            <a href="#">
                                NEXT
                            </a>
                        </div>

                    </div>
                </div>
            </div>

            <!------block and car--->
            <div class="auth__car">
                <div class="auth__car-container">
                    <!---ERRORS AND TEXT BLOCK---->
                    <div class="auth__car-block">
                        <!---text witbh greeen background ---->
                        <div class="auth__car-mainmessage">
                            <img src="{{ asset('images/auth/class.png') }}" alt="Class Capsuleppf">

                            <p>New bonus program for partners</p>
                        </div>

                        <!------->
                        <div class="auth__car-text">
                            <p>Earn bonuses for every product you purchase. Exchange your bonuses for useful gifts to use
                                with your product. </p>
                            <p>No extra charges!</p>
                        </div>

                    </div>
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
