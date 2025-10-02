@extends('layouts.admin')

@section('title', $title ?? 'Create category')
@section('page_title', 'Create category')

@push('page-styles')
<style>
/* ==== CATEGORY FORM (вписывается в твой тёмный UI) ==== */
.cat-form{
  display:block;
}
.cat-form .admin-content{
  padding:20px;
}

.form-grid{
  display:grid;
  grid-template-columns: 1fr 1fr;
  gap:14px 16px;
}
@media (max-width: 900px){
  .form-grid{ grid-template-columns:1fr }
}

/* поля */
.form-label{
  display:flex; align-items:center; gap:8px;
  font-weight:600; color:var(--muted); margin-bottom:6px;
}
.form-control, .form-select, .form-textarea{
  width:100%;
  background:linear-gradient(180deg, rgba(21,25,35,.85), rgba(27,33,48,.85));
  border:1px solid var(--border);
  border-radius:10px; padding:10px 12px;
  color:var(--text);
  transition:border-color .15s ease, box-shadow .15s ease, background .15s ease;
}
.form-control::placeholder, .form-textarea::placeholder{ color:#6c7687 }
.form-control:focus, .form-select:focus, .form-textarea:focus{
  outline:none; border-color:rgba(91,140,255,.55);
  box-shadow:0 0 0 3px rgba(91,140,255,.12);
  background:linear-gradient(180deg, rgba(27,33,48,.95), rgba(21,25,35,.95));
}
.form-help{ color:var(--muted); font-size:12px; margin-top:6px }
.form-error{ color:var(--danger); font-size:12px; margin-top:6px }

/* тумблер Active */
.switch{
  --h:34px;
  position:relative; display:inline-flex; align-items:center; gap:10px; min-height:var(--h);
}
.switch input{ display:none }
.switch .track{
  width:56px; height:var(--h);
  background:rgba(255,255,255,.06);
  border:1px solid var(--border);
  border-radius:999px; position:relative;
  transition:.18s ease background, .18s ease border-color;
}
.switch .thumb{
  position:absolute; top:3px; left:3px;
  width:28px; height:28px; border-radius:50%;
  background:#fff; box-shadow:var(--shadow);
  transform:translateX(0); transition:transform .2s ease;
}
.switch input:checked + .track{
  background:linear-gradient(180deg, rgba(91,140,255,.35), rgba(91,140,255,.25));
  border-color:rgba(91,140,255,.55);
}
.switch input:checked + .track .thumb{
  transform:translateX(22px);
}

/* кнопки */
.btn-row{ display:flex; gap:10px; margin-top:16px }
.btn-primary{
  background:linear-gradient(180deg, rgba(91,140,255,.9), rgba(91,140,255,.75));
  border:1px solid rgba(91,140,255,.55); color:#fff;
  height:38px; padding:0 14px; border-radius:10px; cursor:pointer;
}
.btn-secondary{
  background:transparent; border:1px solid var(--border); color:var(--text);
  height:38px; padding:0 14px; border-radius:10px; cursor:pointer;
}
.btn-primary:hover{ filter:brightness(1.05) }
.btn-secondary:hover{ background:rgba(255,255,255,.05) }

/* маленькие детали */
.input-with-counter{ position:relative }
.input-counter{
  position:absolute; right:10px; bottom:-18px; font-size:12px; color:var(--muted);
}
.badge-tip{
  display:inline-block; padding:2px 8px; border-radius:999px;
  background:rgba(37,194,160,.12); color:#7ae7d3; border:1px solid rgba(37,194,160,.25);
  font-size:12px; vertical-align:middle;
}
</style>
@endpush

@section('content')
    <div class="cat-form">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="admin-content">
            @csrf

            {{-- сетка формы: переиспользуем твой partial --}}
            <div class="form-grid">
                {{-- name --}}
                <div>
                    <label class="form-label">Name *</label>
                    <div class="input-with-counter">
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="cat-name" placeholder="e.g. Body Parts" required>
                        <span class="input-counter" id="name-count">0</span>
                    </div>
                    <div class="form-help">Чёткое название раздела, 1–3 слова.</div>
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                {{-- slug --}}
                <div>
                    <label class="form-label">Slug <span class="badge-tip">optional</span></label>
                    <input type="text" name="slug" value="{{ old('slug') }}" class="form-control" id="cat-slug" placeholder="auto-generated">
                    <div class="form-help">Если оставить пустым — сгенерируется из названия.</div>
                    @error('slug') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                {{-- parent --}}
                <div>
                    <label class="form-label">Parent</label>
                    <select name="parent_id" class="form-select">
                        <option value="">— Root —</option>
                        @foreach($all as $opt)
                            <option value="{{ $opt->id }}" @selected(old('parent_id', request('parent_id')) == $opt->id)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                    <div class="form-help">Оставь Root, если это корневая категория.</div>
                    @error('parent_id') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                {{-- active --}}
                <div style="display:flex; align-items:flex-end">
                    <label class="switch">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                        <span class="track"><span class="thumb"></span></span>
                        <span style="color:var(--muted)">Active</span>
                    </label>
                </div>

                {{-- description (на всю ширину) --}}
                <div style="grid-column:1/-1">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4" class="form-textarea" placeholder="Optional short description…">{{ old('description') }}</textarea>
                    @error('description') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="btn-row">
                <button class="btn-primary">Create</button>
                <a href="{{ route('admin.categories.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection

@push('page-scripts')
<script>
/* автогенерация slug + счётчик символов */
(function(){
  const nameInput = document.getElementById('cat-name');
  const slugInput = document.getElementById('cat-slug');
  const nameCount = document.getElementById('name-count');

  const slugify = (s) => s
      .toString()
      .normalize('NFD').replace(/[\u0300-\u036f]/g,'') // диакритика
      .toLowerCase()
      .replace(/[^a-z0-9\s-]/g,'')
      .trim()
      .replace(/\s+/g,'-')
      .replace(/-+/g,'-')
      .substring(0,80);

  function update(){
    const v = nameInput.value || '';
    nameCount.textContent = v.length;
    if(!slugInput.value){ slugInput.placeholder = slugify(v); }
  }

  nameInput.addEventListener('input', function(){
    if(!slugInput.value){ slugInput.value = slugify(this.value); }
    update();
  });
  slugInput.addEventListener('input', function(){ this.value = slugify(this.value); });

  update();
})();
</script>
@endpush
