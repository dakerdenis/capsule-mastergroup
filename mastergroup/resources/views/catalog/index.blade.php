@extends('layouts.app')
@section('title', $title ?? 'Catalogue')
@section('page_title', 'Catalogue')

@section('content')
    {{-- фильтры/поиск сверху --}}
    <form class="catalog-filters" method="GET" action="{{ route('catalog.index') }}">
        <input type="text" name="q" placeholder="Search products…"
               value="{{ request('q') }}">
        <button type="submit">Search</button>
    </form>

    {{-- список товаров --}}
    <div class="grid">
        {{-- рендер продуктов позже --}}
        <div class="card">Product placeholder</div>
    </div>
@endsection
