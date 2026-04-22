@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Create Profile</h2>

<form action="{{ route('admin.profiles.store') }}" method="POST" enctype="multipart/form-data" class="card p-4">
    @csrf

    <div class="mb-3">
        <label>Full Name</label>
        <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}">
    </div>

    <div class="mb-3">
        <label>Job Title</label>
        <input type="text" name="job_title" class="form-control" value="{{ old('job_title') }}">
    </div>

    <div class="mb-3">
        <label>Short Intro</label>
        <textarea name="short_intro" class="form-control" rows="3">{{ old('short_intro') }}</textarea>
    </div>

    <div class="mb-3">
        <label>About Me</label>
        <textarea name="about_me" class="form-control" rows="5">{{ old('about_me') }}</textarea>
    </div>

    <div class="mb-3">
        <label>Profile Image</label>
        <input type="file" name="profile_image" class="form-control">
    </div>

    <div class="mb-3">
        <label>Profile Video URL</label>
        <input type="text" name="profile_video" class="form-control" value="{{ old('profile_video') }}">
    </div>

    <div class="mb-3">
        <label>Experience</label>
        <input type="text" name="experience" class="form-control" value="{{ old('experience') }}">
    </div>

    <div class="mb-3">
        <label>Education</label>
        <input type="text" name="education" class="form-control" value="{{ old('education') }}">
    </div>

    <div class="mb-3">
        <label>Technologies</label>
        <textarea name="technologies" class="form-control" rows="3">{{ old('technologies') }}</textarea>
    </div>

    <div class="mb-3">
        <label>Goals</label>
        <textarea name="goals" class="form-control" rows="3">{{ old('goals') }}</textarea>
    </div>

    <div class="mb-3">
        <label>CV File (PDF)</label>
        <input type="file" name="cv_file" class="form-control">
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
    </div>

    <div class="mb-3">
        <label>Phone</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
    </div>

    <div class="mb-3">
        <label>Address</label>
        <input type="text" name="address" class="form-control" value="{{ old('address') }}">
    </div>

    <button type="submit" class="btn btn-success">Save Profile</button>
</form>
@endsection
