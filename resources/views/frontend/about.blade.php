@extends('layouts.app')

@section('content')
@php
    $technologies = collect(explode(',', (string) ($profile?->technologies ?? '')))
        ->map(fn ($item) => trim($item))
        ->filter();
@endphp

<section class="page-hero">
    <div class="container">
        <div class="page-hero-card">
            <p class="section-eyebrow">About</p>
            <h1>About Me</h1>
            <p>Background, learning path, personal goals, and the technical focus behind this portfolio.</p>
        </div>
    </div>
</section>

<section class="section-block section-tight">
    <div class="container">
        <div class="about-grid">
            <div class="media-card">
                @if(!empty($profile?->profile_image))
                    <img src="{{ asset('storage/' . $profile->profile_image) }}" class="card-media large-media" alt="Profile">
                @else
                    <div class="card-media card-media-placeholder large-media">Profile Image</div>
                @endif
            </div>

            <article class="story-card">
                <p class="section-eyebrow">{{ $profile->job_title ?? 'Full Stack Developer' }}</p>
                <h2>{{ $profile->full_name ?? 'Your Name' }}</h2>
                <p class="hero-summary">{{ $profile->short_intro ?? 'Write a short introduction here.' }}</p>
                <div class="site-prose">{!! nl2br(e($profile->about_me ?? 'Write your story here.')) !!}</div>
            </article>
        </div>
    </div>
</section>

@include('frontend.partials.profile-video', [
    'profile' => $profile,
    'eyebrow' => 'Video',
    'title' => 'Profile video and external media link',
    'description' => 'If a direct embed is available it will play here, otherwise the section provides a quick link to open the video.'
])

<section class="section-block section-tight">
    <div class="container">
        <div class="metric-grid">
            <article class="metric-card">
                <span class="metric-label">Experience</span>
                <strong>{{ $profile->experience ?? '-' }}</strong>
            </article>
            <article class="metric-card">
                <span class="metric-label">Education</span>
                <strong>{{ Str::limit(str_replace(["\r", "\n"], ' ', $profile->education ?? '-'), 110) }}</strong>
            </article>
            <article class="metric-card">
                <span class="metric-label">Goals</span>
                <strong>{{ $profile->goals ?? '-' }}</strong>
            </article>
        </div>
    </div>
</section>

<section class="section-block">
    <div class="container">
        <div class="story-grid">
            <article class="contact-card">
                <p class="section-eyebrow">Contact Details</p>
                <h2>Direct information</h2>
                <ul class="detail-list">
                    <li><strong>Email</strong><span>{{ $profile->email ?? $setting?->contact_email ?? '-' }}</span></li>
                    <li><strong>Phone</strong><span>{{ $profile->phone ?? $setting?->contact_phone ?? '-' }}</span></li>
                    <li><strong>Address</strong><span>{{ $profile->address ?? $setting?->address ?? '-' }}</span></li>
                </ul>
            </article>

            <article class="story-card">
                <p class="section-eyebrow">Technologies</p>
                <h2>Tools I work with</h2>
                @if($technologies->isNotEmpty())
                    <div class="chip-row">
                        @foreach($technologies as $technology)
                            <span class="site-chip">{{ $technology }}</span>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">Add technology keywords to your profile to show them here.</div>
                @endif
            </article>
        </div>
    </div>
</section>

<section class="section-block">
    <div class="container">
        <div class="section-heading">
            <div>
                <p class="section-eyebrow">Skill Overview</p>
                <h2>Technical strengths</h2>
            </div>
            <p>These are the main technical areas that support the projects shown in the portfolio.</p>
        </div>

        <div class="skill-grid">
            @forelse($skills as $skill)
                <article class="skill-card">
                    <div class="skill-card-row">
                        <strong>{{ $skill->skill_name }}</strong>
                        <span>{{ $skill->percentage }}%</span>
                    </div>
                    <div class="site-meter">
                        <span style="width: {{ $skill->percentage }}%;"></span>
                    </div>
                </article>
            @empty
                <div class="empty-state">Skills have not been added yet.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
