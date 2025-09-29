<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'App')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- сюда подключишь свои css/js --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('page-styles')
</head>

<body>
    <div class="main__wrapper">

        {{-- TOP BAR --}}
        <header class="topbar">
            <div class="topbar_logo_bonuses">
                <div class="brand">
                    <a href="{{ route('account.dashboard') }}">
                        <img src="{{ asset('images/common/capsule_logo-white.png') }}" alt="CAPSULE"
                            class="brand__logo">
                    </a>

                </div>
                <div class="topbar__bonuses">
                    <span class="muted">CPS Bonuses:</span>
                    <p>{{ auth()->user()->bonuses ?? 0 }}</p>
                    <img src="{{ asset('images/app/bonus-info.svg') }}" alt="" class="">
                </div>
            </div>

            <div class="topbar__profile">
                <div class="topbar__profile-image">
                    <img class="avatar"
                        src="{{ auth()->user()->profile_photo_path ? asset('storage/' . auth()->user()->profile_photo_path) : asset('images/avatar-default.png') }}"
                        alt="Profile">
                </div>
                <form action="{{ route('auth.logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="btn btn--logout">LOG OUT</button>
                </form>
            </div>
        </header>
        {{-- LEFT SIDEBAR --}}
        <aside class="sidebar">


            <nav class="nav">
                <a class="nav__link {{ request()->routeIs('home') ? 'is-active' : '' }}"
                    href="{{ route('home') }}">Homepage</a>

                <a class="nav__link {{ request()->routeIs('catalog.*') ? 'is-active' : '' }}"
                    href="{{ route('catalog.index') }}">Catalogue</a>

                <a class="nav__link {{ request()->routeIs('cart.*') ? 'is-active' : '' }}"
                    href="{{ route('cart.index') }}">My Cart</a>

                <a class="nav__link {{ request()->routeIs('orders.*') ? 'is-active' : '' }}"
                    href="{{ route('orders.index') }}">My Orders</a>
            </nav>


            <div class="sidebar_car">
                <img src="{{ asset('images/app/car-left.png') }}" alt="">
            </div>

            <footer class="sidebar__footer">
                <p>Copyright {{ date('Y') }}</p>
            </footer>
        </aside>
        {{-- MAIN AREA --}}
        <main class="main">
            @yield('content')
        </main>
    </div>


    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>

</html>
