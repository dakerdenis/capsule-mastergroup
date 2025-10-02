@extends('layouts.admin')

@section('title', $title ?? 'Edit category')
@section('page_title', 'Edit category')

@push('page-styles')
<style>
/* ==== DARK EDIT FORM (bootstrap-friendly) ==== */
.edit-form.card{
  background:linear-gradient(180deg, #171d2e, #111729) !important;
  border:1px solid rgba(255,255,255,.08) !important;
  border-radius:14px !important;
  box-shadow:0 10px 30px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.03) !important;
  color:#e7ecfb;
}

/* подписи/подсказки/ошибки */
.edit-form label,
.edit-form .label{ color:#cfd9ef; font-weight:600; font-size:14px; letter-spacing:.3px }
.edit-form .hint{ font-size:12px; color:#8a94ad }
.edit-form .invalid-feedback,
.edit-form .error{ font-size:13px; font-weight:500; color:#ff6b6b }

/* ГЛАВНОЕ: стилизуем ВСЕ стандартные контролы */
.edit-form input[type="text"],
.edit-form input[type="email"],
.edit-form input[type="number"],
.edit-form input[type="password"],
.edit-form input[type="search"],
.edit-form input[type="url"],
.edit-form input[type="tel"],
.edit-form select,
.edit-form textarea,
.edit-form .form-control,
.edit-form .form-select,
.edit-form .input,
.edit-form .select,
.edit-form .textarea{
  background:#1c212e !important;
  border:1px solid rgba(255,255,255,.12) !important;
  border-radius:8px !important;
  padding:10px 12px !important;
  font-size:14px !important;
  color:#f0f3fb !important;
  transition:border-color .2s, background .2s;
}

/* placeholder */
.edit-form ::placeholder{ color:#6f7a96 }

/* focus */
.edit-form input[type="text"]:focus,
.edit-form input[type="email"]:focus,
.edit-form input[type="number"]:focus,
.edit-form input[type="password"]:focus,
.edit-form input[type="search"]:focus,
.edit-form input[type="url"]:focus,
.edit-form input[type="tel"]:focus,
.edit-form select:focus,
.edit-form textarea:focus,
.edit-form .form-control:focus,
.edit-form .form-select:focus{
  outline:none;
  border-color:#4b8dff !important;
  background:#202534 !important;
  box-shadow:0 0 0 .2rem rgba(75,141,255,.15) !important; /* заменяет bootstrap glow */
}

/* чекбоксы/радио (если есть) */
.edit-form .form-check-input{
  background-color:#1c212e; border:1px solid rgba(255,255,255,.2)
}
.edit-form .form-check-input:checked{
  background-color:#4b8dff; border-color:#4b8dff
}
.edit-form .form-check-label{ color:#cfd9ef }

/* отступы между группами */
.edit-form .mb-3, .edit-form .form-group, .edit-form .field{ margin-bottom:18px }

/* кнопки */
.edit-form .btn{
  padding:10px 18px; border-radius:8px; font-size:14px; font-weight:600;
  cursor:pointer; transition:background .2s, color .2s, transform .08s;
  text-decoration:none; display:inline-flex; align-items:center; justify-content:center;
}
.edit-form .btn-primary{ background:#4b8dff; color:#fff; border:none }
.edit-form .btn-primary:hover{ background:#3a78e0; transform:translateY(-1px) }
.edit-form .btn-secondary{ background:#2a2f40; color:#cfd6ea; border:none }
.edit-form .btn-secondary:hover{ background:#353c54; color:#fff }
</style>
@endpush


@push('page-styles')
<style>
/* ==== DARK EDIT FORM ==== */
.edit-form.card{
  background:linear-gradient(180deg, #171d2e, #111729);
  border:1px solid rgba(255,255,255,.08);
  border-radius:14px;
  box-shadow:0 10px 30px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.03);
  padding:24px;
  color:#e7ecfb;
}

/* поля формы */
.edit-form .field{
  display:flex; flex-direction:column; gap:8px; margin-bottom:18px;
}
.edit-form .label{
  font-weight:600; font-size:14px; color:#cfd9ef; letter-spacing:.3px;
}
.edit-form .hint{ font-size:12px; color:#8a94ad }
.edit-form .error{ font-size:13px; font-weight:500; color:#ff6b6b }

/* inputs, selects, textarea */
.edit-form .input,
.edit-form .select,
.edit-form .textarea{
  background:#1c212e;
  border:1px solid rgba(255,255,255,.12);
  border-radius:8px;
  padding:10px 12px;
  font-size:14px;
  color:#f0f3fb;
  transition:border-color .2s, background .2s;
}
.edit-form .input::placeholder,
.edit-form .textarea::placeholder{ color:#6f7a96 }
.edit-form .input:focus,
.edit-form .select:focus,
.edit-form .textarea:focus{
  outline:none;
  border-color:#4b8dff;
  background:#202534;
}

/* buttons */
.edit-form .btn{
  padding:10px 18px; border-radius:8px; font-size:14px; font-weight:600;
  cursor:pointer; transition:background .2s, color .2s, transform .08s;
  text-decoration:none; display:inline-flex; align-items:center; justify-content:center;
}
.edit-form .btn-primary{
  background:#4b8dff; color:#fff; border:none;
}
.edit-form .btn-primary:hover{ background:#3a78e0; transform:translateY(-1px) }
.edit-form .btn-secondary{
  background:#2a2f40; color:#cfd6ea; border:none;
}
.edit-form .btn-secondary:hover{ background:#353c54; color:#fff }
</style>
@endpush

@section('content')
<form action="{{ route('admin.categories.update', $category) }}" method="POST" class="edit-form card p-3">
    @csrf @method('PUT')
    @include('admin.categories.partials.form', ['category' => $category])
    <div class="mt-3">
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>

@endsection
