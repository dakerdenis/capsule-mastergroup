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

@php use Illuminate\Support\Str; @endphp

<div class="dashboard__catalog">
  @forelse($randomProducts as $p)
    @php
      $photoPath = optional($p->primaryImage)->path ?? optional($p->images->first())->path;
      $img = $photoPath
        ? (Str::startsWith($photoPath, ['http://','https://']) ? $photoPath : asset('storage/'.ltrim($photoPath,'/')))
        : asset('images/catalog/catalog_placeholder.png');
      $detailUrl = route('catalog.api.product', $p);
    @endphp

    <div class="catalog__element"
         data-product="{{ $detailUrl }}"
         data-name="{{ e($p->name) }}"
         data-product-id="{{ $p->id }}">
      <div class="catalog__element__wrapper">

        <div class="catalog__element__bin" title="Remove from cart" style="opacity:.4; pointer-events:none;">
          <img src="{{ asset('images/catalog/bin.svg') }}" alt="">
        </div>

        <div class="catalog__element__image js-open-product" style="cursor:pointer">
          <img src="{{ $img }}" alt="{{ $p->name }}">
        </div>

        <div class="catalog__element__name">
          <p class="js-open-product" style="cursor:pointer">{{ $p->name }}</p>
        </div>

        <div class="catalog__element__type">
          {{ $p->type ? $p->type.' — ' : '' }}{{ $p->code }}
        </div>

        <div class="catalog__element__amount-price">
          <div class="catalog__element-amount">
            <button class="btn-minus" disabled><img src="{{ asset('images/catalog/minus.svg') }}" alt=""></button>
            <span class="qty">0</span>
            <button class="btn-plus" disabled><img src="{{ asset('images/catalog/plus.svg') }}" alt=""></button>
          </div>
          <div class="catalog__element-price">
            {{ number_format((float) $p->price, 0, '.', ' ') }} CPS
          </div>
        </div>
      </div>
    </div>
  @empty
    <div style="padding:12px; color:#97a2b6">No products yet.</div>
  @endforelse
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
    @include('partials.product_modal')
@endsection
