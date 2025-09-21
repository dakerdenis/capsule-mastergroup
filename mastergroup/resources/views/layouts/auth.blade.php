<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>@yield('title', $title ?? 'Auth')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  {{-- Подключи свой css --}}
</head>
<body class="auth">
  <header class="auth-header">
    <a href="/" class="logo">MasterGroup</a>
  </header>

  <main class="auth-container">
    @include('partials.alerts')
    @yield('content')
  </main>

  <footer class="auth-footer">
    <small>&copy; {{ date('Y') }} MasterGroup</small>
  </footer>

  {{-- Подключи свой js --}}
</body>
</html>
