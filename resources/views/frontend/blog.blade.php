@extends('layouts.app')

@section('content')
<section class="page-hero">
    <div class="container">
        <div class="page-hero-card">
            <p class="section-eyebrow">Blog</p>
            <h1>Thoughts on Laravel, learning, and project building.</h1>
            <p>Short writing collected around web development practice, technical growth, and lessons from database-driven applications.</p>
        </div>
    </div>
</section>

<section class="section-block section-tight">
    <div class="container">
        <div class="blog-grid">
            @forelse($blogs as $blog)
                <article class="blog-card">
                    @if($blog->image)
                        <img src="{{ asset('storage/' . $blog->image) }}" class="card-media" alt="{{ $blog->title }}">
                    @endif

                    <div class="card-content">
                        <p class="card-kicker">{{ $blog->category?->name ?? 'Blog' }}</p>
                        <h3>{{ $blog->title }}</h3>
                        <p>{{ $blog->short_description }}</p>
                        <div class="card-meta">{{ optional($blog->published_at)->format('F d, Y') ?? 'Draft' }}</div>
                        <a href="{{ route('blog.show', $blog->slug) }}" class="site-inline-link">Read More</a>
                    </div>
                </article>
            @empty
                <div class="empty-state">No blog posts have been published yet.</div>
            @endforelse
        </div>

        <div class="pagination-wrap mt-5">
            {{ $blogs->links() }}
        </div>
    </div>
</section>
@endsection
