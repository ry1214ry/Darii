@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Category Detail</h2>

<div class="card p-4">
    <p><strong>ID:</strong> {{ $category->id }}</p>
    <p><strong>Name:</strong> {{ $category->name }}</p>
    <p><strong>Slug:</strong> {{ $category->slug }}</p>
    <p><strong>Type:</strong> {{ $category->type }}</p>
    <p><strong>Status:</strong> {{ $category->status ? 'Active' : 'Inactive' }}</p>
</div>
@endsection
