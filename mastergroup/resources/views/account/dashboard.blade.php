@extends('layouts.app')
@section('title', $title ?? 'Homepage')
@section('page_title', 'Welcome to Mastegroup Market')

@push('page-styles')
    <link rel="stylesheet"
        href="{{ asset('css/market/dashboard.css') }}?v={{ filemtime(public_path('css/market/dashboard.css')) }}">
@endpush


@section('content')
    ACCOUNT PAGE
@endsection
