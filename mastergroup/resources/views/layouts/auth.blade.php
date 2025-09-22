<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>@yield('title', $title ?? 'Auth')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{ asset('css/main.css') }}?v={{ filemtime(public_path('css/main.css')) }}">
  <link rel="stylesheet" href="{{ asset('css/auth/base.css') }}?v={{ filemtime(public_path('css/auth/base.css')) }}">
  @stack('page-styles')
</head>
<body class="auth">
  <main class="auth-container">
    @yield('content')
  </main>

  @stack('page-scripts')
</body>
</html>
