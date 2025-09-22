@extends('layouts.auth')

@section('title', $title ?? 'Create account')
@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/auth/user.css') }}?v={{ filemtime(public_path('css/auth/user.css')) }}">
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
                            <form action="">
                                <!-- FIRST BLOCK -->
                                <div class="register_user-element">
                                    <div class="file-upload">
                                        <input type="file" class="file-input" accept="image/*" hidden>
                                        <div class="file-dropzone">
                                            <div class="file-icon">📄</div>
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
                                            <div class="file-icon">📄</div>
                                            <p>Profile photo *<span class="req">*</span></p>
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
                                    <input type="text">
                                </div>
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

<script>
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".file-upload").forEach(block => {
    const input = block.querySelector(".file-input");
    const dropZone = block.querySelector(".file-dropzone");
    const preview = block.querySelector(".file-preview");
    const previewImg = block.querySelector(".preview-img");
    const removeBtn = block.querySelector(".file-remove");

    // клик по Choose file
    dropZone.addEventListener("click", (e) => {
      if (e.target.classList.contains("choose") || e.target.closest(".file-dropzone")) {
        input.click();
      }
    });

    // drag over
    dropZone.addEventListener("dragover", (e) => {
      e.preventDefault();
      dropZone.style.borderColor = "#0a0";
    });

    dropZone.addEventListener("dragleave", () => {
      dropZone.style.borderColor = "#34c81e";
    });

    // drop
    dropZone.addEventListener("drop", (e) => {
      e.preventDefault();
      dropZone.style.borderColor = "#34c81e";
      if (e.dataTransfer.files.length) {
        input.files = e.dataTransfer.files;
        showPreview(input.files[0]);
      }
    });

    // выбор файла через input
    input.addEventListener("change", () => {
      if (input.files.length) {
        showPreview(input.files[0]);
      }
    });

    // показать превью
    function showPreview(file) {
      if (!file.type.startsWith("image/")) return;
      const reader = new FileReader();
      reader.onload = (e) => {
        previewImg.src = e.target.result;
        preview.hidden = false;
        dropZone.style.display = "none";
      };
      reader.readAsDataURL(file);
    }

    // удалить файл
    removeBtn.addEventListener("click", () => {
      input.value = "";
      preview.hidden = true;
      previewImg.src = "";
      dropZone.style.display = "block";
    });
  });
});
</script>




@endsection
