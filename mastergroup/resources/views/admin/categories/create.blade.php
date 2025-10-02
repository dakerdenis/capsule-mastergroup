@extends('layouts.admin')

@section('title', $title ?? 'Create category')
@section('page_title', 'Create category')

@push('page-styles')

@endpush


@section('content')
<style>
/* ==== DARK FORM THEME ==== */
.cat-form .admin-content {
  padding: 24px;
  border-radius: 14px;
  background: linear-gradient(180deg, rgba(25, 29, 41, 0.95), rgba(15, 17, 25, 0.95));
  border: 1px solid rgba(255,255,255,0.08);
  box-shadow: 0 4px 16px rgba(0,0,0,0.45);
  color: #e1e6f2;
}

/* grid */
.cat-form .form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 18px;
}
@media(max-width:1024px) {
  .cat-form .form-grid { grid-template-columns:1fr; }
}

/* fields */
.cat-form .field { display:flex; flex-direction:column; gap:8px; }
.cat-form .label {
  font-weight: 600;
  font-size: 14px;
  color: #cfd9ef;
  letter-spacing: .3px;
}
.cat-form .hint {
  font-size: 12px;
  color: #8a94ad;
}
.cat-form .error {
  font-size: 13px;
  font-weight: 500;
  color: #ff6b6b;
}
.cat-form .top-hint {
  margin-bottom: 20px;
  font-size: 14px;
  color: #9fa9c5;
}

/* inputs, selects, textarea */
.cat-form .input,
.cat-form .select,
.cat-form .textarea {
  background: #1c212e;
  border: 1px solid rgba(255,255,255,0.12);
  border-radius: 8px;
  padding: 10px 12px;
  font-size: 14px;
  color: #f0f3fb;
  transition: border-color .2s, background .2s;
}
.cat-form .input::placeholder,
.cat-form .textarea::placeholder {
  color: #6f7a96;
}
.cat-form .input:focus,
.cat-form .select:focus,
.cat-form .textarea:focus {
  outline: none;
  border-color: #4b8dff;
  background: #202534;
}

/* counter */
.cat-form .counter {
  font-size: 12px;
  color: #7e89a6;
  margin-top: 2px;
}

/* switch */
.cat-form .switch {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
}
.cat-form .switch input[type="checkbox"] {
  display:none;
}
.cat-form .switch .track {
  width: 40px; height: 20px;
  background: #333a4f;
  border-radius: 20px;
  position: relative;
  transition: background .2s;
}
.cat-form .switch .thumb {
  width: 16px; height: 16px;
  background: #ccd4e8;
  border-radius: 50%;
  position: absolute; top:2px; left:2px;
  transition: all .25s;
}
.cat-form .switch input:checked + .track .thumb {
  left: 22px; background: #4b8dff;
}
.cat-form .switch input:checked + .track {
  background: #2c5be8;
}
.cat-form .switch .txt {
  font-size: 14px;
  color: #b7c3df;
}

/* textarea full row */
.cat-form .textarea {
  min-height: 120px;
  resize: vertical;
}

/* buttons */
.cat-form .actions {
  margin-top: 24px;
  display:flex;
  gap: 12px;
}
.cat-form .btn {
  padding: 10px 18px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor:pointer;
  transition: background .2s, color .2s;
  text-decoration:none;
  display:inline-flex;
  align-items:center;
  justify-content:center;
}
.cat-form .btn-primary {
  background: #4b8dff;
  color: #fff;
  border:none;
}
.cat-form .btn-primary:hover {
  background: #3a78e0;
}
.cat-form .btn-secondary {
  background: #2a2f40;
  color: #cfd6ea;
}
.cat-form .btn-secondary:hover {
  background: #353c54;
  color: #fff;
}
</style>
<div class="cat-form">
  <form action="{{ route('admin.categories.store') }}" method="POST" class="admin-content" novalidate>
    @csrf

    <div class="top-hint">Заполни поля и нажми Create — slug можно оставить пустым, он создастся автоматически.</div>

    <div class="form-grid">

      {{-- Name --}}
      <div class="field">
        <label class="label" for="cat-name">Name *</label>
        <input id="cat-name" type="text" name="name" value="{{ old('name') }}" class="input" placeholder="Body Parts" required>
        <div class="counter" id="name-count">0</div>
        @error('name') <div class="error">{{ $message }}</div> @enderror
      </div>

      {{-- Slug --}}
      <div class="field">
        <label class="label" for="cat-slug">Slug <span style="opacity:.75">(optional)</span></label>
        <input id="cat-slug" type="text" name="slug" value="{{ old('slug') }}" class="input" placeholder="auto-generated">
        <div class="hint">Если пусто — сгенерируется из названия.</div>
        @error('slug') <div class="error">{{ $message }}</div> @enderror
      </div>

      {{-- Parent --}}
      <div class="field">
        <label class="label" for="cat-parent">Parent</label>
        <select id="cat-parent" name="parent_id" class="select">
          <option value="">— Root —</option>
          @foreach($all as $opt)
            <option value="{{ $opt->id }}" @selected(old('parent_id', request('parent_id')) == $opt->id)>{{ $opt->name }}</option>
          @endforeach
        </select>
        <div class="hint">Оставь Root, если это корневая категория.</div>
        @error('parent_id') <div class="error">{{ $message }}</div> @enderror
      </div>

      {{-- Active --}}
      <div class="field" style="align-self:flex-end">
        <label class="label">Status</label>
        <label class="switch">
          <input type="hidden" name="is_active" value="0">
          <input type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
          <span class="track"><span class="thumb"></span></span>
          <span class="txt">Active</span>
        </label>
      </div>

      {{-- Description (full row) --}}
      <div class="field" style="grid-column:1/-1">
        <label class="label" for="cat-desc">Description</label>
        <textarea id="cat-desc" name="description" class="textarea" placeholder="Optional short description…">{{ old('description') }}</textarea>
        @error('description') <div class="error">{{ $message }}</div> @enderror
      </div>

    </div>

    <div class="actions">
      <button class="btn btn-primary" type="submit">Create</button>
      <a class="btn btn-secondary" href="{{ route('admin.categories.index') }}">Cancel</a>
    </div>
  </form>
</div>
@endsection

@push('page-scripts')
<script>
  // slugify + live counter (аккуратно, без навязчивости)
  (function(){
    const nameInput = document.getElementById('cat-name');
    const slugInput = document.getElementById('cat-slug');
    const nameCount = document.getElementById('name-count');

    const slugify = (s) => s
      .toString()
      .normalize('NFD').replace(/[\u0300-\u036f]/g,'')
      .toLowerCase()
      .replace(/[^a-z0-9\s-]/g,'')
      .trim()
      .replace(/\s+/g,'-')
      .replace(/-+/g,'-')
      .slice(0,80);

    const updateCounter = () => nameCount.textContent = (nameInput.value||'').length;

    nameInput.addEventListener('input', () => {
      if (!slugInput.value) slugInput.value = slugify(nameInput.value);
      updateCounter();
    });
    slugInput.addEventListener('input', () => slugInput.value = slugify(slugInput.value));
    updateCounter();
  })();
</script>
@endpush
