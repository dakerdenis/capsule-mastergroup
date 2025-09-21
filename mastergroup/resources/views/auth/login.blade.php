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
                    <!--- logo with link to /--->
                    <a class="auth__form-logo">
                        <img src="{{ asset('images/common/capsule_logo-white.png') }}" alt="Capsuleppf Logo">
                    </a>

                    <!---form block with text and buttons---->
                    <div class="auth__form-name">
                        <h2>
                            Welcome to Mastegroup Portal
                        </h2>
                        <p>Masters marketpace. Use bonuses - get gifts.</p>
                        <!-----Lined text----->
                        <div class="auth__form-lined">
                            <div class="auth-line"></div>
                            <div class="auth__form-desc">
                                enter our credentials
                            </div>
                            <div class="auth-line"></div>
                        </div>
                    </div>
                    <!-----form and input fields----->
                    <form action="" class="auth__form-form">
                        <div class="form-block">
                            <input type="text">
                        </div>
                        <div class="form-block">
                            <input type="password">
                        </div>

                        <div class="form-forgot">
                            <a href="#">Forgot password ?</a>
                        </div>
                        <div class="form-button">
                            <button>
                                <p>LOG IN</p>
                            </button>
                        </div>
                    </form>
                    <!--------->

                    <!----link to registration---->
                    <div class="auth__form-register">
                        <p>Have no regostration ?</p>
                        <a href="#">Register</a>
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
                            <img src="" alt="">

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
                        <img src="" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
