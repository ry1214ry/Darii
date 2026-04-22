@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Create Service</h2>

<form action="{{ route('admin.services.store') }}" method="POST" class="card p-4">
    @csrf

    <div class="mb-3">
        <label>Title</label>
        <input type="text" name="title" class="form-control" value="{{ old('title') }}">
    </div>

    <div class="mb-3">
        <label>Icon</label>
        <input type="text" name="icon" class="form-control" value="{{ old('icon') }}">
    </div>

    <div class="mb-3">
        <label>Short Description</label>
        <textarea name="short_description" class="form-control" rows="4">{{ old('short_description') }}</textarea>
    </div>

    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
    </div>

    <button type="submit" class="btn btn-success">Save Service</button>
</form>
@endsection
