@extends('layouts.app')
@section('content')
@php
    $technologies = collect(explode(',', (string) ($profile?->technologies ?? '')))
        ->map(fn ($item) => trim($item))
        ->filter();
    $cvUrl = $profile?->cvUrl();
    $heroTechnologies = $technologies->take(5);
    $focusItems = collect([
        $profile?->job_title,
        'Laravel Development',
        'Frontend Interfaces',
        'MySQL Database Design',
    ])->filter()->unique()->values();
    $locationItems = collect([
        $profile?->address ? Str::limit($profile->address, 48) : null,
        'Por Senchey, Phnom Penh',
        'Phnom Penh, Cambodia',
    ])->filter()->unique()->values();
@endphp

<section class="hero-section">
    <div class="container">
        <div class="hero-grid">
            <div class="hero-card">
                <p class="section-eyebrow">{{ $home->subtitle ?? $profile?->job_title ?? 'Laravel Developer' }}</p>
                <h1 class="hero-title">
                    <span class="hero-name-text">{{ $profile->full_name ?? 'Your Name' }}</span>
                </h1>
                <p class="hero-role">{{ $profile->job_title ?? 'Full Stack Developer' }}</p>
                <p class="hero-summary">{{ $home->short_description ?? $profile->short_intro ?? 'I build modern websites and web applications using Laravel.' }}</p>

                <div class="hero-actions">
                    <a href="{{ $home->hire_me_link ?? route('contact') }}" class="site-btn site-btn-primary">Hire Me</a>

                    @if($cvUrl)
                        <a
                            href="{{ $cvUrl }}"
                            class="site-btn site-btn-secondary"
                            download="{{ $profile?->cvDownloadName() ?? 'cv.pdf' }}"
                        >
                            {{ $home->cv_button_text ?? 'Download CV' }}
                        </a>
                    @endif
                </div>

                @if($socialLinks->isNotEmpty())
                    <div class="chip-row mt-4">
                        @foreach($socialLinks as $socialLink)
                            <a href="{{ $socialLink->url }}" class="site-chip site-chip-link" target="_blank" rel="noopener noreferrer">
                                {{ $socialLink->platform }}
                            </a>
                        @endforeach
                    </div>
                @endif

                @if($heroTechnologies->isNotEmpty())
                    <div class="hero-stack">
                        <span>Core stack</span>
                        <div class="chip-row compact">
                            @foreach($heroTechnologies as $technology)
                                <span class="site-chip">{{ $technology }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="hero-visual-card">
                <div class="hero-visual-frame">
                    @if(!empty($profile?->profile_image))
                        <img src="{{ asset('storage/' . $profile->profile_image) }}" class="hero-portrait" alt="Profile">
                    @else
                        <div class="image-placeholder hero-placeholder">Profile Image</div>
                    @endif

                    <div class="floating-note floating-note-top">
                        <span>Focused on</span>
                        <strong
                            class="typing-role"
                            data-typing-words='@json($focusItems)'
                            data-typing-speed="85"
                            data-typing-pause="1500"
                        >
                            {{ $focusItems->first() ?? ($profile->job_title ?? 'Laravel Development') }}
                        </strong>
                    </div>

                    <div class="floating-note floating-note-bottom">
                        <span>Based in</span>
                        <strong
                            class="typing-role typing-address"
                            data-typing-words='@json($locationItems)'
                            data-typing-speed="70"
                            data-typing-pause="1700"
                        >
                            {{ $locationItems->first() ?? Str::limit($profile->address ?? 'Phnom Penh, Cambodia', 48) }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('frontend.partials.profile-video', [
    'profile' => $profile,
    'eyebrow' => 'Profile Video',
    'title' => 'A video link connected to the portfolio profile',
    'description' => 'Use this section to open or play the video attached to the profile record.'
])

<section class="section-block section-tight">
    <div class="container">
        <div class="metric-grid">
            <article class="metric-card">
                <span class="metric-label">Experience</span>
                <strong>{{ $profile->experience ?? 'Growing through hands-on Laravel projects.' }}</strong>
            </article>
            <article class="metric-card">
                <span class="metric-label">Education</span>
                <strong>{{ Str::limit(str_replace(["\r", "\n"], ' ', $profile?->education ?? ''), 90) ?: 'Information Technology background.' }}</strong>
            </article>
            <article class="metric-card">
                <span class="metric-label">Location</span>
                <strong>{{ $profile->address ?? 'Phnom Penh, Cambodia' }}</strong>
            </article>
        </div>
    </div>
</section>

<section class="section-block">
    <div class="container">
        <div class="section-heading">
            <div>
                <p class="section-eyebrow">Capability</p>
                <h2>Skills that support the work</h2>
            </div>
            <p>Practical strengths built around Laravel, database work, clean interfaces, and maintainable web application delivery.</p>
        </div>

        <div class="skill-grid">
            @forelse($skills as $skill)
                <article class="skill-card">
                    <div class="skill-card-row">
                        <strong>{{ $skill->skill_name }}</strong>
                        <span>{{ $skill->percentage }}%{{ $skill->level ? ' | ' . $skill->level : '' }}</span>
                    </div>
                    <div class="site-meter">
                        <span style="width: {{ $skill->percentage }}%;"></span>
                    </div>
                </article>
            @empty
                <div class="empty-state">Add skills in the admin area to show your technical strengths here.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="section-block">
    <div class="container">
        <div class="section-heading">
            <div>
                <p class="section-eyebrow">Featured Work</p>
                <h2>Selected projects from the portfolio</h2>
            </div>
            <a href="{{ route('projects') }}" class="site-btn site-btn-secondary">View All Projects</a>
        </div>

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
                <div class="empty-state">Featured projects will appear here after you add them.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="section-block">
    <div class="container">
        <div class="section-heading">
            <div>
                <p class="section-eyebrow">Services</p>
                <h2>How I can contribute</h2>
            </div>
            <a href="{{ route('services') }}" class="site-btn site-btn-secondary">View All Services</a>
        </div>

        <div class="service-grid">
            @forelse($services as $service)
                <article class="service-card">
                    <span class="service-index">{{ str_pad((string) ($loop->iteration), 2, '0', STR_PAD_LEFT) }}</span>
                    <h3>{{ $service->title }}</h3>
                    <p>{{ $service->short_description }}</p>
                </article>
            @empty
                <div class="empty-state">Service information has not been added yet.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="section-block">
    <div class="container">
        <div class="section-heading">
            <div>
                <p class="section-eyebrow">Latest Writing</p>
                <h2>Recent articles and updates</h2>
            </div>
            <a href="{{ route('blog') }}" class="site-btn site-btn-secondary">Visit Blog</a>
        </div>

        <div class="blog-grid">
            @forelse($blogs as $blog)
                <article class="blog-card">
                    <div class="card-content">
                        <p class="card-kicker">{{ $blog->category?->name ?? 'Blog' }}</p>
                        <h3>{{ $blog->title }}</h3>
                        <p>{{ $blog->short_description }}</p>
                        <div class="card-meta">{{ optional($blog->published_at)->format('F d, Y') ?? 'Draft' }}</div>
                        <a href="{{ route('blog.show', $blog->slug) }}" class="site-inline-link">Read Article</a>
                    </div>
                </article>
            @empty
                <div class="empty-state">Blog posts will appear here after they are published.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="section-block">
    <div class="container">
        <div class="story-grid">
            <article class="story-card">
                <p class="section-eyebrow">About This Portfolio</p>
                <h2>A clear presentation of skills, work, and professional goals.</h2>
                <p class="site-prose">{{ $profile->about_me ?? 'Add your background story in the profile section.' }}</p>

                @if($technologies->isNotEmpty())
                    <div class="chip-row mt-4">
                        @foreach($technologies as $technology)
                            <span class="site-chip">{{ $technology }}</span>
                        @endforeach
                    </div>
                @endif
            </article>

            <article class="contact-card">
                <p class="section-eyebrow">Contact</p>
                <h2>Available for internships and project discussions.</h2>
                <ul class="detail-list">
                    <li><strong>Email</strong><span>{{ $profile->email ?? $setting?->contact_email ?? '-' }}</span></li>
                    <li><strong>Phone</strong><span>{{ $profile->phone ?? $setting?->contact_phone ?? '-' }}</span></li>
                    <li><strong>Address</strong><span>{{ $profile->address ?? $setting?->address ?? '-' }}</span></li>
                </ul>
                <a href="{{ route('contact') }}" class="site-btn site-btn-primary mt-4">Send a Message</a>
            </article>
        </div>
    </div>
</section>
@endsection
