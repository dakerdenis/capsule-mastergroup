@extends('layouts.admin')
@section('title', $title ?? 'Products')
@section('page_title', 'Products')

@push('page-styles')
<link rel="stylesheet"
            href="{{ asset('css/admin/products.css') }}?v={{ filemtime(public_path('css/admin/products.css')) }}">
@endpush

@section('content')
    <div class="admin-content">
        @if (session('success'))
            <div class="admin-content"
                style="margin-bottom:12px; border-color:rgba(37,194,160,.35); background:rgba(37,194,160,.08); color:#9fe7d3">
                {{ session('success') }}
            </div>
        @endif
    {{-- ACTIONS --}}
    <div class="actions-bar">
      <a href="{{ route('admin.products.create') }}" class="btn btn--primary">+ New product</a>
    </div>
        {{-- ФИЛЬТРЫ --}}
        <form class="filters" method="GET" action="{{ route('admin.products.index') }}">
            <input class="input" type="text" name="q" value="{{ $q }}"
                placeholder="Search by name / code / slug…">
            <select class="select" name="category_id">
                <option value="">All categories</option>
                @foreach ($categories as $c)
                    <option value="{{ $c->id }}" @selected($catId == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
            <select class="select" name="type">
                <option value="">All types</option>
                @foreach ($types as $t)
                    <option value="{{ $t }}" @selected($type === $t)>{{ $t }}</option>
                @endforeach
            </select>
            <select class="select" name="sort" title="Sort">
                <option value="new" @selected($sort === 'new')>Newest</option>
                <option value="price_asc" @selected($sort === 'price_asc')>Price ↑</option>
                <option value="price_desc" @selected($sort === 'price_desc')>Price ↓</option>
            </select>
            <div class="right">
                <select class="select" name="per_page" title="Per page">
                    @foreach ([10, 20, 30, 50, 100] as $pp)
                        <option value="{{ $pp }}" @selected($perPage == $pp)>{{ $pp }}/page</option>
                    @endforeach
                </select>
                <button class="btn btn--primary">Apply</button>
            </div>
        </form>

        {{-- ТАБЛИЦА --}}
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width:70px">Photo</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th style="width:140px">Price</th>
                        <th style="width:180px">Created</th>
                        <th class="t-actions" style="width:120px; text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                        @php
                            $primary = $p->primaryImage; // hasOne with is_primary = true
                            $photoPath = $primary?->path ?? optional($p->images->first())->path;

                            if ($photoPath) {
                                // если локальный путь — через storage, если URL — как есть
                                $isUrl = Str::startsWith($photoPath, ['http://', 'https://']);
                                $img = $isUrl ? $photoPath : asset('storage/' . $photoPath);
                            } else {
                                $img = asset('images/common/placeholder.png'); // свой плейсхолдер
                            }
                        @endphp

                        <tr>
                            <td>
                                <div class="thumb">
                                    <img src="{{ $img }}" alt="">
                                </div>
                            </td>
                            <td>
                                <div class="tbl-meta">
                                    <span class="t-primary">{{ $p->name }}</span>
                                    <small>
                                        <span class="muted">Code:</span> {{ $p->code }}
                                            &nbsp;&middot;&nbsp;
                                        <span class="muted">Slug:</span> /{{ $p->slug }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                {{ optional($p->category)->name ?? '—' }}
                            </td>
                            <td>
                                @if ($p->type)
                                    <span class="pill">{{ $p->type }}</span>
                                @else
                                    <span class="muted">—</span>
                                @endif
                            </td>
                            <td class="price">
                                {{ number_format((float) $p->price, 2, '.', ' ') }}
                            </td>
                            <td>
                                <div class="tbl-meta">
                                    <span>{{ $p->created_at->format('Y-m-d H:i') }}</span>
                                    <small class="muted">#{{ $p->id }}</small>
                                </div>
                            </td>
                            <td class="t-actions" style="text-align:right">
                                <a class="btn" href="{{ route('admin.products.edit', $p) }}">Edit</a>
                                
                                <form action="{{ route('admin.products.destroy', $p) }}" method="POST"
                                    style="display:inline"
                                    onsubmit="return confirm('Delete product «{{ $p->name }}»? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn" style="border-color:#3a2b2b; color:#ff9b9b">Delete</button>
                                </form>
                            </td>
                            
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="t-empty">No products yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ПАГИНАЦИЯ --}}
        <div class="pagination-wrap">
            {{ $products->links() }}
        </div>
    </div>
@endsection
