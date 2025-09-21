@extends('layouts.auth')

@section('title', $title ?? 'Sign in')

@section('content')
  <h1>Sign in</h1>
  @include('partials/errors')

  <form method="POST" action="{{ route('auth.login') }}" novalidate>
    @csrf
    <div class="form-group">
      <label for="email">Email</label>
      <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
    </div>

    <div class="form-group">
      <label for="password">Password</label>
      <input id="password" name="password" type="password" required>
    </div>

    <div class="form-group">
      <label>
        <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
        Remember me
      </label>
    </div>

    <button type="submit">Sign in</button>
  </form>

  <p class="mt">
    <a href="{{ route('password.request') }}">Forgot your password?</a>
  </p>
  <p>
    <a href="{{ route('auth.register') }}">Create an account</a>
  </p>
@endsection
