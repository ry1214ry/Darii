@extends('layouts.app')

@section('content')
@php
    $technologies = collect(explode(',', (string) ($project->technology_used ?? '')))
        ->map(fn ($item) => trim($item))
        ->filter();
@endphp

<section class="page-hero">
    <div class="container">
        <div class="page-hero-card detail-hero-card">
            <p class="section-eyebrow">{{ $project->category?->name ?? 'Project' }}</p>
            <h1>{{ $project->title }}</h1>
            <p>{{ Str::limit($project->description, 180) }}</p>

            <div class="hero-actions mt-4">
                @if($project->project_url)
                    <a href="{{ $project->project_url }}" target="_blank" class="site-btn site-btn-primary">Live Project</a>
                @endif

                @if($project->github_url)
                    <a href="{{ $project->github_url }}" target="_blank" class="site-btn site-btn-secondary">GitHub</a>
                @endif

                <a href="{{ route('projects') }}" class="site-btn site-btn-secondary">Back to Projects</a>
            </div>
        </div>
    </div>
</section>

<section class="section-block section-tight">
    <div class="container">
        @if($project->demo_video)
            <div class="media-card mb-4">
                <x-project-media :project="$project" media-class="detail-media" placeholder-text="Project Preview" />
            </div>
        @endif

        <div class="detail-grid">
            <article class="story-card">
                <p class="section-eyebrow">Overview</p>
                <h2>Project description</h2>
                <div class="site-prose">{!! nl2br(e($project->description)) !!}</div>
            </article>

            <aside class="contact-card">
                <p class="section-eyebrow">Technology Stack</p>
                <h2>What was used</h2>

                @if($technologies->isNotEmpty())
                    <div class="chip-row">
                        @foreach($technologies as $technology)
                            <span class="site-chip">{{ $technology }}</span>
                        @endforeach
                    </div>
                @else
                    <p class="site-prose mb-0">{{ $project->technology_used ?: '-' }}</p>
                @endif
            </aside>
        </div>
    </div>
</section>
@endsection
