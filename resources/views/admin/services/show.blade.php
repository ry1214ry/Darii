@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Service Detail</h2>

<div class="card p-4">
    <p><strong>ID:</strong> {{ $service->id }}</p>
    <p><strong>Title:</strong> {{ $service->title }}</p>
    <p><strong>Icon:</strong> {{ $service->icon }}</p>
    <p><strong>Description:</strong> {{ $service->short_description }}</p>
    <p><strong>Status:</strong> {{ $service->status ? 'Active' : 'Inactive' }}</p>
</div>
@endsection
