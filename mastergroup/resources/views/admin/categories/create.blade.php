@extends('layouts.admin')

@section('title', $title ?? 'Create category')
@section('page_title', 'Create category')

@push('page-styles')
<link rel="stylesheet" href="{{ asset('css/admin/new_category.css') }}?v={{ filemtime(public_path('css/admin/new_category.css')) }}">
@endpush


@section('content')

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
