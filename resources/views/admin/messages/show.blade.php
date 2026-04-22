@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Message Detail</h2>

<div class="card p-4">
    <p><strong>Name:</strong> {{ $message->name }}</p>
    <p><strong>Email:</strong> {{ $message->email }}</p>
    <p><strong>Subject:</strong> {{ $message->subject }}</p>
    <p><strong>Message:</strong></p>
    <div class="border rounded p-3 bg-light">
        {{ $message->message }}
    </div>
</div>
@endsection