<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'App')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- сюда подключишь свои css/js --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="app">
<div class="app-shell">

    {{-- LEFT SIDEBAR --}}
    <aside class="sidebar">
        <div class="brand">
            <a href="{{ route('account.dashboard') }}">
                <img src="{{ asset('images/logo.svg') }}" alt="CAPSULE" class="brand__logo">
            </a>
        </div>

        <nav class="nav">
            <a class="nav__link {{ request()->routeIs('account.dashboard') ? 'is-active' : '' }}"
               href="{{ route('account.dashboard') }}">Homepage</a>

            <a class="nav__link {{ request()->routeIs('catalog.*') ? 'is-active' : '' }}"
               href="{{ route('catalog.index') }}">Catalogue</a>

            <a class="nav__link {{ request()->routeIs('cart.*') ? 'is-active' : '' }}"
               href="{{ route('cart.index') }}">My Cart</a>

            <a class="nav__link {{ request()->routeIs('orders.*') ? 'is-active' : '' }}"
               href="{{ route('orders.index') }}">My Orders</a>
        </nav>

        <footer class="sidebar__footer">
            <small>Copyright {{ date('Y') }}</small>
        </footer>
    </aside>

    {{-- MAIN AREA --}}
    <main class="main">
        {{-- TOP BAR --}}
        <header class="topbar">
            <div class="topbar__bonuses">
                <span class="muted">CPS Bonuses:</span>
                <strong>{{ auth()->user()->bonuses ?? 0 }}</strong>
                <span class="i" title="Bonuses you can spend in catalogue">i</span>
            </div>

            <div class="topbar__profile">
                <img class="avatar"
                     src="{{ auth()->user()->profile_photo_path ? asset('storage/'.auth()->user()->profile_photo_path) : asset('images/avatar-default.png') }}"
                     alt="Profile">
                <form action="{{ route('auth.logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="btn btn--logout">LOG OUT</button>
                </form>
            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <section class="content">
            <h1 class="page-title">@yield('page_title', $title ?? '')</h1>
            @yield('content')
        </section>
    </main>
</div>

<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
