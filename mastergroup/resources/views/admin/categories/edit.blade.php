@extends('layouts.admin')

@section('title', $title ?? 'Edit category')
@section('page_title', 'Edit category')

@section('content')
    <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="card p-3">
        @csrf @method('PUT')
        @include('admin.categories.partials.form', ['category' => $category])
        <div class="mt-3">
            <button class="btn btn-primary">Save</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
@endsection
