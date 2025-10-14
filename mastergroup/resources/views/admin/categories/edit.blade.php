@extends('layouts.admin')

@section('title', $title ?? 'Edit category')
@section('page_title', 'Edit category')


    @push('page-styles')
        <link rel="stylesheet"
            href="{{ asset('css/admin/edit_category.css') }}?v={{ filemtime(public_path('css/admin/edit_category.css')) }}">
    @endpush



@section('content')
    <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="edit-form card p-3">
        @csrf @method('PUT')
        @include('admin.categories.partials.form', ['category' => $category])
        <div class="mt-3">
            <button class="btn btn-primary">Save</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>

@endsection
