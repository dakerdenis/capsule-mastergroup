@extends('layouts.admin')
@section('title', $title ?? 'Products')
@section('page_title', 'Products')

@push('page-styles')
<style>
  .filters{ display:grid; grid-template-columns: 1.4fr 1fr 1fr auto auto; gap:10px; margin-bottom:14px }
  @media (max-width: 1100px){ .filters{ grid-template-columns:1fr 1fr; } .filters .right{ grid-column:1/-1; display:flex; gap:10px; justify-content:flex-end } }
  .input, .select{
    background:linear-gradient(180deg,#151a25,#121723);
    border:1px solid #263046; border-radius:10px; height:38px; color:var(--text); padding:0 12px; outline:none;
    transition:border-color .15s ease, box-shadow .15s ease, background .15s ease;
  }
  .input:focus, .select:focus{ border-color:rgba(91,140,255,.55); box-shadow:0 0 0 3px rgba(91,140,255,.12) }
  .btn{ height:38px; border-radius:10px; padding:0 12px; border:1px solid var(--border); background:transparent; color:var(--text); cursor:pointer }
  .btn--primary{ background:linear-gradient(180deg,#6a98ff,#527fff); border-color:rgba(91,140,255,.6); color:#fff }
  .thumb{
    width:56px; height:42px; border-radius:8px; overflow:hidden; background:#0e1421; border:1px solid var(--border);
    display:flex; align-items:center; justify-content:center;
  }
  .thumb img{ width:100%; height:100%; object-fit:cover }
  .price{ font-weight:700 }
  .pill{ display:inline-block; padding:2px 8px; border-radius:999px; background:rgba(91,140,255,.12); color:#cfe0ff; font-size:12px }
  .muted{ color:var(--muted); font-size:12px }
  .t-actions .btn{ height:30px; padding:0 10px }
  .tbl-meta{ display:flex; flex-direction:column }
  .tbl-meta small{ color:var(--muted) }
  .pagination-wrap{ margin-top:14px }
</style>
@endpush

@section('content')
  <div class="admin-content">
    {{-- ФИЛЬТРЫ --}}
    <form class="filters" method="GET" action="{{ route('admin.products.index') }}">
      <input class="input" type="text" name="q" value="{{ $q }}" placeholder="Search by name / code / slug…">
      <select class="select" name="category_id">
        <option value="">All categories</option>
        @foreach($categories as $c)
          <option value="{{ $c->id }}" @selected($catId==$c->id)>{{ $c->name }}</option>
        @endforeach
      </select>
      <select class="select" name="type">
        <option value="">All types</option>
        @foreach($types as $t)
          <option value="{{ $t }}" @selected($type===$t)>{{ $t }}</option>
        @endforeach
      </select>
      <select class="select" name="sort" title="Sort">
        <option value="new" @selected($sort==='new')>Newest</option>
        <option value="price_asc" @selected($sort==='price_asc')>Price ↑</option>
        <option value="price_desc" @selected($sort==='price_desc')>Price ↓</option>
      </select>
      <div class="right">
        <select class="select" name="per_page" title="Per page">
          @foreach([10,20,30,50,100] as $pp)
            <option value="{{ $pp }}" @selected($perPage==$pp)>{{ $pp }}/page</option>
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
            $img = optional($p->images->first())->path;
            $img = $img ?: asset('images/common/placeholder.png'); // подложи файл, либо останется внешняя картинка
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
              @if($p->type)
                <span class="pill">{{ $p->type }}</span>
              @else
                <span class="muted">—</span>
              @endif
            </td>
            <td class="price">
              {{ number_format((float)$p->price, 2, '.', ' ') }}
            </td>
            <td>
              <div class="tbl-meta">
                <span>{{ $p->created_at->format('Y-m-d H:i') }}</span>
                <small class="muted">#{{ $p->id }}</small>
              </div>
            </td>
            <td class="t-actions" style="text-align:right">
              {{-- Страницу show/edit добавим позже; пока заглушки/disabled --}}
              <a class="btn" style="opacity:.6; pointer-events:none">View</a>
              <a class="btn" style="opacity:.6; pointer-events:none">Edit</a>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="t-empty">No products yet.</td></tr>
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
