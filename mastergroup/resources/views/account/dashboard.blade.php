@extends('layouts.app')
@section('title', $title ?? 'Homepage')
@section('page_title', 'Welcome to Mastegroup Market')

@section('content')
    <p class="lead">
        Earn bonuses for every product you purchase. Exchange your bonuses for useful gifts to use with your product.
    </p>

    {{-- сюда вставишь свои карточки товаров, форму вопроса и пр. --}}
    <div class="grid">
        {{-- placeholder блоки --}}
        <div class="card">…</div>
        <div class="card">…</div>
        <div class="card">…</div>
        <div class="card">…</div>
    </div>
@endsection
