@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Profile Detail</h2>

<div class="card p-4">
    @if($profile->profile_image)
        <div class="mb-3">
            <img src="{{ asset('storage/' . $profile->profile_image) }}" width="120">
        </div>
    @endif

    <p><strong>Full Name:</strong> {{ $profile->full_name }}</p>
    <p><strong>Job Title:</strong> {{ $profile->job_title }}</p>
    <p><strong>Short Intro:</strong> {{ $profile->short_intro }}</p>
    <p><strong>About Me:</strong> {{ $profile->about_me }}</p>
    <p><strong>Experience:</strong> {{ $profile->experience }}</p>
    <p><strong>Education:</strong> {{ $profile->education }}</p>
    <p><strong>Technologies:</strong> {{ $profile->technologies }}</p>
    <p><strong>Goals:</strong> {{ $profile->goals }}</p>
    <p><strong>Email:</strong> {{ $profile->email }}</p>
    <p><strong>Phone:</strong> {{ $profile->phone }}</p>
    <p><strong>Address:</strong> {{ $profile->address }}</p>

    @if($profile->cv_file)
        <p>
            <strong>CV:</strong>
            <a href="{{ asset('storage/' . $profile->cv_file) }}" target="_blank">View CV</a>
        </p>
    @endif
</div>
@endsection
