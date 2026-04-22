@extends('layouts.admin')

@section('page_title', 'Dashboard Overview')

@section('content')
<section class="admin-hero">
    <div class="admin-hero-copy">
        <p class="admin-hero-kicker">Portfolio Workspace</p>
        <h2>Track your content at a glance.</h2>
        <p>
            This dashboard gives you a quick view of the sections that shape the public portfolio,
            from projects and skills to blog posts and client communication.
        </p>
    </div>

    <div class="admin-hero-panel">
        <span class="admin-hero-panel-label">Live Sections</span>
        <strong>{{ $totalProjects + $totalSkills + $totalBlogs + $totalMessages + $totalServices + $totalTestimonials }}</strong>
        <p>Total records across your main dashboard widgets.</p>
    </div>
</section>

<section class="admin-stats-grid">
    <article class="admin-stat-card tone-orange">
        <span class="admin-stat-label">Projects</span>
        <strong>{{ $totalProjects }}</strong>
        <p>Portfolio work and featured case studies.</p>
    </article>

    <article class="admin-stat-card tone-teal">
        <span class="admin-stat-label">Skills</span>
        <strong>{{ $totalSkills }}</strong>
        <p>Technical skills shown on the public profile.</p>
    </article>

    <article class="admin-stat-card tone-gold">
        <span class="admin-stat-label">Blogs</span>
        <strong>{{ $totalBlogs }}</strong>
        <p>Published writing and development updates.</p>
    </article>

    <article class="admin-stat-card tone-slate">
        <span class="admin-stat-label">Messages</span>
        <strong>{{ $totalMessages }}</strong>
        <p>Contact requests submitted from the website.</p>
    </article>

    <article class="admin-stat-card tone-olive">
        <span class="admin-stat-label">Services</span>
        <strong>{{ $totalServices }}</strong>
        <p>Professional offers currently listed online.</p>
    </article>

    <article class="admin-stat-card tone-clay">
        <span class="admin-stat-label">Testimonials</span>
        <strong>{{ $totalTestimonials }}</strong>
        <p>Client feedback and proof of trust.</p>
    </article>
</section>

<section class="admin-dashboard-panels">
    <div class="admin-dashboard-panel">
        <div class="admin-panel-head">
            <div>
                <p class="admin-panel-kicker">Quick Actions</p>
                <h3>Update important sections</h3>
            </div>
        </div>

        <div class="admin-action-list">
            <a href="{{ route('admin.profiles.index') }}" class="admin-action-item">
                <strong>Edit profile</strong>
                <span>Update intro, CV, photo, and contact details.</span>
            </a>
            <a href="{{ route('admin.projects.index') }}" class="admin-action-item">
                <strong>Manage projects</strong>
                <span>Add new work, edit descriptions, and highlight featured items.</span>
            </a>
            <a href="{{ route('admin.blogs.index') }}" class="admin-action-item">
                <strong>Publish blog content</strong>
                <span>Keep the site active with posts and updates.</span>
            </a>
            <a href="{{ route('admin.messages.index') }}" class="admin-action-item">
                <strong>Review messages</strong>
                <span>Check contact requests from the website inbox.</span>
            </a>
        </div>
    </div>

    <div class="admin-dashboard-panel">
        <div class="admin-panel-head">
            <div>
                <p class="admin-panel-kicker">Content Health</p>
                <h3>Current publishing status</h3>
            </div>
        </div>

        <div class="admin-status-stack">
            <div class="admin-status-row">
                <span>Projects</span>
                <div class="admin-status-meter">
                    <div class="admin-status-fill tone-orange" style="width: {{ min(100, $totalProjects * 12) }}%;"></div>
                </div>
                <strong>{{ $totalProjects }}</strong>
            </div>

            <div class="admin-status-row">
                <span>Skills</span>
                <div class="admin-status-meter">
                    <div class="admin-status-fill tone-teal" style="width: {{ min(100, $totalSkills * 10) }}%;"></div>
                </div>
                <strong>{{ $totalSkills }}</strong>
            </div>

            <div class="admin-status-row">
                <span>Blogs</span>
                <div class="admin-status-meter">
                    <div class="admin-status-fill tone-gold" style="width: {{ min(100, $totalBlogs * 18) }}%;"></div>
                </div>
                <strong>{{ $totalBlogs }}</strong>
            </div>

            <div class="admin-status-row">
                <span>Messages</span>
                <div class="admin-status-meter">
                    <div class="admin-status-fill tone-slate" style="width: {{ min(100, $totalMessages * 15) }}%;"></div>
                </div>
                <strong>{{ $totalMessages }}</strong>
            </div>
        </div>
    </div>
</section>
@endsection
