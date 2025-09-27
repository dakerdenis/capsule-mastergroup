@extends('layouts.app')
@section('title', $title ?? 'My Orders')
@section('page_title', 'My Orders')

@section('content')
    <div class="orders">
        {{-- список заказов --}}
        <p>No orders yet.</p>
    </div>
@endsection
