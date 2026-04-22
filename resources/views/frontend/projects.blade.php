@extends('layouts.app')

@section('content')
<section class="page-hero">
    <div class="container">
        <div class="page-hero-card">
            <p class="section-eyebrow">Projects</p>
            <h1>Work built with Laravel and practical web technologies.</h1>
            <p>A selection of portfolio pieces focused on responsive layouts, structured backend logic, and database-driven content.</p>
        </div>
    </div>
</section>

<section class="section-block section-tight">
    <div class="container">
        @if($categories->isNotEmpty())
            <div class="chip-row mb-4">
                @foreach($categories as $category)
                    <span class="site-chip">{{ $category->name }}</span>
                @endforeach
            </div>
        @endif

        <div class="project-grid">
            @forelse($projects as $project)
                <article class="project-card">
                    <x-project-media :project="$project" media-class="card-media" placeholder-text="Project Preview" />

                    <div class="card-content">
                        <p class="card-kicker">{{ $project->category?->name ?? 'Project' }}</p>
                        <h3>{{ $project->title }}</h3>
                        <p>{{ Str::limit($project->description, 120) }}</p>
                        <div class="card-meta">{{ $project->technology_used }}</div>
                        <a href="{{ route('projects.show', $project->slug) }}" class="site-inline-link">View Details</a>
                    </div>
                </article>
            @empty
                <div class="empty-state">No projects have been published yet.</div>
            @endforelse
        </div>

        <div class="pagination-wrap mt-5">
            {{ $projects->links() }}
        </div>
    </div>
</section>
@endsection
