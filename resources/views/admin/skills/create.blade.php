@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Create Skill</h2>

<form action="{{ route('admin.skills.store') }}" method="POST" class="card p-4">
    @csrf

    <div class="mb-3">
        <label>Skill Name</label>
        <input type="text" name="skill_name" class="form-control">
    </div>

    <div class="mb-3">
        <label>Percentage</label>
        <input type="number" name="percentage" class="form-control">
    </div>

    <div class="mb-3">
        <label>Icon</label>
        <input type="text" name="icon" class="form-control">
    </div>

    <div class="mb-3">
        <label>Level</label>
        <input type="text" name="level" class="form-control">
    </div>

    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Sort Order</label>
        <input type="number" name="sort_order" class="form-control" value="0">
    </div>

    <button class="btn btn-success">Save</button>
</form>
@endsection