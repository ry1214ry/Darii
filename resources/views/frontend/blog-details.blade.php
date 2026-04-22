@extends('layouts.app')

@section('content')
<section class="page-hero">
    <div class="container">
        <div class="page-hero-card detail-hero-card">
            <p class="section-eyebrow">{{ $blog->category?->name ?? 'Blog' }}</p>
            <h1>{{ $blog->title }}</h1>
            <p>{{ $blog->short_description ?? 'Article details' }}</p>
        </div>
    </div>
</section>

<section class="section-block section-tight">
    <div class="container">
        @if($blog->image)
            <div class="media-card mb-4">
                <img src="{{ asset('storage/' . $blog->image) }}" class="card-media detail-media" alt="{{ $blog->title }}">
            </div>
        @endif

        <div class="detail-grid">
            <article class="story-card">
                <p class="section-eyebrow">Article</p>
                <h2>Content</h2>
                <div class="site-prose">{!! nl2br(e($blog->content)) !!}</div>
            </article>

            <aside class="contact-card">
                <p class="section-eyebrow">Details</p>
                <h2>Post information</h2>
                <ul class="detail-list">
                    <li><strong>Author</strong><span>{{ $blog->author ?: '-' }}</span></li>
                    <li><strong>Date</strong><span>{{ optional($blog->published_at)->format('F d, Y') ?? 'Draft' }}</span></li>
                    <li><strong>Category</strong><span>{{ $blog->category?->name ?? 'Blog' }}</span></li>
                </ul>
                <a href="{{ route('blog') }}" class="site-btn site-btn-secondary mt-4">Back to Blog</a>
            </aside>
        </div>
    </div>
</section>
@endsection
