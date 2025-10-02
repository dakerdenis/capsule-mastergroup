@php
    $hasChildren = $category->relationLoaded('children') ? $category->children->count() : $category->children()->count();
@endphp

<li class="cat-item" data-id="{{ $category->id }}">
    <div class="cat-row">
        <div>
            <span class="drag-handle">⋮⋮</span>
            <strong>{{ $category->name }}</strong>
            @if(!$category->is_active)
                <span class="badge bg-secondary">inactive</span>
            @endif
            <span class="text-muted">/{{ $category->slug }}</span>
        </div>
        <div class="cat-actions">
            <a href="{{ route('admin.categories.create', ['parent_id' => $category->id]) }}" class="btn btn-sm btn-outline-success">+ Sub</a>
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">Edit</a>
            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?');" style="display:inline">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Delete</button>
            </form>
        </div>
    </div>

    <ol class="cat-children">
        @if($hasChildren)
            @foreach($category->children as $child)
                @include('admin.categories.partials.node', ['category' => $child])
            @endforeach
        @endif
    </ol>
</li>
