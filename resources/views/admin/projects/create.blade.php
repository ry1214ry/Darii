@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Create Project</h2>

<form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data" class="card p-4">
    @csrf

    <div class="mb-3">
        <label>Category</label>
        <select name="category_id" class="form-control">
            <option value="">Select Category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Title</label>
        <input type="text" name="title" class="form-control" value="{{ old('title') }}">
    </div>

    <div class="mb-3">
        <label>Preview Image</label>
        <input type="file" name="image" class="form-control" accept="image/*">
        <small class="text-muted">Used on project cards when no demo video is shown.</small>
    </div>

    <div class="mb-3">
        <label>Demo Video File</label>
        <input type="file" name="demo_video_file" class="form-control" accept="video/mp4,video/webm,video/ogg,video/quicktime">
        <small class="text-muted">Upload MP4, WebM, Ogg, or MOV. Max 50 MB.</small>
    </div>

    <div class="mb-3">
        <label>Demo Video URL</label>
        <input type="url" name="demo_video_url" class="form-control" value="{{ old('demo_video_url') }}" placeholder="https://www.youtube.com/watch?v=...">
        <small class="text-muted">Supports YouTube, Vimeo, or a direct video URL. Uploaded file takes priority.</small>
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" rows="5" class="form-control">{{ old('description') }}</textarea>
    </div>

    <div class="mb-3">
        <label>Technology Used</label>
        <textarea name="technology_used" rows="3" class="form-control">{{ old('technology_used') }}</textarea>
    </div>

    <div class="mb-3">
        <label>Project URL</label>
        <input type="url" name="project_url" class="form-control" value="{{ old('project_url') }}">
    </div>

    <div class="mb-3">
        <label>GitHub URL</label>
        <input type="url" name="github_url" class="form-control" value="{{ old('github_url') }}">
    </div>

    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Featured</label>
        <select name="is_featured" class="form-control">
            <option value="1" {{ old('is_featured', '1') == '1' ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ old('is_featured') == '0' ? 'selected' : '' }}>No</option>
        </select>
    </div>

    <button class="btn btn-success">Save</button>
</form>
@endsection
