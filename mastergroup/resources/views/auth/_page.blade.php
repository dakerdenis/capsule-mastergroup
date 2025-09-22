{{-- resources/views/auth/_page.blade.php --}}

@php
  // Значения по умолчанию, если что-то не передали
  $tagline           = $tagline           ?? 'Masters marketplace. Use bonuses – get gifts.';
  $linedText         = $linedText         ?? 'enter your credentials';
  $lineClass         = $lineClass         ?? ''; // сюда можно передать модификатор класса для линии
  $formInclude       = $formInclude       ?? null; // путь к partial с формой (обязателен для конкретной страницы)
  $showRegisterBlock = $showRegisterBlock ?? false;
  $registerText      = $registerText      ?? 'Have no registration?';
  $registerUrl       = $registerUrl       ?? route('auth.register'); // переопределите по необходимости

  $carTitle          = $carTitle          ?? 'New bonus program for partners';
  $carParagraphs     = $carParagraphs     ?? [
      'Earn bonuses for every product you purchase. Exchange your bonuses for useful gifts to use with your product.',
      'No extra charges!',
  ];

  // Изображения (можно переопределить снаружи)
  $logoSrc           = $logoSrc           ?? asset('images/common/capsule_logo-white.png');
  $bgImageSrc        = $bgImageSrc        ?? asset('images/auth/back_.png');
  $carIconSrc        = $carIconSrc        ?? asset('images/auth/class.png');
  $carImageSrc       = $carImageSrc       ?? asset('images/auth/car.png');
@endphp

<div class="auth_page-container">
  <div class="auth_page-wrapper">

    {{-- FORM SIDE --}}
    <div class="auth__form">
      <div class="auth__form-back">
        <img src="{{ $bgImageSrc }}" alt="Background">
      </div>

      <div class="auth__form-container">
        <div class="auth__form__wrapper">
          {{-- Логотип --}}
          <a href="{{ url('/') }}" class="auth__form-logo">
            <img src="{{ $logoSrc }}" alt="Logo">
          </a>

          {{-- Заголовки/тексты --}}
          <div class="auth__form-name">
            <h2>Welcome to Mastergroup Portal</h2>
            <p>{{ $tagline }}</p>

            <div class="auth__form-lined">
              <div class="auth-line {{ $lineClass }}"></div>
              <div class="auth__form-desc">{{ $linedText }}</div>
              <div class="auth-line {{ $lineClass }}"></div>
            </div>
          </div>

          {{-- Форма (целиком подменяем через include) --}}
          @if($formInclude)
            @include($formInclude)
          @endif

          {{-- Не на всех экранах нужен: показываем по флагу --}}
          @if($showRegisterBlock)
            <div class="auth__form-register">
              <p>{{ $registerText }}</p>
              <a href="{{ $registerUrl }}">Register</a>
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- RIGHT SIDE / CAR --}}
    <div class="auth__car">
      <div class="auth__car-container">
        <div class="auth__car-block">
          <div class="auth__car-mainmessage">
            <img src="{{ $carIconSrc }}" alt="Icon">
            <p>{{ $carTitle }}</p>
          </div>

          <div class="auth__car-text">
            @foreach($carParagraphs as $p)
              <p>{{ $p }}</p>
            @endforeach
          </div>
        </div>

        <div class="auth__car-image">
          <img src="{{ $carImageSrc }}" alt="Promo image">
        </div>
      </div>
    </div>

  </div>
</div>
