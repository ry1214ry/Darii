@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Skills</h2>
    <a href="{{ route('admin.skills.create') }}" class="btn btn-primary">Add Skill</a>
</div>

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>ID</th>
            <th>Skill</th>
            <th>Percentage</th>
            <th>Level</th>
            <th>Status</th>
            <th width="180">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($skills as $skill)
            <tr>
                <td>{{ $skill->id }}</td>
                <td>{{ $skill->skill_name }}</td>
                <td>{{ $skill->percentage }}%</td>
                <td>{{ $skill->level }}</td>
                <td>{{ $skill->status ? 'Active' : 'Inactive' }}</td>
                <td>
                    <a href="{{ route('admin.skills.edit', $skill) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('admin.skills.destroy', $skill) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Delete this skill?')" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center">No data found.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection