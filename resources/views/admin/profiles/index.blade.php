@extends('layouts.admin')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Profiles</h2>
    <a href="{{ route('admin.profiles.create') }}" class="btn btn-primary">Add Profile</a>
</div>

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Full Name</th>
            <th>Job Title</th>
            <th>Email</th>
            <th>Phone</th>
            <th width="180">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($profiles as $profile)
            <tr>
                <td>{{ $profile->id }}</td>
                <td>
                    @if($profile->profile_image)
                        <img src="{{ asset('storage/' . $profile->profile_image) }}" width="70" height="70" style="object-fit: cover; border-radius: 8px;">
                    @else
                        <span>No Image</span>
                    @endif
                </td>
                <td>{{ $profile->full_name }}</td>
                <td>{{ $profile->job_title }}</td>
                <td>{{ $profile->email }}</td>
                <td>{{ $profile->phone }}</td>
                <td>
                    <a href="{{ route('admin.profiles.show', $profile->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('admin.profiles.edit', $profile->id) }}" class="btn btn-warning btn-sm">Edit</a>

                    <form action="{{ route('admin.profiles.destroy', $profile->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Delete this profile?')" class="btn btn-danger btn-sm">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">No profiles found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
