@extends('layouts.admin')

@section('title', $title ?? 'Edit product')
@section('page_title', 'Edit product')

@push('page-styles')
<style>
  .form-card{ background:linear-gradient(180deg, rgba(25,31,45,.8), rgba(17,22,34,.8)); border:1px solid var(--border); border-radius:16px; box-shadow:var(--shadow) }
  .form-head{ padding:14px 18px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center }
  .form-body{ padding:18px; display:grid; gap:18px }
  .grid{ display:grid; grid-template-columns:1fr 1fr; gap:16px } @media (max-width:1024px){ .grid{ grid-template-columns:1fr } }
  .field{ display:flex; flex-direction:column; gap:8px }
  .label{ font-weight:700; color:#cfe0ff }
  .input,.select,.textarea{ height:44px; padding:0 12px; background:linear-gradient(180deg,#151a25,#121723); border:1px solid #263046; border-radius:12px; color:var(--text); outline:none }
  .input:focus,.select:focus,.textarea:focus{ border-color:rgba(91,140,255,.55); box-shadow:0 0 0 3px rgba(91,140,255,.12) }
  .textarea{ min-height:120px; height:auto; padding:10px 12px; resize:vertical }
  .help{ color:var(--muted); font-size:12px }
  .error{ color:var(--danger); font-size:12px }

  .images{ display:grid; gap:12px }
  .img-grid{ display:grid; grid-template-columns:repeat(5, 1fr); gap:12px } @media (max-width:1100px){ .img-grid{ grid-template-columns:repeat(3,1fr) } } @media (max-width:700px){ .img-grid{ grid-template-columns:repeat(2,1fr) } }
  .img-card{ background:rgba(27,33,48,.6); border:1px solid var(--border); border-radius:12px; overflow:hidden; display:flex; flex-direction:column }
  .img-card .p{ padding:8px }
  .thumb{ width:100%; aspect-ratio: 4/3; background:#0f1421; display:flex; align-items:center; justify-content:center; overflow:hidden }
  .thumb img{ width:100%; height:100%; object-fit:cover }
  .img-actions{ display:flex; align-items:center; justify-content:space-between; gap:8px; padding:8px }
  .badge{ display:inline-block; padding:2px 8px; border-radius:999px; border:1px solid rgba(91,140,255,.35); background:rgba(91,140,255,.12); color:#cfe0ff; font-size:12px }

  .upload{ border:1px dashed #314166; border-radius:12px; padding:12px; background:rgba(21,25,35,.5) }
  .actions{ display:flex; gap:10px; border-top:1px solid var(--border); padding:12px 18px; justify-content:flex-end }
  .btn{ height:40px; padding:0 14px; border-radius:12px; border:1px solid var(--border); background:transparent; color:var(--text); cursor:pointer }
  .btn-primary{ background:linear-gradient(180deg,#6a98ff,#4f7aff); border-color:rgba(91,140,255,.6); color:#fff }
  .btn:hover{ filter:brightness(1.05) }
</style>
@endpush

@section('content')
<form action="{{ route('admin.products.update', $product) }}" method="POST" class="form-card" enctype="multipart/form-data">
  @csrf
  @method('PUT')

  <div class="form-head">
    <div>
      <strong>{{ $product->name }}</strong>
      <span class="help">#{{ $product->id }}</span>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn">Back</a>
  </div>

  <div class="form-body">
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

    <div class="grid">
      <div class="field">
        <label class="label">Name *</label>
        <input class="input" name="name" value="{{ old('name', $product->name) }}" required>
        @error('name') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="field">
        <label class="label">Code *</label>
        <input class="input" name="code" value="{{ old('code', $product->code) }}" required>
        @error('code') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="field">
        <label class="label">Slug (optional)</label>
        <input class="input" name="slug" value="{{ old('slug', $product->slug) }}" placeholder="auto-generated if empty">
        @error('slug') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="field">
        <label class="label">Type</label>
        <input class="input" name="type" value="{{ old('type', $product->type) }}" list="type-list">
        <datalist id="type-list">
          @foreach($types as $t) <option value="{{ $t }}"> @endforeach
        </datalist>
        @error('type') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="field">
        <label class="label">Price *</label>
        <input class="input" type="number" step="0.01" min="0" name="price" value="{{ old('price', $product->price) }}" required>
        @error('price') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="field">
        <label class="label">Category *</label>
        <select class="select" name="category_id" required>
          @foreach($categories as $c)
            <option value="{{ $c->id }}" @selected(old('category_id', $product->category_id)==$c->id)>{{ $c->name }}</option>
          @endforeach
        </select>
        @error('category_id') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="field" style="grid-column:1/-1">
        <label class="label">Description</label>
        <textarea class="textarea" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
        @error('description') <div class="error">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- Images --}}
    <div class="images">
      <div class="label" style="display:flex; align-items:center; gap:10px">
        Images <span class="help">Total 1–5. Choose primary, delete лишние, добавь новые.</span>
      </div>

      <div class="img-grid">
        @foreach($product->images as $img)
          <div class="img-card">
            <div class="thumb">
              @php
                $src = Str::startsWith($img->path, ['http://','https://'])
                    ? $img->path
                    : asset('storage/'.$img->path);
              @endphp
              <img src="{{ $src }}" alt="">
            </div>
            <div class="img-actions">
              <label style="display:flex; align-items:center; gap:6px">
                <input type="radio" name="primary_image_id" value="{{ $img->id }}" {{ $img->is_primary ? 'checked' : '' }}>
                <span class="badge">Primary</span>
              </label>
              <label class="help" style="display:flex; align-items:center; gap:6px">
                <input type="checkbox" name="delete_images[]" value="{{ $img->id }}">
                delete
              </label>
            </div>
          </div>
        @endforeach
      </div>

      <div class="upload">
        <div class="help">Upload new images (they will be appended to the end). Max 5MB each. Allowed: jpg, png, webp.</div>
        <input class="input" type="file" name="images[]" accept=".jpg,.jpeg,.png,.webp" multiple>
        @error('images') <div class="error">{{ $message }}</div> @enderror
        @error('images.*') <div class="error">{{ $message }}</div> @enderror
      </div>
    </div>
  </div>

  <div class="actions">
    <a href="{{ route('admin.products.index') }}" class="btn">Cancel</a>
    <button class="btn btn-primary">Save changes</button>
  </div>
</form>
@endsection
