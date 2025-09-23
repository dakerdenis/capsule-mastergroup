@extends('layouts.auth')

@section('title', $title ?? 'Create account')
@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/auth/user.css') }}?v={{ filemtime(public_path('css/auth/user.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/pickaday.css') }}">
@endpush
@section('content')
    <div class="auth_page-container">
        <div class="auth_page-wrapper">
            <!--------FORM---->
            <div class="auth__form">
                <div class="auth__form-back">
                    <img src="{{ asset('images/auth/back_.png') }}" alt="Capsuleppf Back">
                </div>
                <div class="auth__form-container">
                    <div class="auth__form__wrapper">
                        <!--- logo with link to /--->
                        <a href="{{ route('home') }}" class="auth__form-logo">
                            <img src="{{ asset('images/common/capsule_logo-white.png') }}" alt="Capsuleppf Logo">
                        </a>


                        <!---form block with text and buttons---->
                        <div class="auth__form-name">
                            <h2>
                                Welcome to Mastegroup Portal
                            </h2>

                            <!-----Lined text----->
                            <div class="auth__form-lined">
                                <div class="auth-line"></div>
                                <div class="auth__form-desc">
                                    register your new account
                                </div>
                                <div class="auth-line"></div>
                            </div>
                        </div>

                        <div class="register_user-form">
                            <form id="regForm" action="">
                                <section class="step" data-step="1">
                                    <!-- FIRST BLOCK -->
                                    <div class="register_user-element">
                                        <div class="file-upload">
                                            <input type="file" class="file-input" accept="image/*" hidden>
                                            <div class="file-dropzone">
                                                <div class="file-icon">
                                                    <img src="{{ asset('images/auth/doc.png') }}" alt="">

                                                </div>
                                                <p>Identity card <span class="req">*</span></p>
                                                <p class="file-text">
                                                    Drag and Drop file here or <span class="choose">Choose file</span>
                                                </p>
                                            </div>
                                            <div class="file-preview" hidden>
                                                <img class="preview-img" src="" alt="Preview">
                                                <button type="button" class="file-remove">✕</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- SECOND BLOCK -->
                                    <div class="register_user-element">
                                        <div class="file-upload">
                                            <input type="file" class="file-input" accept="image/*" hidden>
                                            <div class="file-dropzone">
                                                <div class="file-icon"><img src="{{ asset('images/auth/doc.png') }}"
                                                        alt=""></div>
                                                <p>Profile photo <span class="req">*</span></p>
                                                <p class="file-text">
                                                    Drag and Drop file here or <span class="choose">Choose file</span>
                                                </p>
                                            </div>
                                            <div class="file-preview" hidden>
                                                <img class="preview-img" src="" alt="Preview">
                                                <button type="button" class="file-remove">✕</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="register_user-input">
                                        <input type="text" placeholder="Name and Surname *">
                                    </div>


                                    <div class="register_user-inputs">
                                        <div class="register_user-birth">
                                            <div class="field">
                                                <input type="text" class="field__control field__control--date"
                                                    name="birthdate" placeholder="MM/DD/YYYY" required lang="en"
                                                    data-datepicker inputmode="numeric">
                                            </div>
                                        </div>


                                        <div class="register_user-gender">
                                            <div class="field field--cselect" data-cselect>

                                                <!-- Нативный select: скрыт, но участвует в сабмите -->
                                                <select name="gender" class="cselect-native" required>
                                                    <option value="" selected disabled>Gender*</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                    <option value="other">Other</option>
                                                </select>

                                                <!-- Кнопка-отображение -->
                                                <button type="button" class="cselect-toggle" aria-haspopup="listbox"
                                                    aria-expanded="false">
                                                    <span class="cselect-value">Gender*</span>
                                                    <span class="cselect-arrow" aria-hidden="true"></span>
                                                </button>

                                                <!-- Выпадающий список -->
                                                <ul class="cselect-list" role="listbox" tabindex="-1" hidden>
                                                    <li role="option" data-value="male" class="is-option">Male</li>
                                                    <li role="option" data-value="female" class="is-option">Female</li>
                                                    <li role="option" data-value="other" class="is-option">Other</li>
                                                </ul>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="register_user-input">
                                        <input type="text" placeholder="Instagram account">
                                    </div>


                                    <div class="form__controll">
                                        <a href="{{ route('auth.register') }}">Back</a>
                                        <button type="button" class="form_next is-disabled"
                                            aria-disabled="true">NEXT</button>
                                    </div>
                                </section>

                                {{-- STEP 2 (скрыт по умолчанию) --}}
                                <section class="step is-hidden" data-step="2">
                                    <!-- здесь пиши «другие поля» для второго этапа -->
                                    <div class="register_user-input"><input type="text" name="address"
                                            placeholder="Address *"></div>
                                    <div class="register_user-input"><input type="text" name="city"
                                            placeholder="City *"></div>
                                    <div class="register_user-input"><input type="tel" name="phone"
                                            placeholder="Phone *"></div>

                                    <div class="form__controll">
                                        <button type="button" class="form_back">Back</button>
                                        <button type="submit" class="form_submit">CREATE ACCOUNT</button>
                                    </div>
                                </section>
                            </form>
                        </div>



                    </div>
                </div>
            </div>

            <!------block and car--->
            <div class="auth__car">
                <div class="auth__car-container">
                    <!---ERRORS AND TEXT BLOCK---->
                    <div class="auth__car-block">
                        <!---text witbh greeen background ---->
                        <div class="auth__car-mainmessage">
                            <img src="{{ asset('images/auth/class.png') }}" alt="Class Capsuleppf">

                            <p>New bonus program for partners</p>
                        </div>

                        <!------->
                        <div class="auth__car-text">
                            <p>Earn bonuses for every product you purchase. Exchange your bonuses for useful gifts to use
                                with your product. </p>
                            <p>No extra charges!</p>
                        </div>

                    </div>
                    <!-------->

                    <!---car image--->
                    <div class="auth__car-image">
                        <img src="{{ asset('images/auth/car.png') }}" alt="Capsuleppf Back">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('page-scripts')
        <script
            src="{{ asset('js/register/ui/fileUpload.js') }}?v={{ filemtime(public_path('js/register/ui/fileUpload.js')) }}">
        </script>
        <script src="{{ asset('js/register/ui/cselect.js') }}?v={{ filemtime(public_path('js/register/ui/cselect.js')) }}">
        </script>


        <script src="{{ asset('js/pickaday.js') }}?v={{ filemtime(public_path('js/pickaday.js')) }}"></script>
        <script
            src="{{ asset('js/register/registeruser.js') }}?v={{ filemtime(public_path('js/register/registeruser.js')) }}">
        </script>
    @endpush


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const birthInput = document.querySelector('input[name="birthdate"]');
            if (!birthInput) return;

            // оборачиваем для аккуратного позиционирования
            let anchor = birthInput.closest('.dp-anchor');
            if (!anchor) {
                anchor = document.createElement('div');
                anchor.className = 'dp-anchor';
                birthInput.parentNode.insertBefore(anchor, birthInput);
                anchor.appendChild(birthInput);
            }

            new Pikaday({
                field: birthInput,
                format: 'MM/DD/YYYY', // всегда англ. формат
                yearRange: [1950, 2004], // выбери свой диапазон
                firstDay: 0, // Sunday
                container: anchor, // чтобы всплывал под инпутом в твоём блоке
                bound: true, // позиционируется относительно field
                toString: (date) => {
                    const mm = String(date.getMonth() + 1).padStart(2, '0');
                    const dd = String(date.getDate()).padStart(2, '0');
                    const yy = date.getFullYear();
                    return `${mm}/${dd}/${yy}`;
                },
                parse: (str) => {
                    const m = /^(\d{1,2})\/(\d{1,2})\/(\d{4})$/.exec((str || '').trim());
                    if (!m) return null;
                    const mm = +m[1],
                        dd = +m[2],
                        yy = +m[3];
                    const d = new Date(yy, mm - 1, dd);
                    return (d.getFullYear() === yy && d.getMonth() === mm - 1 && d.getDate() === dd) ?
                        d : null;
                }
            });
        });
    </script>



@endsection
