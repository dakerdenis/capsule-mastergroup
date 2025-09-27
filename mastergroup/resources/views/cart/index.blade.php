@extends('layouts.app')
@section('title', $title ?? 'My Cart')
@section('page_title', 'My Cart')

@section('content')
    <div class="cart-list">
        {{-- элементы корзины --}}
        <p>Your cart is empty.</p>
    </div>
@endsection
