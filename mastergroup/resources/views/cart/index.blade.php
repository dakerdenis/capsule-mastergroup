@extends('layouts.app')
@section('title', $title ?? 'My Cart')
@section('page_title', 'My Cart')

@push('page-styles')
<link rel="stylesheet" href="{{ asset('css/market/catalog.css') }}?v={{ filemtime(public_path('css/market/catalog.css')) }}">
<link rel="stylesheet" href="{{ asset('css/bin/style.css') }}?v={{ filemtime(public_path('css/market/catalog.css')) }}">

@endpush

@section('content')
<div id="cartPage" data-user-cps="{{ (int)$user_cps }}">
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
          <button id="btnPlaceOrder" disabled>PLACE ORDER</button>
        </div>
      </div>
    </div>
  </div>
</div>



{{-- MODAL: confirm remove --}}
<div id="confirmModal" class="m-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="mTitle">
  <div class="m-overlay" data-m-close></div>
  <div class="m-dialog" role="document">
    <h3 id="mTitle" class="m-title">Удалить товар из корзины?</h3>
    <p class="m-text">Вы действительно хотите удалить этот товар. Действие нельзя отменить.</p>
    <div class="m-actions">
      <button type="button" class="m-btn m-btn--danger" id="mConfirm">Да, удалить</button>
      <button type="button" class="m-btn" data-m-close>Отмена</button>
    </div>
  </div>
</div>

@endsection

@push('page-scripts')
<script src="{{ asset('js/cart-page.js') }}"></script>
@endpush
