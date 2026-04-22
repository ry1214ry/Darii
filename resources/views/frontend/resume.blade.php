@extends('layouts.app')

@section('content')
@php
    $technologies = collect(explode(',', (string) ($profile?->technologies ?? '')))
        ->map(fn ($item) => trim($item))
        ->filter();
    $cvUrl = $profile?->cvUrl();
@endphp

<section class="page-hero">
    <div class="container">
        <div class="page-hero-card">
            <p class="section-eyebrow">Resume</p>
            <h1>Professional summary, education, and core technical profile.</h1>
            <p>A clean resume view for quickly reviewing experience, background, goals, and downloadable CV information.</p>
        </div>
    </div>
</section>

<section class="section-block section-tight">
    <div class="container">
        <div class="detail-grid">
            <article class="story-card">
                <p class="section-eyebrow">{{ $profile->job_title ?? 'Laravel Developer' }}</p>
                <h2>{{ $profile->full_name ?? 'Your Name' }}</h2>
                <p class="hero-summary">{{ $profile->short_intro ?? 'Add your short introduction to the profile section.' }}</p>

                <div class="resume-stack">
                    <div>
                        <h3>Professional Summary</h3>
                        <div class="site-prose">{!! nl2br(e($profile->about_me ?? '-')) !!}</div>
                    </div>
                    <div>
                        <h3>Experience</h3>
                        <div class="site-prose">{!! nl2br(e($profile->experience ?? '-')) !!}</div>
                    </div>
                    <div>
                        <h3>Education</h3>
                        <div class="site-prose">{!! nl2br(e($profile->education ?? '-')) !!}</div>
                    </div>
                    <div>
                        <h3>Career Goals</h3>
                        <div class="site-prose">{!! nl2br(e($profile->goals ?? '-')) !!}</div>
                    </div>
                </div>
            </article>

            <aside class="sidebar-stack">
                <article class="contact-card">
                    <p class="section-eyebrow">Contact</p>
                    <h2>Details</h2>
                    <ul class="detail-list">
                        <li><strong>Email</strong><span>{{ $profile->email ?? $setting?->contact_email ?? '-' }}</span></li>
                        <li><strong>Phone</strong><span>{{ $profile->phone ?? $setting?->contact_phone ?? '-' }}</span></li>
                        <li><strong>Address</strong><span>{{ $profile->address ?? $setting?->address ?? '-' }}</span></li>
                    </ul>
                </article>

                <article class="contact-card">
                    <p class="section-eyebrow">Technologies</p>
                    <h2>Stack</h2>
                    @if($technologies->isNotEmpty())
                        <div class="chip-row">
                            @foreach($technologies as $technology)
                                <span class="site-chip">{{ $technology }}</span>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">Technology keywords have not been added yet.</div>
                    @endif
                </article>

                <article class="contact-card">
                    <p class="section-eyebrow">CV</p>
                    <h2>Download file</h2>
                    @if($cvUrl)
                        <a
                            href="{{ $cvUrl }}"
                            download="{{ $profile?->cvDownloadName() ?? 'cv.pdf' }}"
                            class="site-btn site-btn-primary w-100"
                        >
                            Download CV
                        </a>
                    @else
                        <div class="empty-state">No CV uploaded yet.</div>
                    @endif
                </article>
            </aside>
        </div>
    </div>
</section>
@endsection
