@extends('layouts.admin')

@section('title', $title ?? 'Categories')
@section('page_title', 'Categories')

@push('page-styles')
<style>
/* ====== DARK TREE UI (без перетаскивания) ====== */
:root{
  --bg:#0f1320; --card:#171d2e; --card-2:#111729; --border:rgba(255,255,255,.08);
  --muted:#8b96b3; --text:#e7ecfb; --accent:#4b8dff; --accent-2:#3a78e0;
  --danger:#ff6b6b; --success:#42c27a; --shadow:0 10px 30px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.03);
}
body { background:
  radial-gradient(1200px 600px at 90% -10%, rgba(75,141,255,.06), transparent 60%),
  radial-gradient(900px 480px at -10% 0%, rgba(66,194,122,.05), transparent 55%),
  var(--bg);
}

.btn.btn-primary{
  background:var(--accent); border:none; color:#fff; border-radius:10px;
  padding:10px 16px; font-weight:700; transition:transform .08s, background .2s;
}
.btn.btn-primary:hover{ background:var(--accent-2); transform:translateY(-1px) }
.btn.btn-secondary{ background:#222a40; color:#d6def8; border:1px solid var(--border) }

.alert{ border-radius:10px; border:1px solid var(--border); color:#dfe6fb }
.alert-success{ background:rgba(66,194,122,.12); border-color:rgba(66,194,122,.35); padding:20px; margin:20px 0 15px }
.alert-danger{ background:rgba(255,107,107,.10); border-color:rgba(255,107,107,.35) }
.text-muted{ color:var(--muted)!important }

.cat-tree{ list-style:none; padding-left:0; margin:0 }
.cat-children{ list-style:none; padding-left:0; margin-left:22px; position:relative }
.cat-children::before{
  content:""; position:absolute; left:10px; top:-4px; bottom:6px;
  width:1px; background:linear-gradient(var(--border), transparent 85%);
}

.cat-item{
  position:relative; margin-bottom:10px; border:1px solid var(--border);
  border-radius:14px; background:linear-gradient(180deg, var(--card), var(--card-2));
  box-shadow:var(--shadow); padding:12px 14px; color:var(--text);
  transition:border-color .2s, transform .08s, box-shadow .2s, background .2s;
}
.cat-item:hover{
  border-color:rgba(75,141,255,.35);
  box-shadow:0 14px 26px rgba(15,23,42,.45), 0 0 0 1px rgba(75,141,255,.14) inset;
}
.cat-item::before{
  content:""; position:absolute; left:-12px; top:22px; width:12px; height:12px;
  border-left:1px dashed var(--border); border-bottom:1px dashed var(--border); border-radius:0 0 0 8px;
}

.cat-row{ display:flex; align-items:center; justify-content:space-between; gap:12px }

/* drag-handle выключен и скрыт */
.drag-handle{ display:none !important; }

.cat-actions a, .cat-actions form{ display:inline-block }
.cat-actions a, .cat-actions button{
  --btn-bg:#232a43; display:inline-flex; align-items:center; gap:8px;
  border:1px solid var(--border); background:var(--btn-bg); color:#dbe6ff;
  padding:8px 12px; border-radius:10px; text-decoration:none; font-weight:600;
  transition:background .2s, border-color .2s, transform .08s;
}
.cat-actions a:hover, .cat-actions button:hover{
  background:#263152; border-color:rgba(75,141,255,.35)
}
.cat-actions .danger{ background:rgba(255,107,107,.12); border-color:rgba(255,107,107,.35); color:#ffd3d3 }
.cat-actions .danger:hover{ background:rgba(255,107,107,.18) }

/* скроллы */
.cat-tree, .cat-children{ scrollbar-width:thin; scrollbar-color:#334066 transparent }
.cat-tree::-webkit-scrollbar{ height:10px; width:10px }
.cat-tree::-webkit-scrollbar-thumb{ background:#334066; border-radius:8px }
</style>
@endpush


@section('content')
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

    <div class="mb-3">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">+ New category</a>
    </div>

    <p class="text-muted">Изменение порядка и вложенности отключено на этой странице.</p>

    <ol id="cat-root" class="cat-tree">
        @foreach($tree as $cat)
            @include('admin.categories.partials.node', ['category' => $cat])
        @endforeach
    </ol>
@endsection


@push('page-scripts')
{{-- DnD отключён: Sortable и логика сохранения порядка удалены --}}
<script>
  // Оставлено пустым намеренно.
</script>
@endpush

