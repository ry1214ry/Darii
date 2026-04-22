@extends('layouts.app')

@section('content')
<section class="page-hero">
    <div class="container">
        <div class="page-hero-card">
            <p class="section-eyebrow">Services</p>
            <h1>Practical frontend and Laravel development support.</h1>
            <p>Services focused on building, refining, and maintaining portfolio websites, admin dashboards, and data-driven web applications.</p>
        </div>
    </div>
</section>

<section class="section-block section-tight">
    <div class="container">
        <div class="service-grid">
            @forelse($services as $service)
                <article class="service-card">
                    <span class="service-index">{{ str_pad((string) ($loop->iteration), 2, '0', STR_PAD_LEFT) }}</span>
                    <h3>{{ $service->title }}</h3>
                    <p>{{ $service->short_description }}</p>
                </article>
            @empty
                <div class="empty-state">No services are available yet.</div>
            @endforelse
        </div>

        <div class="cta-banner mt-5">
            <div>
                <p class="section-eyebrow mb-2">Need a project?</p>
                <h2>Let's talk about your Laravel website or dashboard.</h2>
                <p class="mb-0">Use the contact form to discuss features, improvements, or portfolio work that needs a cleaner frontend presentation.</p>
            </div>
            <a href="{{ route('contact') }}" class="site-btn site-btn-primary">Contact Me</a>
        </div>
    </div>
</section>
@endsection
