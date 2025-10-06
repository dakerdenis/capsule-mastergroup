@extends('layouts.app')
@section('title', $title ?? 'Catalogue')
@section('page_title', 'Catalogue')

@push('page-styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />

    <link rel="stylesheet"
        href="{{ asset('css/market/catalog.css') }}?v={{ filemtime(public_path('css/market/catalog.css')) }}">
    <link rel="stylesheet"
        href="{{ asset('css/market/catalog_add.css') }}?v={{ filemtime(public_path('css/market/catalog_add.css')) }}">
@endpush


@section('content')
    @php use Illuminate\Support\Str; @endphp

    <div class="catalog__wrapper">

        <div class="catalog__filter">
            <div class="catalog_filter__element"></div>
            <div class="catalog_filter__element"></div>
            <div class="catalog_filter__element"></div>

            <div class="catalog_filter__element">
                <img src="{{ asset('images/catalog/search.svg') }}" alt="">
                <input type="text" placeholder="Search" disabled>
            </div>

            <div class="catalog_filter__clear">
                <button disabled>clear</button>
            </div>
        </div>

        <div class="catalog__content">
            @forelse($products as $p)
                @php
                    $photoPath = optional($p->primaryImage)->path ?? optional($p->images->first())->path;
                    if ($photoPath) {
                        $img = Str::startsWith($photoPath, ['http://', 'https://'])
                            ? $photoPath
                            : asset('storage/' . ltrim($photoPath, '/'));
                    } else {
                        $img = asset('images/catalog/catalog_placeholder.png');
                    }
                    $detailUrl = route('catalog.api.product', $p); // JSON endpoint
                @endphp

                <!----element------>
                <div class="catalog__element" data-product="{{ $detailUrl }}" data-name="{{ e($p->name) }}">
                    <div class="catalog__element__wrapper">

                        <div class="catalog__element__bin" title="Remove from cart"
                            style="opacity:.4; pointer-events:none;">
                            <img src="{{ asset('images/catalog/bin.svg') }}" alt="">
                        </div>

                        <div class="catalog__element__image js-open-product" style="cursor:pointer">
                            <img src="{{ $img }}" alt="{{ $p->name }}">
                        </div>

                        <div class="catalog__element__name">
                            <p class="js-open-product" style="cursor:pointer">{{ $p->name }}</p>
                        </div>

                        <div class="catalog__element__type">
                            {{ $p->type ? $p->type . ' — ' : '' }}{{ $p->code }}
                        </div>

                        <div class="catalog__element__amount-price">
                            <div class="catalog__element-amount">
                                <button disabled><img src="{{ asset('images/catalog/minus.svg') }}"
                                        alt=""></button>
                                <span>0</span>
                                <button disabled><img src="{{ asset('images/catalog/plus.svg') }}" alt=""></button>
                            </div>
                            <div class="catalog__element-price">
                               {{ number_format((float) $p->price, 0, '.', ' ') }} CPS
                            </div>
                        </div>
                    </div>
                </div>
                <!----element------>
            @empty
                <div style="padding:20px; color:#97a2b6">No products found.</div>
            @endforelse
        </div>

        {{-- пагинация --}}
        <div style="margin-top:16px">
            {{ $products->links() }}
        </div>
    </div>







   

@push('page-scripts')

<!-- Fancybox -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
@endpush


    @include('partials.product_modal')
@endsection
