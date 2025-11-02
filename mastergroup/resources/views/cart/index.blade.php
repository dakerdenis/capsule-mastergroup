@extends('layouts.app')
@section('title', $title ?? 'My Cart')
@section('page_title', 'My Cart')

@push('page-styles')
    <link rel="stylesheet"
        href="{{ asset('css/market/catalog.css') }}?v={{ filemtime(public_path('css/market/catalog.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/bin/style.css') }}?v={{ filemtime(public_path('css/market/catalog.css')) }}">
@endpush

@section('content')
    <div id="cartPage" data-user-cps="{{ (int) $user_cps }}">
        <div class="cart-list">
            <div class="cart__elements" id="cartItems"></div>

            <div class="cart__selected">
                <div class="cart__selected-name">Selected Items</div>
                <div class="selected__items__wrapper" id="selectedItems"></div>

                <div class="cart__total">
                    <div class="cart__total__bin">
                        <div class="cart__total__bin-element">
                            <p>Your CPS Bonuses:</p>
                            <span id="cpsUser">0 CPS</span>
                        </div>
                        <div class="cart__total__bin-element">
                            <p>Items price:</p>
                            <span id="cpsSelected">0 CPS</span>
                        </div>
                        <div class="cart__total__bin-element">
                            <p>Your CPS Bonuses</p>
                            <span id="cpsLeft">0 CPS</span>
                        </div>
                    </div>

                    <div class="cart__total__button">
                        <!-- Сообщение об ошибке баланса -->
                        <div id="cpsError" class="cart__total__notice" hidden aria-live="polite">
                            Not enough CPS to place the order.
                        </div>
                        <button id="btnPlaceOrder" disabled>PLACE ORDER</button>
                    </div>


                </div>
            </div>
        </div>
    </div>



    {{-- MODAL: confirm remove (у тебя уже есть) --}}
    <div id="confirmModal" class="m-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="mTitle">
        <div class="m-overlay" data-m-close></div>
        <div class="m-dialog m-dialog-confirm" role="document">
            <p class="m-text m-text-confirm">Are you sure you want to remove this item? This action cannot be undone.</p>
            <div class="m-actions">
                <button type="button" class="m-btn m-btn--danger" id="mConfirm">Yes</button>
                <button type="button" class="m-btn" data-m-close>Cancel</button>
            </div>
        </div>
    </div>

    {{-- MODAL: confirm order (НОВОЕ) --}}
    <div id="confirmOrderModal" class="m-modal" aria-hidden="true" role="dialog" aria-modal="true"
        aria-labelledby="oTitle">
        <div class="m-overlay" data-o-close></div>
        <div class="m-dialog m-dialog-confirm" role="document">
            <p class="m-text m-text-confirm">Do you confirm you want to place this order and spend your CPS?</p>
            <div class="m-actions">
                <button type="button" class="m-btn m-btn--danger" id="oConfirm">Yes, place order</button>
                <button type="button" class="m-btn" data-o-close>Cancel</button>
            </div>
        </div>
    </div>

    {{-- MODAL: order placed (SUCCESS) --}}
    <div id="orderSuccessModal" class="m-modal" aria-hidden="true" role="dialog" aria-modal="true"
        aria-labelledby="sTitle">
        <div class="m-overlay" data-s-close></div>
        <div class="m-dialog" role="document">
            <h3 id="sTitle" class="m-title">
                <img src="{{ asset('images/common/thum.png') }}" alt="">
                <p>Your order has been successfully placed!</p>
            </h3>
            <p class="m-text">
                Your order <strong id="orderNumberText"></strong> has been received.
            </p>
            <p class="m-text">
                Our regional manager will contact you shortly with all the details regarding the receipt of your ordered
                items.
            </p>
            <p class="m-text">
                In the meantime, you can track your order status in the "Orders" tab.
            </p>
            <div class="m-actions">
                <button type="button" class="m-btn m-btn-confirm" id="sOk">
                    <img src="{{ asset('images/common/close.svg') }}" alt="">
                </button>
            </div>
        </div>
    </div>


@endsection

@push('page-scripts')
    <script src="{{ asset('js/cart-page.js') }}"></script>
@endpush
