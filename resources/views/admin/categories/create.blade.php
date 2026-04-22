@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Create Category</h2>

<form action="{{ route('admin.categories.store') }}" method="POST" class="card p-4">
    @csrf

    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}">
    </div>

    <div class="mb-3">
        <label>Type</label>
        <select name="type" class="form-control">
            <option value="project">Project</option>
            <option value="blog">Blog</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
    </div>

    <button type="submit" class="btn btn-success">Save Category</button>
</form>
@endsection
