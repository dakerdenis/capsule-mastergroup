@extends('layouts.app')
@section('title', $title ?? 'Catalogue')
@section('page_title', 'Catalogue')


@push('page-styles')
    <link rel="stylesheet"
        href="{{ asset('css/market/catalog.css') }}?v={{ filemtime(public_path('css/market/catalog.css')) }}">
    <link rel="stylesheet"
        href="{{ asset('css/market/catalog_add.css') }}?v={{ filemtime(public_path('css/market/catalog_add.css')) }}">
@endpush



@section('content')
    <div class="catalog__wrapper">
        <div class="catalog__filter">
            <div class="catalog_filter__element"></div>
            <div class="catalog_filter__element"></div>
            <div class="catalog_filter__element"></div>
            <div class="catalog_filter__element">
                <img src="{{ asset('images/catalog/search.svg') }}" alt="" srcset="">
                <input type="text" placeholder="Search">
            </div>
            <div class="catalog_filter__clear">
                <button>clear</button>
            </div>
        </div>


        <div class="catalog__content">
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
                        <p>Name of the product sadfsf sdfvsd sdfcds </p>
                    </div>
                    <div class="catalog__element__type">
                        Black - UT894 X7
                    </div>
                    <div class="catalog__element__amount-price">
                        <div class="catalog__element-amount">

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
                        <p>Name of the product sadfsf sdfvsd sdfcds </p>
                    </div>
                    <div class="catalog__element__type">
                        Black - UT894 X7
                    </div>
                    <div class="catalog__element__amount-price">
                        <div class="catalog__element-amount">

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
                        <p>Name of the product sadfsf sdfvsd sdfcds </p>
                    </div>
                    <div class="catalog__element__type">
                        Black - UT894 X7
                    </div>
                    <div class="catalog__element__amount-price">
                        <div class="catalog__element-amount">

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
                        <p>Name of the product sadfsf sdfvsd sdfcds </p>
                    </div>
                    <div class="catalog__element__type">
                        Black - UT894 X7
                    </div>
                    <div class="catalog__element__amount-price">
                        <div class="catalog__element-amount">

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
                        <p>Name of the product sadfsf sdfvsd sdfcds </p>
                    </div>
                    <div class="catalog__element__type">
                        Black - UT894 X7
                    </div>
                    <div class="catalog__element__amount-price">
                        <div class="catalog__element-amount">

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
                        <p>Name of the product sadfsf sdfvsd sdfcds </p>
                    </div>
                    <div class="catalog__element__type">
                        Black - UT894 X7
                    </div>
                    <div class="catalog__element__amount-price">
                        <div class="catalog__element-amount">

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
                        <p>Name of the product sadfsf sdfvsd sdfcds </p>
                    </div>
                    <div class="catalog__element__type">
                        Black - UT894 X7
                    </div>
                    <div class="catalog__element__amount-price">
                        <div class="catalog__element-amount">

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
                        <p>Name of the product sadfsf sdfvsd sdfcds </p>
                    </div>
                    <div class="catalog__element__type">
                        Black - UT894 X7
                    </div>
                    <div class="catalog__element__amount-price">
                        <div class="catalog__element-amount">

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
                        <p>Name of the product sadfsf sdfvsd sdfcds </p>
                    </div>
                    <div class="catalog__element__type">
                        Black - UT894 X7
                    </div>
                    <div class="catalog__element__amount-price">
                        <div class="catalog__element-amount">

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
                        <p>Name of the product sadfsf sdfvsd sdfcds </p>
                    </div>
                    <div class="catalog__element__type">
                        Black - UT894 X7
                    </div>
                    <div class="catalog__element__amount-price">
                        <div class="catalog__element-amount">

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
                        <p>Name of the product sadfsf sdfvsd sdfcds </p>
                    </div>
                    <div class="catalog__element__type">
                        Black - UT894 X7
                    </div>
                    <div class="catalog__element__amount-price">
                        <div class="catalog__element-amount">

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
                        <p>Name of the product sadfsf sdfvsd sdfcds </p>
                    </div>
                    <div class="catalog__element__type">
                        Black - UT894 X7
                    </div>
                    <div class="catalog__element__amount-price">
                        <div class="catalog__element-amount">

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
                        <p>Name of the product sadfsf sdfvsd sdfcds </p>
                    </div>
                    <div class="catalog__element__type">
                        Black - UT894 X7
                    </div>
                    <div class="catalog__element__amount-price">
                        <div class="catalog__element-amount">

                        </div>
                        <div class="catalog__element-price">
                            48.00 AZN
                        </div>
                    </div>
                </div>
            </div>
            <!----element------>
        </div>
    </div>
@endsection
