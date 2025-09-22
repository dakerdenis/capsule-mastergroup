{{-- resources/views/auth/forms/login.blade.php --}}
<form action="{{ route('auth.login.submit') }}" method="post" class="auth__form-form" novalidate>
  @csrf

  <div class="form-block">
    <input type="email" name="email" placeholder="Email" required autocomplete="username">
    @error('email')<div class="form-error">{{ $message }}</div>@enderror
  </div>

  <div class="form-block">
    <input type="password" name="password" placeholder="Password" required autocomplete="current-password">
    @error('password')<div class="form-error">{{ $message }}</div>@enderror
  </div>

  <div class="form-forgot">
    <a href="{{ route('password.request') }}">Forgot password?</a>
  </div>

  <div class="form-button">
    <button type="submit"><p>LOG IN</p></button>
  </div>
</form>
