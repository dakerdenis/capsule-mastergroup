{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Admin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- можно оставить общий app.css если там стили топбара/сайдбара; при желании подключи admin.css дополнительно --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/admin.css') }}"> --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('page-styles')
</head>

<body class="admin">
    <div class="main__wrapper">

        {{-- === TOPBAR === --}}
        <header class="topbar">
            <div class="topbar_logo_bonuses"> {{-- блок переспользуем как контейнер для лого --}}
                <div class="brand">
                    <a href="{{ route('admin.dashboard') }}" class="brand__logo-link">
                        <img src="{{ asset('images/common/capsule_logo-white.png') }}" alt="CAPSULE • Admin"
                            class="brand__logo">
                    </a>
                </div>
            </div>

            {{-- Бургер (mobile) --}}
            <div class="three col">
                <div class="hamburger" id="hamburger-6" aria-label="Open menu" role="button" tabindex="0"
                    aria-controls="adminSidebar" aria-expanded="false">
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                </div>
            </div>

            {{-- Профиль админа + logout --}}
            <div class="topbar__profile">


                <form action="{{ route('admin.logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="btn btn--logout">LOG OUT</button>
                </form>
            </div>
        </header>

        {{-- === SIDEBAR (оффканвас на мобиле) === --}}
        <aside class="sidebar" id="adminSidebar" aria-label="Admin Navigation">
            {{-- Аккаунт (виден на мобиле) --}}
            <div class="mobile-menu__account">
                <div class="mobile-menu__name" style="margin-top:8px;">
                    <strong>{{ auth('admin')->user()->name ?? 'Admin' }}</strong>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST" class="logout-form" style="margin-top:8px;">
                    @csrf
                    <button type="submit" class="btn btn--logout">LOG OUT</button>
                </form>
            </div>

            <nav class="nav" role="navigation">
                <a class="nav__link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}"
                    href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="nav__link {{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}"
                    href="{{ route('admin.users.index') }}">Users</a>
                <a class="nav__link {{ request()->routeIs('admin.categories.*') ? 'is-active' : '' }}"
                    href="{{ route('admin.categories.index') }}">Categories</a>
                <a class="nav__link {{ request()->routeIs('admin.products.*') ? 'is-active' : '' }}"
                    href="{{ route('admin.products.index') }}">Products</a>
                <a class="nav__link {{ request()->routeIs('admin.orders.*') ? 'is-active' : '' }}"
                    href="{{ route('admin.orders.index') }}">Orders</a>
                <a class="nav__link {{ request()->routeIs('admin.codes.*') ? 'is-active' : '' }}"
                    href="{{ route('admin.codes.index') }}">Codes</a>
            </nav>


            {{-- Декоративный блок можно убрать/заменить при необходимости --}}
            <div class="sidebar_car" aria-hidden="true">
                <img src="{{ asset('images/app/car-left.png') }}" alt="" />
            </div>

            <footer class="sidebar__footer">
                <p>&copy; {{ date('Y') }} • Admin</p>
            </footer>
        </aside>

        <div class="offcanvas-overlay" aria-hidden="true"></div>

        {{-- === MAIN === --}}
        <main class="main">
            {{-- Заголовок страницы админки (как было в admin-шаблоне) --}}
            @hasSection('page_title')
            @endif

            @yield('content')
        </main>
    </div>

    {{-- Общий app.js (если нужен), затем специфичный админский --}}
    <script src="{{ asset('js/app.js') }}"></script>

    {{-- Убрано всё, что связано с корзиной:
         - нет js-cart-count;
         - нет сохранения cartCount в localStorage;
         - нет иконок корзины и бейджей. --}}

    <script>
        (function() {
            const body = document.body;
            const burger = document.getElementById('hamburger-6');
            const overlay = document.querySelector('.offcanvas-overlay');
            const sidebar = document.getElementById('adminSidebar');

            function toggleMenu(forceState) {
                const willOpen = typeof forceState === 'boolean' ?
                    forceState :
                    !body.classList.contains('menu-open');

                body.classList.toggle('menu-open', willOpen);
                if (burger) {
                    burger.classList.toggle('is-activa', willOpen);
                    burger.setAttribute('aria-expanded', String(willOpen));
                }
            }

            if (burger) {
                burger.addEventListener('click', () => toggleMenu());
                burger.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        toggleMenu();
                    }
                });
            }
            if (overlay) overlay.addEventListener('click', () => toggleMenu(false));

            window.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') toggleMenu(false);
            });

            if (sidebar) {
                sidebar.addEventListener('click', (e) => {
                    const a = e.target.closest('a');
                    if (a && window.matchMedia('(max-width: 768px)').matches) toggleMenu(false);
                });
            }
        })();
    </script>

    @stack('scripts')
    @stack('page-scripts')
</body>

</html>
