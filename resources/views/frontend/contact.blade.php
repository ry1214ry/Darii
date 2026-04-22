@extends('layouts.app')

@section('content')
<section class="page-hero">
    <div class="container">
        <div class="page-hero-card">
            <p class="section-eyebrow">Contact</p>
            <h1>Start a conversation about work, collaboration, or internships.</h1>
            <p>Use the form to send a direct message and review the main contact details shown alongside it.</p>
        </div>
    </div>
</section>

<section class="section-block section-tight">
    <div class="container">
        @if(session('success'))
            <div class="site-alert site-alert-success mb-4">{{ session('success') }}</div>
        @endif

        <div class="detail-grid">
            <article class="contact-card">
                <p class="section-eyebrow">Contact Details</p>
                <h2>Reach out directly</h2>
                <ul class="detail-list">
                    <li><strong>Email</strong><span>{{ $profile->email ?? $setting?->contact_email ?? '-' }}</span></li>
                    <li><strong>Phone</strong><span>{{ $profile->phone ?? $setting?->contact_phone ?? '-' }}</span></li>
                    <li><strong>Address</strong><span>{{ $profile->address ?? $setting?->address ?? '-' }}</span></li>
                </ul>

                @if($socialLinks->isNotEmpty())
                    <div class="mt-4">
                        <p class="section-eyebrow mb-2">Quick Links</p>
                        <div class="chip-row">
                            @foreach($socialLinks as $socialLink)
                                <a href="{{ $socialLink->url }}" class="site-chip site-chip-link" target="_blank" rel="noopener noreferrer">
                                    {{ $socialLink->platform }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </article>

            <form action="{{ route('contact.store') }}" method="POST" class="contact-form-card">
                @csrf

                <div class="form-row">
                    <label class="site-label">Name</label>
                    <input type="text" name="name" class="site-input" value="{{ old('name') }}">
                    @error('name')
                        <div class="site-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row">
                    <label class="site-label">Email</label>
                    <input type="email" name="email" class="site-input" value="{{ old('email') }}">
                    @error('email')
                        <div class="site-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row">
                    <label class="site-label">Subject</label>
                    <input type="text" name="subject" class="site-input" value="{{ old('subject') }}">
                    @error('subject')
                        <div class="site-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row">
                    <label class="site-label">Message</label>
                    <textarea name="message" rows="6" class="site-input site-textarea">{{ old('message') }}</textarea>
                    @error('message')
                        <div class="site-error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="site-btn site-btn-primary">Send Message</button>
            </form>
        </div>
    </div>
</section>
@endsection
