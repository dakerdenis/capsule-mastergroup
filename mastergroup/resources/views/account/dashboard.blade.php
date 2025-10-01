@extends('layouts.app')
@section('title', $title ?? 'Homepage')
@section('page_title', 'Welcome to Mastegroup Market')

@push('page-styles')
    <link rel="stylesheet"
        href="{{ asset('css/market/account.css') }}?v={{ filemtime(public_path('css/market/dashboard.css')) }}">
@endpush


@section('content')
    <div class="account_wrapper">
        <!----INFORMATION ABOUNT ACCOUNT---->
        <div class="account__info">
            <div class="acoount__info-desc">
                Personal information
            </div>
            <div class="acoount__info-container">
                <div class="account__info-wrapper">
                    <div class="account__profile-foto">
                        <img src="{{ auth()->user()->profile_photo_path ? asset('storage/' . auth()->user()->profile_photo_path) : asset('images/avatar-default.png') }}"
                            alt="">
                    </div>
                    <div class="account__profile-input account__profile-name">
                        <p>Jane Doo</p>
                    </div>
                    <div class="account__profile-birthname">
                        <div class="account__profile-input account__profile-birth">
                            <p>09.09.1999</p>
                        </div>
                        <div class="account__profile-input account__profile-gender">
                            <p>Male</p>
                        </div>
                    </div>
                    <div class="account__profile-input account__profile-email">
                        <p>dakerdenis@gmail.com</p>
                    </div>
                    <div class="account__profile-input account__profile-number">
                        <p>+994 50 750 69 01</p>
                    </div>
                    <div class="account__profile-input account__profile-country">
                        <p>Azerbaijan</p>
                    </div>

                    <div class="account__profile-password">
                        <button>CHANGE PASSWORD</button>
                    </div>
                </div>
            </div>
        </div>

        <!----BOnuses colllection part---->
        <div class="account__bonuses_collection">
            <div class="account__bonuses-desc_collect">
                <p>Bonuses collection history</p>

                <form action="#">
                    <input type="text" placeholder="Enter the product code">
                    <button>REQUEST CPS</button>
                </form>
            </div>

            <div class="account_bonuses__wrapper">
                <div class="account_bonuses-table">
                    <p class="code">
                        PRODUCT CODE
                    </p>
                    <p class="date">
                        ACTIVATION DATE
                    </p>
                    <p class="amount">
                        CPS
                    </p>
                    <p class="status">
                        STATUS
                    </p>
                </div>
                <div class="account_bonuses-element">
                    <p class="code">
                        #242332228
                    </p>
                    <p class="date">
                        08/20/22
                    </p>
                    <p class="amount">
                        CPS 499
                    </p>
                    <p class="status">
                        COMPLETED
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
