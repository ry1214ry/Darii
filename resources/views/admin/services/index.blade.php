@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Services</h2>
    <a href="{{ route('admin.services.create') }}" class="btn btn-primary">Add Service</a>
</div>

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Icon</th>
            <th>Description</th>
            <th>Status</th>
            <th width="180">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($services as $service)
            <tr>
                <td>{{ $service->id }}</td>
                <td>{{ $service->title }}</td>
                <td>{{ $service->icon }}</td>
                <td>{{ \Illuminate\Support\Str::limit($service->short_description, 50) }}</td>
                <td>{{ $service->status ? 'Active' : 'Inactive' }}</td>
                <td>
                    <a href="{{ route('admin.services.show', $service->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-warning btn-sm">Edit</a>

                    <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Delete this service?')" class="btn btn-danger btn-sm">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No services found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
