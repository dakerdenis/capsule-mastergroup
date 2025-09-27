@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $title ?? 'Admin Dashboard' }}</h1>
    <p>You are logged in as admin.</p>
</div>
@endsection
