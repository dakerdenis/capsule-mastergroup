@extends('layouts.app')
@section('title', $title ?? 'Homepage')
@section('page_title', 'Welcome to Mastegroup Market')

@push('page-styles')
    <link rel="stylesheet"
        href="{{ asset('css/market/dashboard.css') }}?v={{ filemtime(public_path('css/market/dashboard.css')) }}">
@endpush


@section('content')
    <div class="dashboard_wrapper">
        <div class="dashboard__content">
            <!----dashboard desc----->
            <div class="dashboard__desc">
                <h3>Welcome to Mastegroup Market </h3>
                <p>Earn bonuses for every product you purchase. Exchange your bonuses for useful gifts to use with your
                    product.
                    Earn bonuses for every product you purchase. Exchange your bonuses for useful gifts to use with your
                    product. Earn bonuses for every product you purchase. Exchange your bonuses for useful gifts to use with
                    your product. Earn bonuses for every product you purchase. Exchange your bonuses for useful gifts to use
                    with your product. Earn bonuses for every product you purchase. Exchange your bonuses for useful gifts
                    to use with your product. </p>
            </div>

            <!----DASHBOARD catalog----->
            <div class="dashboard__catalog">
                <div class="catalog__element"></div>
                <div class="catalog__element"></div>
                <div class="catalog__element"></div>
                <div class="catalog__element"></div>
            </div>

            <div class="dashboard__footer">
                <div class="footer__request">
                    <div class="footer__request-question">
                        Any questions? Send request or contact us direcly
                    </div>
                    <div class="footer__request-form">
                        <form action="">
                            <input type="text" placeholder="Leave the message">
                            <button>SEND REQUEST</button>
                        </form>
                    </div>
                </div>

                <div class="footer__contact">
                    <div class="footer__contact-element">
                        <span>Address:</span>
                        <p>91 Main St. New York, USA</p>
                    </div>
                    <div class="footer__contact-element">
                        <span>Phone:</span>
                        <p>+994 44 444 44 44</p>
                    </div>
                    <div class="footer__contact-element">
                        <span>Email:</span>
                        <p>hello@capsule.com</p>
                    </div>
                </div>
            </div>



        </div>


        <div class="dashboard__advertisement">

        </div>
    </div>
@endsection
