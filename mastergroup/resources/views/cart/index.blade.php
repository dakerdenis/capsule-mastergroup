@extends('layouts.app')
@section('title', $title ?? 'My Cart')
@section('page_title', 'My Cart')
@push('page-styles')
    <link rel="stylesheet"
        href="{{ asset('css/market/catalog.css') }}?v={{ filemtime(public_path('css/market/catalog.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/bin/style.css') }}?v={{ filemtime(public_path('css/market/catalog.css')) }}">
@endpush
@section('content')
    <div class="cart-list">
        <div class="cart__elements">
            <!---Element----->
            <div class="cart__element">
                <div class="cart__element-image">
                    <img src="{{ 'images/catalog/catalog_placeholder2.png' }}" alt="" srcset="">
                </div>
                <div class="cart__element-desc">
                    <div class="cart__element-desc-container">
                        <div class="cart__element-name">
                            Name of the product ame of the product vame of the
                        </div>
                        <div class="cart__element-code">
                            Black - UT894 X7
                        </div>

                    </div>
                    <div class="cart__element__quantity">
                        <button>
                            <img src="{{ 'images/catalog/minus.svg' }}" alt="">
                        </button>
                        <span>10</span>
                        <button>
                            <img src="{{ 'images/catalog/plus.svg' }}" alt="">
                        </button>
                    </div>
                </div>
                <div class="cart__element-price">
                    CPS 50
                </div>
                <div class="cart__element-select">
                    <button class="remove_item">
                        <img src="{{ 'images/common/mdi_trash.png' }}" alt="">
                    </button>
                    <button class="selected_element">

                    </button>

                </div>
            </div>
            <!---Element----->

            <!---Element----->
            <div class="cart__element">
                <div class="cart__element-image">
                    <img src="{{ 'images/catalog/catalog_placeholder2.png' }}" alt="" srcset="">
                </div>
                <div class="cart__element-desc">
                    <div class="cart__element-desc-container">
                        <div class="cart__element-name">
                            Name of the product ame of the product vame of the
                        </div>
                        <div class="cart__element-code">
                            Black - UT894 X7
                        </div>

                    </div>
                    <div class="cart__element__quantity">
                        <button>
                            <img src="{{ 'images/catalog/minus.svg' }}" alt="">
                        </button>
                        <span>10</span>
                        <button>
                            <img src="{{ 'images/catalog/plus.svg' }}" alt="">
                        </button>
                    </div>
                </div>
                <div class="cart__element-price">
                    CPS 50
                </div>
                <div class="cart__element-select">
                    <button class="remove_item">
                        <img src="{{ 'images/common/mdi_trash.png' }}" alt="">
                    </button>
                    <button class="selected_element">

                    </button>

                </div>
            </div>
            <!---Element----->
            <!---Element----->
            <div class="cart__element">
                <div class="cart__element-image">
                    <img src="{{ 'images/catalog/catalog_placeholder.png' }}" alt="" srcset="">
                </div>
                <div class="cart__element-desc">
                    <div class="cart__element-desc-container">
                        <div class="cart__element-name">
                            Name of the product ame of the product vame of the
                        </div>
                        <div class="cart__element-code">
                            Black - UT894 X7
                        </div>

                    </div>
                    <div class="cart__element__quantity">
                        <button>
                            <img src="{{ 'images/catalog/minus.svg' }}" alt="">
                        </button>
                        <span>10</span>
                        <button>
                            <img src="{{ 'images/catalog/plus.svg' }}" alt="">
                        </button>
                    </div>
                </div>
                <div class="cart__element-price">
                    CPS 50
                </div>
                <div class="cart__element-select">
                    <button class="remove_item">
                        <img src="{{ 'images/common/mdi_trash.png' }}" alt="">
                    </button>
                    <button class="selected_element">

                    </button>

                </div>
            </div>
            <!---Element----->
            <!---Element----->
            <div class="cart__element">
                <div class="cart__element-image">
                    <img src="{{ 'images/catalog/catalog_placeholder3.png' }}" alt="" srcset="">
                </div>
                <div class="cart__element-desc">
                    <div class="cart__element-desc-container">
                        <div class="cart__element-name">
                            Name of the product ame of the product vame of the
                        </div>
                        <div class="cart__element-code">
                            Black - UT894 X7
                        </div>

                    </div>
                    <div class="cart__element__quantity">
                        <button>
                            <img src="{{ 'images/catalog/minus.svg' }}" alt="">
                        </button>
                        <span>10</span>
                        <button>
                            <img src="{{ 'images/catalog/plus.svg' }}" alt="">
                        </button>
                    </div>
                </div>
                <div class="cart__element-price">
                    CPS 50
                </div>
                <div class="cart__element-select">
                    <button class="remove_item">
                        <img src="{{ 'images/common/mdi_trash.png' }}" alt="">
                    </button>
                    <button class="selected_element">

                    </button>

                </div>
            </div>
            <!---Element----->
            <!---Element----->
            <div class="cart__element">
                <div class="cart__element-image">
                    <img src="{{ 'images/catalog/catalog_placeholder2.png' }}" alt="" srcset="">
                </div>
                <div class="cart__element-desc">
                    <div class="cart__element-desc-container">
                        <div class="cart__element-name">
                            Name of the product ame of the product vame of the
                        </div>
                        <div class="cart__element-code">
                            Black - UT894 X7
                        </div>

                    </div>
                    <div class="cart__element__quantity">
                        <button>
                            <img src="{{ 'images/catalog/minus.svg' }}" alt="">
                        </button>
                        <span>10</span>
                        <button>
                            <img src="{{ 'images/catalog/plus.svg' }}" alt="">
                        </button>
                    </div>
                </div>
                <div class="cart__element-price">
                    CPS 50
                </div>
                <div class="cart__element-select">
                    <button class="remove_item">
                        <img src="{{ 'images/common/mdi_trash.png' }}" alt="">
                    </button>
                    <button class="selected_element">

                    </button>

                </div>
            </div>
            <!---Element----->
        </div>

        <div class="cart__selected">
            <div class="cart__selected-name">Selected Items</div>
            <div class="selected__items__wrapper">
                <!------>
                <div class="selected_bin-element">
                    <div class="selected_bin-element-image">
                        <img src="{{ 'images/catalog/catalog_placeholder.png' }}" alt="">
                    </div>
                    <div class="selected_bin-element-amount">
                        <span>CPS 50</span>
                        <p>x 2</p>
                    </div>
                </div>
                <!------>
                <div class="selected_bin-element">
                    <div class="selected_bin-element-image">
                        <img src="{{ 'images/catalog/catalog_placeholder2.png' }}" alt="">
                    </div>
                    <div class="selected_bin-element-amount">
                        <span>CPS 10</span>
                        <p>x 1</p>
                    </div>
                </div>
                <!------>
                <div class="selected_bin-element">
                    <div class="selected_bin-element-image">
                        <img src="{{ 'images/catalog/catalog_placeholder3.png' }}" alt="">
                    </div>
                    <div class="selected_bin-element-amount">
                        <span>CPS 30</span>
                        <p>x 3</p>
                    </div>
                </div>
                <!------>
                <div class="selected_bin-element">
                    <div class="selected_bin-element-image">
                        <img src="{{ 'images/catalog/catalog_placeholder.png' }}" alt="">
                    </div>
                    <div class="selected_bin-element-amount">
                        <span>CPS 50</span>
                        <p>x 2</p>
                    </div>
                </div>
                <!------>
            </div>

            <div class="cart__total">
                <div class="cart__total__bin">
                    <div class="cart__total__bin-element">
                        <p>Your CPS Bonuses:</p>
                        <span>748 CPS</span>
                    </div>
                    <div class="cart__total__bin-element">
                        <p>Items price:</p>
                        <span>544 CPS</span>
                    </div>

                    <div class="cart__total__bin-element">
                        <p>Your CPS Bonuses</p>
                        <span>304 CPS</span>
                    </div>
                </div>

                <div class="cart__total__button">
                    <button>PLACE ORDER</button>
                </div>
            </div>
        </div>
    </div>
@endsection
