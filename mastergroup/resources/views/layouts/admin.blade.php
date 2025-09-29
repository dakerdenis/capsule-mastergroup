<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>@yield('title','Admin')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="admin">
<div class="admin-shell" data-admin-shell>
  {{-- SIDEBAR --}}
  <aside class="admin-sidebar" id="adminSidebar" aria-label="Admin Navigation">
    <div class="admin-brand">
      <a href="{{ route('admin.dashboard') }}" class="brand-link">CAPSULE • Admin</a>
    </div>

    <nav class="admin-nav" role="navigation">
      <a class="admin-nav__link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}"
         href="{{ route('admin.dashboard') }}">Dashboard</a>

      <a class="admin-nav__link {{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}"
         href="{{ route('admin.users.index') }}">Users</a>

      <a class="admin-nav__link {{ request()->routeIs('admin.categories.*') ? 'is-active' : '' }}"
         href="{{ route('admin.categories.index') }}">Categories</a>

      <a class="admin-nav__link {{ request()->routeIs('admin.products.*') ? 'is-active' : '' }}"
         href="{{ route('admin.products.index') }}">Products</a>

      <a class="admin-nav__link {{ request()->routeIs('admin.orders.*') ? 'is-active' : '' }}"
         href="{{ route('admin.orders.index') }}">Orders</a>
    </nav>

    <footer class="admin-sidebar__footer">
      <small>&copy; {{ date('Y') }}</small>
    </footer>
  </aside>

  {{-- MAIN --}}
  <main class="admin-main">
    <header class="admin-topbar">
      <button class="sidebar-toggle" type="button"
              aria-label="Toggle sidebar"
              aria-controls="adminSidebar"
              aria-expanded="false"
              data-sidebar-toggle>
        <!-- простая иконка-бургер -->
        <svg width="22" height="22" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M4 6h16M4 12h16M4 18h16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
      </button>

      <h1 class="admin-page-title">@yield('page_title', $title ?? '')</h1>

      <div class="admin-topbar__right">
        <span class="admin-name">{{ auth('admin')->user()->name ?? 'Admin' }}</span>
        <form action="{{ route('admin.logout') }}" method="POST" class="inline">
          @csrf
          <button type="submit" class="btn btn--logout">Log out</button>
        </form>
      </div>
    </header>
    <div class="admin__content__wrapper">
    <section class="admin-content">
      @yield('content')
    </section>
    </div>


  </main>
</div>

<script src="{{ asset('js/admin.js') }}"></script>
@stack('scripts')
</body>
</html>
