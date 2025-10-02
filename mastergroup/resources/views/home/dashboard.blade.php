@extends('layouts.app')
@section('title', $title ?? 'Homepage')
@section('page_title', 'Welcome to Mastegroup Market')

@push('page-styles')
    <link rel="stylesheet"
        href="{{ asset('css/market/dashboard.css') }}?v={{ filemtime(public_path('css/market/dashboard.css')) }}">
    <link rel="stylesheet"
        href="{{ asset('css/market/catalog.css') }}?v={{ filemtime(public_path('css/market/catalog.css')) }}">
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
                <!----element------>
                <div class="catalog__element">
                    <div class="catalog__element__wrapper">
                        <div class="catalog__element__bin">
                            <img src="{{ asset('images/catalog/bin.svg') }}" alt="" srcset="">
                        </div>
                        <div class="catalog__element__image">

                            <img src="{{ asset('images/catalog/catalog_placeholder.png') }}" alt="" srcset="">
                        </div>
                        <div class="catalog__element__name">
                            <p>Name of the product sadfsf sdfvsd sdfcds </p>
                        </div>
                        <div class="catalog__element__type">
                            Black - UT894 X7
                        </div>
                        <div class="catalog__element__amount-price">
                            <div class="catalog__element-amount">
                                <button>
                                    <img src="{{ asset('images/catalog/minus.svg') }}" alt="" srcset="">
                                </button>                                
                                <span>0</span>
                                <button>
                                    <img src="{{ asset('images/catalog/plus.svg') }}" alt="" srcset="">
                                </button>
                            </div>
                            <div class="catalog__element-price">
                                48.00 AZN
                            </div>
                        </div>
                    </div>
                </div>
                <!----element------>
                <!----element------>
                <div class="catalog__element">
                    <div class="catalog__element__wrapper">
                        <div class="catalog__element__bin">
                            <img src="{{ asset('images/catalog/bin.svg') }}" alt="" srcset="">
                        </div>
                        <div class="catalog__element__image">

                            <img src="{{ asset('images/catalog/catalog_placeholder.png') }}" alt="" srcset="">
                        </div>
                        <div class="catalog__element__name">
                            <p>Name of the product</p>
                        </div>
                        <div class="catalog__element__type">
                            Black - UT894 X7
                        </div>
                        <div class="catalog__element__amount-price">
                            <div class="catalog__element-amount">
                                <button>
                                    <img src="{{ asset('images/catalog/minus.svg') }}" alt="" srcset="">
                                </button>                                
                                <span>0</span>
                                <button>
                                    <img src="{{ asset('images/catalog/plus.svg') }}" alt="" srcset="">
                                </button>
                            </div>
                            <div class="catalog__element-price">
                                48.00 AZN
                            </div>
                        </div>
                    </div>
                </div>
                <!----element------>
                <!----element------>
                <div class="catalog__element">
                    <div class="catalog__element__wrapper">
                        <div class="catalog__element__bin">
                            <img src="{{ asset('images/catalog/bin.svg') }}" alt="" srcset="">
                        </div>
                        <div class="catalog__element__image">

                            <img src="{{ asset('images/catalog/catalog_placeholder.png') }}" alt="" srcset="">
                        </div>
                        <div class="catalog__element__name">
                            <p>Name of the product</p>
                        </div>
                        <div class="catalog__element__type">
                            Black - UT894 X7
                        </div>
                        <div class="catalog__element__amount-price">
                            <div class="catalog__element-amount">
                                <button>
                                    <img src="{{ asset('images/catalog/minus.svg') }}" alt="" srcset="">
                                </button>                                
                                <span>0</span>
                                <button>
                                    <img src="{{ asset('images/catalog/plus.svg') }}" alt="" srcset="">
                                </button>
                            </div>
                            <div class="catalog__element-price">
                                48.00 AZN
                            </div>
                        </div>
                    </div>
                </div>
                <!----element------>
                <!----element------>
                <div class="catalog__element">
                    <div class="catalog__element__wrapper">
                        <div class="catalog__element__bin">
                            <img src="{{ asset('images/catalog/bin.svg') }}" alt="" srcset="">
                        </div>
                        <div class="catalog__element__image">

                            <img src="{{ asset('images/catalog/catalog_placeholder.png') }}" alt="" srcset="">
                        </div>
                        <div class="catalog__element__name">
                            <p>Name of the product</p>
                        </div>
                        <div class="catalog__element__type">
                            Black - UT894 X7
                        </div>
                        <div class="catalog__element__amount-price">
                            <div class="catalog__element-amount">
                                <button>
                                    <img src="{{ asset('images/catalog/minus.svg') }}" alt="" srcset="">
                                </button>                                
                                <span>0</span>
                                <button>
                                    <img src="{{ asset('images/catalog/plus.svg') }}" alt="" srcset="">
                                </button>
                            </div>
                            <div class="catalog__element-price">
                                48.00 AZN
                            </div>
                        </div>
                    </div>
                </div>
                <!----element------>
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
