@extends('layouts.admin')

@section('title', $title ?? 'Categories')
@section('page_title', 'Categories')

@push('page-styles')
<link rel="stylesheet" href="{{ asset('css/admin/category.css') }}?v={{ filemtime(public_path('css/admin/category.css')) }}">
@endpush


@section('content')
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif
  <div class="category__wrapper">
    
    <div class="mb-3">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">+ New category</a>
    </div>

    <p class="text-muted">Изменение порядка и вложенности отключено на этой странице.</p> 
    <br>

    <ol id="cat-root" class="cat-tree">
        @foreach($tree as $cat)
            @include('admin.categories.partials.node', ['category' => $cat])
        @endforeach
    </ol>
  </div>
@endsection


@push('page-scripts')
{{-- DnD отключён: Sortable и логика сохранения порядка удалены --}}
<script>
  // Оставлено пустым намеренно.
</script>
@endpush

