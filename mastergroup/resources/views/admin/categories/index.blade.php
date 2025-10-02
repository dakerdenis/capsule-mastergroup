@extends('layouts.admin')

@section('title', $title ?? 'Categories')
@section('page_title', 'Categories')

@push('page-styles')
<style>
    .cat-tree { list-style:none; padding-left:0 }
    .cat-item { border:1px solid #e3e3e3; border-radius:10px; padding:10px 12px; margin-bottom:8px; background:#fff }
    .cat-row { display:flex; gap:10px; align-items:center; justify-content:space-between }
    .cat-actions a, .cat-actions form { display:inline-block }
    .cat-children { margin-top:8px; margin-left:22px; padding-left:0; list-style:none }
    .drag-handle { cursor:grab; user-select:none; font-weight:600 }
</style>
@endpush

@section('content')
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

    <div class="mb-3">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">+ New category</a>
    </div>

    <p class="text-muted">Перетаскивай категории для смены порядка и вложенности. Изменения сохраняются автоматически.</p>

    <ol id="cat-root" class="cat-tree">
        @foreach($tree as $cat)
            @include('admin.categories.partials.node', ['category' => $cat])
        @endforeach
    </ol>
@endsection

@push('page-scripts')
{{-- SortableJS CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
    // Рекурсивно навешиваем Sortable на все списки
    function initSortable(el) {
        new Sortable(el, {
            group: 'cats',
            handle: '.drag-handle',
            animation: 150,
            fallbackOnBody: true,
            swapThreshold: 0.65,
            onSort: debounce(saveOrder, 500),
            onAdd: debounce(saveOrder, 500)
        });

        el.querySelectorAll('ol.cat-children').forEach(initSortable);
    }

    function collect(el) {
        // собираем дерево => [{id, children: [...]}, ...]
        const items = [];
        el.querySelectorAll(':scope > li.cat-item').forEach((li) => {
            const id = parseInt(li.dataset.id, 10);
            const childOl = li.querySelector(':scope > ol.cat-children');
            items.push({
                id,
                children: childOl ? collect(childOl) : []
            });
        });
        return items;
    }

    let saving = false;
    async function saveOrder() {
        if (saving) return;
        saving = true;
        try {
            const root = document.getElementById('cat-root');
            const payload = { items: collect(root) };

            const res = await fetch('{{ route('admin.categories.reorder') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            });
            if (!res.ok) console.error('Reorder failed', await res.text());
        } catch (e) {
            console.error(e);
        } finally {
            saving = false;
        }
    }

    function debounce(fn, ms) {
        let t; return function(...args){
            clearTimeout(t); t = setTimeout(()=>fn.apply(this,args), ms);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        initSortable(document.getElementById('cat-root'));
    });
</script>
@endpush
