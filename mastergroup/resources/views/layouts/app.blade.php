{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'App')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="https://capsuleppf.com/public/images/casule_favicon.png">
    @stack('page-styles')

</head>

<body>
    <div class="main__wrapper">

        <header class="topbar">
            <div class="topbar_logo_bonuses">
                <div class="brand">
                    <a href="{{ route('home') }}"> {{-- was: route('account.dashboard') --}}
                        <img src="{{ asset('images/common/capsule_logo-white.png') }}" alt="CAPSULE"
                            class="brand__logo">
                    </a>
                </div>
                <div class="topbar__bonuses"> <span class="muted">CPS Bonuses:</span>
                    <p>{{ auth()->user()->cps_total ?? 0 }}</p> <span class="topbar-tooltip" tabindex="0"
                        aria-describedby="cpsTip"> <img src="{{ asset('images/app/bonus-info.svg') }}" alt=""
                            class="topbar-tooltip__icon"> <span id="cpsTip" role="tooltip"
                            class="topbar-tooltip__bubble"> Your CPS bonuses can be used to redeem gifts in the Catalog.
                            Add items to cart and spend CPS at checkout. </span> </span>
                </div>
            </div>
            <!-----BASKET----->
            <a href="{{ route('cart.index') }}" class="basket_mobile">
                <span class="cart-badge cart-badge-mobile js-cart-count">0</span>
                <img src="{{ asset('images/common/card.png') }}" alt="">
            </a>
            <!-- BURGER (mobile only) -->
            <div class="three col">
                <div class="hamburger" id="hamburger-6" aria-label="Open menu" role="button" tabindex="0">
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                </div>
            </div>


            <div class="topbar__profile">
                <a href="{{ route('account') }}" class="topbar__profile-image">
                    <img class="avatar"
                        src="{{ auth()->user()->profile_photo_path ? asset('storage/' . auth()->user()->profile_photo_path) : asset('images/avatar-default.png') }}"
                        alt="Profile">
                </a>

                <form action="{{ route('auth.logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="btn btn--logout">LOG OUT</button>
                </form>
            </div>
        </header>

        <aside class="sidebar">
            <!-- MOBILE ACCOUNT (visible only on mobile) -->
            <div class="mobile-menu__account">
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

            <nav class="nav">
                <a class="nav__link {{ request()->routeIs('home') ? 'is-active' : '' }}"
                    href="{{ route('home') }}">Homepage</a>
                <a class="nav__link {{ request()->routeIs('catalog.*') ? 'is-active' : '' }}"
                    href="{{ route('catalog.index') }}">Catalogue</a>
                <a class="nav__link {{ request()->routeIs('cart.*') ? 'is-active' : '' }}"
                    href="{{ route('cart.index') }}">
                    <p>My Cart</p> <span class="cart-badge js-cart-count">0</span>
                </a>
                <a class="nav__link {{ request()->routeIs('orders.*') ? 'is-active' : '' }}"
                    href="{{ route('orders.index') }}">My Orders</a>
                <a class="nav__link {{ request()->routeIs('account') ? 'is-active' : '' }}"
                    href="{{ route('account') }}">Account</a>
            </nav>
            <div class="sidebar_car">
                <img src="{{ asset('images/app/car-left.png') }}" alt="">
            </div>
            <footer class="sidebar__footer">
                <p>Copyright {{ date('Y') }}</p>
            </footer>
        </aside>
        <div class="offcanvas-overlay" aria-hidden="true"></div>
        <main class="main">
            @yield('content')
        </main>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>




    <script>
        (function() {
            function setCartCount(count) {
                const n = Math.max(0, Number(count) || 0);
                document.querySelectorAll('.js-cart-count').forEach(el => {
                    el.textContent = String(n);
                });
                try {
                    localStorage.setItem('cartCount', String(n));
                } catch (e) {}
            }
            window.setCartCount = setCartCount;

            window.addEventListener('storage', (e) => {
                if (e.key === 'cartCount') setCartCount(e.newValue);
            });
        })();
    </script>



    @stack('scripts')
    @stack('page-scripts')
    <script>
        (function() {
            const body = document.body;
            const burger = document.getElementById('hamburger-6'); // << заменили селектор
            const overlay = document.querySelector('.offcanvas-overlay');
            const sidebar = document.querySelector('.sidebar');

            function toggleMenu(forceState) {
                const willOpen = typeof forceState === 'boolean' ? forceState : !body.classList.contains('menu-open');
                body.classList.toggle('menu-open', willOpen);
                if (burger) burger.classList.toggle('is-activa', willOpen); // синхронизируем анимацию
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
    <script src="{{ asset('js/cart.js') }}"></script>


</body>

</html>
