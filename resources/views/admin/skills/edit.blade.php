@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Edit Skill</h2>

<form action="{{ route('admin.skills.update', $skill) }}" method="POST" class="card p-4">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Skill Name</label>
        <input type="text" name="skill_name" class="form-control" value="{{ $skill->skill_name }}">
    </div>

    <div class="mb-3">
        <label>Percentage</label>
        <input type="number" name="percentage" class="form-control" value="{{ $skill->percentage }}">
    </div>

    <div class="mb-3">
        <label>Icon</label>
        <input type="text" name="icon" class="form-control" value="{{ $skill->icon }}">
    </div>

    <div class="mb-3">
        <label>Level</label>
        <input type="text" name="level" class="form-control" value="{{ $skill->level }}">
    </div>

    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="1" {{ $skill->status ? 'selected' : '' }}>Active</option>
            <option value="0" {{ !$skill->status ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Sort Order</label>
        <input type="number" name="sort_order" class="form-control" value="{{ $skill->sort_order }}">
    </div>

    <button class="btn btn-primary">Update</button>
</form>
@endsection