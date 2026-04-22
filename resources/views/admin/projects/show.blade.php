@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Project Detail</h2>

<div class="card p-4">
    @if($project->image || $project->demo_video)
        <div class="mb-4" style="max-width: 520px;">
            <x-project-media :project="$project" media-class="project-preview-media" placeholder-text="Project Preview" />
        </div>
    @endif

    <p><strong>ID:</strong> {{ $project->id }}</p>
    <p><strong>Title:</strong> {{ $project->title }}</p>
    <p><strong>Slug:</strong> {{ $project->slug }}</p>
    <p><strong>Category:</strong> {{ $project->category?->name ?? '-' }}</p>
    <p><strong>Status:</strong> {{ $project->status ? 'Active' : 'Inactive' }}</p>
    <p><strong>Featured:</strong> {{ $project->is_featured ? 'Yes' : 'No' }}</p>
    <p><strong>Description:</strong> {{ $project->description ?: '-' }}</p>
    <p><strong>Technology Used:</strong> {{ $project->technology_used ?: '-' }}</p>
    <p><strong>Project URL:</strong>
        @if($project->project_url)
            <a href="{{ $project->project_url }}" target="_blank" rel="noopener noreferrer">{{ $project->project_url }}</a>
        @else
            -
        @endif
    </p>
    <p><strong>GitHub URL:</strong>
        @if($project->github_url)
            <a href="{{ $project->github_url }}" target="_blank" rel="noopener noreferrer">{{ $project->github_url }}</a>
        @else
            -
        @endif
    </p>
    <p><strong>Preview Image:</strong> {{ $project->image ?: '-' }}</p>
    <p><strong>Demo Video:</strong>
        @if($project->demo_video)
            <a href="{{ $project->demoVideoUrl() }}" target="_blank" rel="noopener noreferrer">{{ $project->demo_video }}</a>
        @else
            -
        @endif
    </p>
</div>
@endsection
