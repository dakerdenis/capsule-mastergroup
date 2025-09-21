<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>@yield('title', 'App')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="app">
  <nav class="topbar">
    <a href="{{ route('catalog.index') }}">Catalog</a>
    <a href="{{ route('cart.index') }}">Cart</a>
    <a href="{{ route('orders.index') }}">Orders</a>
    <form action="{{ route('auth.logout') }}" method="POST" style="display:inline">
      @csrf
      <button type="submit">Sign out</button>
    </form>
  </nav>

  <main class="container">
    @include('partials.alerts')
    @yield('content')
  </main>
</body>
</html>
