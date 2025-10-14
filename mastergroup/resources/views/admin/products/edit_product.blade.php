@extends('layouts.admin')

@section('title', $title ?? 'Edit product')
@section('page_title', 'Edit product')

@push('page-styles')
<link rel="stylesheet" href="{{ asset('css/admin/edit_product.css') }}">
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
