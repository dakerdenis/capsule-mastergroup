@extends('layouts.auth')

@section('title', $title ?? 'Create account')
@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}?v={{ filemtime(public_path('css/auth/login.css')) }}">
@endpush
@section('content')
    <style>
        @media only screen and (max-width: 1000px) {
            .auth__form {
                width: 100%;
                height: 100vh;
                min-height: 833px;
            }

            .auth__car {
                display: none;
                visibility: hidden;
            }

            .auth_page-container {
                min-height: 750px;
            }
        }
    </style>
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
                                Welcome to Mastergroup Portal
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
                        {{-- Регистрация: варианты --}}
                        <div class="registraion__options__container">
                            {{-- REGISTER AS USER --}}
                            <a class="regitration__option" href="{{ route('auth.register.user') }}">
                                <div class="registration_name">Register as an Individual</div>
                                <div class="registration_image">
                                    <img src="{{ asset('images/auth/reg-user.svg') }}" alt="Capsuleppf Back">
                                </div>
                                <div class="registration_text">
                                    Your registration request has been accepted and sent to the administrator for review.
                                </div>
                            </a>

                            {{-- REGISTER AS COMPANY --}}
                            <a class="regitration__option" href="{{ route('auth.register.company') }}">
                                <div class="registration_name">Register as a Company</div>
                                <div class="registration_image">
                                    <img src="{{ asset('images/auth/reg-company.svg') }}" alt="Capsuleppf Back">
                                </div>
                                <div class="registration_text">
                                    Your registration request has been accepted and sent to the administrator for review.
                                </div>
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




@endsection
