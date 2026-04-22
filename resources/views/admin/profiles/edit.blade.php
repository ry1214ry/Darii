@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Edit Profile</h2>

<form action="{{ route('admin.profiles.update', $profile->id) }}" method="POST" enctype="multipart/form-data" class="card p-4">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Full Name</label>
        <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $profile->full_name) }}">
    </div>

    <div class="mb-3">
        <label>Job Title</label>
        <input type="text" name="job_title" class="form-control" value="{{ old('job_title', $profile->job_title) }}">
    </div>

    <div class="mb-3">
        <label>Short Intro</label>
        <textarea name="short_intro" class="form-control" rows="3">{{ old('short_intro', $profile->short_intro) }}</textarea>
    </div>

    <div class="mb-3">
        <label>About Me</label>
        <textarea name="about_me" class="form-control" rows="5">{{ old('about_me', $profile->about_me) }}</textarea>
    </div>

    <div class="mb-3">
        <label>Current Image</label><br>
        @if($profile->profile_image)
            <img src="{{ asset('storage/' . $profile->profile_image) }}" width="100" class="mb-2">
        @else
            <p>No image</p>
        @endif

    </div>

    <div class="mb-3">
        <label>New Profile Image</label>
        <input type="file" name="profile_image" class="form-control">
    </div>

    <div class="mb-3">
        <label>Profile Video URL</label>
        <input type="text" name="profile_video" class="form-control" value="{{ old('profile_video', $profile->profile_video) }}">
    </div>

    <div class="mb-3">
        <label>Experience</label>
        <input type="text" name="experience" class="form-control" value="{{ old('experience', $profile->experience) }}">
    </div>

    <div class="mb-3">
        <label>Education</label>
        <input type="text" name="education" class="form-control" value="{{ old('education', $profile->education) }}">
    </div>

    <div class="mb-3">
        <label>Technologies</label>
        <textarea name="technologies" class="form-control" rows="3">{{ old('technologies', $profile->technologies) }}</textarea>
    </div>

    <div class="mb-3">
        <label>Goals</label>
        <textarea name="goals" class="form-control" rows="3">{{ old('goals', $profile->goals) }}</textarea>
    </div>

    <div class="mb-3">
        <label>Upload New CV (PDF)</label>
        <input type="file" name="cv_file" class="form-control">
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $profile->email) }}">
    </div>

    <div class="mb-3">
        <label>Phone</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone', $profile->phone) }}">
    </div>

    <div class="mb-3">
        <label>Address</label>
        <input type="text" name="address" class="form-control" value="{{ old('address', $profile->address) }}">
    </div>

    <button type="submit" class="btn btn-primary">Update Profile</button>
</form>
@endsection
