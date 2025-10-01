@extends('layouts.app')
@section('title', $title ?? 'Homepage')
@section('page_title', 'Welcome to Mastegroup Market')

@push('page-styles')
    {{-- ВАЖНО: у тебя был mismatch в query-параметре: подключаешь account.css, а filemtime берёшь для dashboard.css --}}
    <link rel="stylesheet"
          href="{{ asset('css/market/account.css') }}?v={{ filemtime(public_path('css/market/account.css')) }}">
@endpush

@section('content')
@php
    /** @var \App\Models\User $user */
    $user = auth()->user();

    $genderMap = [
        'male' => 'Male',
        'female' => 'Female',
        'other' => 'Other',
        null => '—',
        '' => '—',
    ];

    $statusLabel = $user->isApproved() ? 'APPROVED' : ($user->isPending() ? 'PENDING' : 'REJECTED');
    $statusClass = $user->isApproved() ? 'badge--approved' : ($user->isPending() ? 'badge--pending' : 'badge--rejected');

    // Красиво форматируем дату рождения, если есть
    $birth = $user->birth_date ? $user->birth_date->format('d.m.Y') : '—';

    // Безопасные фолбэки
    $fullName = $user->full_name ?: ($user->name ?: '—');
    $email = $user->email ?: '—';
    $phone = $user->phone ?: '—';
    $country = $user->country ?: '—';
    $gender = $genderMap[$user->gender ?? null] ?? '—';

    // Баланс бонусов (после миграции ниже)
    $cpsTotal = number_format((int)($user->cps_total ?? 0));
@endphp

<div class="account_wrapper">
    <!----INFORMATION ABOUT ACCOUNT---->
    <div class="account__info">
        <div class="acoount__info-desc">
            Personal information
        </div>
        <div class="acoount__info-container">
            <div class="account__info-wrapper">
                <div class="account__profile-foto">
                    <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('images/avatar-default.png') }}"
                         alt="Avatar">
                </div>

                <div class="account__profile-input account__profile-name">
                    <p>{{ $fullName }}</p>
                </div>

                <div class="account__profile-birthname">
                    <div class="account__profile-input account__profile-birth">
                        <p>{{ $birth }}</p>
                    </div>
                    <div class="account__profile-input account__profile-gender">
                        <p>{{ $gender }}</p>
                    </div>
                </div>

                <div class="account__profile-input account__profile-email">
                    <p>{{ $email }}</p>
                </div>

                <div class="account__profile-input account__profile-number">
                    <p>{{ $phone }}</p>
                </div>

                <div class="account__profile-input account__profile-country">
                    <p>{{ $country }}</p>
                </div>



                {{-- Опционально специфичные поля по типу клиента --}}
                @if($user->isIndividual() && $user->workplace)
                    <div class="account__profile-input account__placeofwork">
                        <p>Workplace: {{ $user->workplace }}</p>
                    </div>
                @endif

                @if($user->isCompany())
                    @if($user->company_logo_path)
                        <div class="account__profile-company_logo">
                            <img src="{{ asset('storage/' . $user->company_logo_path) }}" alt="Company logo">
                        </div>
                    @endif
                    @if($user->instagram)
                    @php $ig = ltrim($user->instagram, '@'); @endphp
                    <div class="account__profile-input">
                        <p>
                            Instagram:
                            <a href="https://instagram.com/{{ $ig }}" target="_blank" rel="noopener">
                                {{ '@' . $ig }}
                            </a>
                        </p>
                    </div>
                @endif
                
                @endif
                <div class="account__profile-password">
                    <button type="button">CHANGE PASSWORD</button>
                </div>
            </div>
        </div>
    </div>

    <!----Bonuses collection part---->
    <div class="account__bonuses_collection">
        <div class="account__bonuses-desc_collect">
            <p>Bonuses collection history</p>
            <div class="account__bonuses-balance">
                <span class="muted">Total CPS:</span>
                <strong>{{ $cpsTotal }}</strong>
            </div>

            <form action="#">
                <input type="text" placeholder="Enter the product code">
                <button type="button">REQUEST CPS</button>
            </form>
        </div>

        {{-- Таблица (как мы переделали ранее) --}}
        <div class="account_bonuses__wrapper">
            <table class="account_bonuses-table">
                <thead>
                    <tr>
                        <th class="code">PRODUCT CODE</th>
                        <th class="date">ACTIVATION DATE</th>
                        <th class="amount">CPS</th>
                        <th class="status">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Пример. Позже сюда подставишь реальные записи из БД/сервиса --}}
                    <tr class="account_bonuses-element">
                        <td class="code">#242332228</td>
                        <td class="date">08/20/22</td>
                        <td class="amount">499</td>
                        <td class="status"><p>COMPLETED</p></td>
                    </tr>
                    <tr class="account_bonuses-element">
                        <td class="code">#242332229</td>
                        <td class="date">08/22/22</td>
                        <td class="amount">199</td>
                        <td class="status"><p>PENDING</p></td>
                    </tr>
                </tbody>
            </table>

            <div class="account_bonuses-navigation">
                <button type="button">Previous</button>
                <span>1-10 of 100</span>
                <button type="button">Next</button>
            </div>
        </div>
    </div>
</div>
@endsection
