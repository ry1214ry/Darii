@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Projects</h2>
    <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">Add Project</a>
</div>

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>ID</th>
            <th>Preview</th>
            <th>Title</th>
            <th>Category</th>
            <th>Featured</th>
            <th>Status</th>
            <th width="180">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($projects as $project)
            <tr>
                <td>{{ $project->id }}</td>
                <td>
                    @if($project->image)
                        <img
                            src="{{ asset('storage/' . ltrim($project->image, '/')) }}"
                            alt="{{ $project->title }}"
                            class="img-fluid rounded border"
                            style="width: 120px; height: 72px; object-fit: cover;"
                        >
                    @elseif($project->demo_video)
                        <div class="d-grid gap-2">
                            <span class="badge bg-info text-dark">Video Demo</span>
                            <a href="{{ $project->demoVideoUrl() }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm">Open Demo</a>
                        </div>
                    @else
                        <span class="text-muted">No media</span>
                    @endif
                </td>
                <td>{{ $project->title }}</td>
                <td>{{ $project->category->name ?? '-' }}</td>
                <td>{{ $project->is_featured ? 'Yes' : 'No' }}</td>
                <td>{{ $project->status ? 'Active' : 'Inactive' }}</td>
                <td>
                    <a href="{{ route('admin.projects.show', $project) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Delete this project?')" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
