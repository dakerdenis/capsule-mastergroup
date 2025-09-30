@extends('layouts.app')
@section('title', $title ?? 'Catalogue')
@section('page_title', 'Catalogue')


@push('page-styles')
    <link rel="stylesheet"
        href="{{ asset('css/market/catalog.css') }}?v={{ filemtime(public_path('css/market/catalog.css')) }}">
@endpush



@section('content')
    <div class="catalog__wrapper">
        <div class="catalog__filter">
            <div class="catalog_filter__element"></div>
            <div class="catalog_filter__element"></div>
            <div class="catalog_filter__element"></div>
            <div class="catalog_filter__element">
                <input type="text" placeholder="Search">
            </div>
            <div class="catalog_filter__clear">
                <button>clear</button>
            </div>
        </div>


        <div class="catalog__content">

        </div>
    </div>
@endsection
