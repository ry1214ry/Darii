@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Edit Project</h2>

<form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data" class="card p-4">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Category</label>
        <select name="category_id" class="form-control">
            <option value="">Select Category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $project->category_id) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Title</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $project->title) }}">
    </div>

    @if($project->image)
        <div class="mb-3">
            <label>Current Preview Image</label>
            <div class="mt-2" style="max-width: 420px;">
                <img
                    src="{{ asset('storage/' . ltrim($project->image, '/')) }}"
                    alt="{{ $project->title }}"
                    class="img-fluid rounded border"
                >
            </div>

            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" value="1" id="remove_image" name="remove_image" {{ old('remove_image') ? 'checked' : '' }}>
                <label class="form-check-label" for="remove_image">Remove current preview image</label>
            </div>
        </div>
    @endif

    <div class="mb-3">
        <label>Preview Image</label>
        <input type="file" name="image" class="form-control" accept="image/*">
        <small class="text-muted">Used on project cards when no demo video is shown.</small>
    </div>

    @if($project->demo_video)
        <div class="mb-3">
            <label>Current Demo Video</label>
            <div class="mt-2" style="max-width: 420px;">
                <x-project-media :project="$project" media-class="project-preview-media" placeholder-text="Project Demo" />
            </div>

            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" value="1" id="remove_demo_video" name="remove_demo_video" {{ old('remove_demo_video') ? 'checked' : '' }}>
                <label class="form-check-label" for="remove_demo_video">Remove current demo video</label>
            </div>
        </div>
    @endif

    <div class="mb-3">
        <label>New Demo Video File</label>
        <input type="file" name="demo_video_file" class="form-control" accept="video/mp4,video/webm,video/ogg,video/quicktime">
        <small class="text-muted">Upload MP4, WebM, Ogg, or MOV. Max 50 MB.</small>
    </div>

    <div class="mb-3">
        <label>Demo Video URL</label>
        <input
            type="url"
            name="demo_video_url"
            class="form-control"
            value="{{ old('demo_video_url', \Illuminate\Support\Str::startsWith((string) $project->demo_video, ['http://', 'https://']) ? $project->demo_video : '') }}"
            placeholder="https://www.youtube.com/watch?v=..."
        >
        <small class="text-muted">Supports YouTube, Vimeo, or a direct video URL. Uploaded file takes priority.</small>
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" rows="5" class="form-control">{{ old('description', $project->description) }}</textarea>
    </div>

    <div class="mb-3">
        <label>Technology Used</label>
        <textarea name="technology_used" rows="3" class="form-control">{{ old('technology_used', $project->technology_used) }}</textarea>
    </div>

    <div class="mb-3">
        <label>Project URL</label>
        <input type="url" name="project_url" class="form-control" value="{{ old('project_url', $project->project_url) }}">
    </div>

    <div class="mb-3">
        <label>GitHub URL</label>
        <input type="url" name="github_url" class="form-control" value="{{ old('github_url', $project->github_url) }}">
    </div>

    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="1" {{ old('status', (string) (int) $project->status) == '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('status', (string) (int) $project->status) == '0' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Featured</label>
        <select name="is_featured" class="form-control">
            <option value="1" {{ old('is_featured', (string) (int) $project->is_featured) == '1' ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ old('is_featured', (string) (int) $project->is_featured) == '0' ? 'selected' : '' }}>No</option>
        </select>
    </div>

    <button class="btn btn-primary">Update</button>
</form>
@endsection
