@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Edit Category</h2>

<form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="card p-4">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}">
    </div>

    <div class="mb-3">
        <label>Type</label>
        <select name="type" class="form-control">
            <option value="project" {{ $category->type == 'project' ? 'selected' : '' }}>Project</option>
            <option value="blog" {{ $category->type == 'blog' ? 'selected' : '' }}>Blog</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="1" {{ $category->status ? 'selected' : '' }}>Active</option>
            <option value="0" {{ !$category->status ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Update Category</button>
</form>
@endsection
