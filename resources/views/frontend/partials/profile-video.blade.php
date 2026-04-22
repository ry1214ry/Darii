@php
    $videoUrl = trim((string) ($profile?->profile_video ?? ''));
    $embedType = null;
    $embedUrl = null;
    $videoPoster = !empty($profile?->profile_image) ? asset('storage/' . $profile->profile_image) : null;

    if ($videoUrl !== '') {
        if (preg_match('~(?:youtube\.com/watch\?v=|youtu\.be/|youtube\.com/embed/)([^&?/]+)~i', $videoUrl, $matches)) {
            $embedType = 'youtube';
            $embedUrl = 'https://www.youtube.com/embed/' . $matches[1] . '?rel=0';
        } elseif (preg_match('~vimeo\.com/(?:video/)?([0-9]+)~i', $videoUrl, $matches)) {
            $embedType = 'vimeo';
            $embedUrl = 'https://player.vimeo.com/video/' . $matches[1];
        } elseif (preg_match('~\.(mp4|webm|ogg|mov)(\?.*)?$~i', $videoUrl)) {
            $embedType = 'file';
        } else {
            $embedType = 'external';
        }
    }
@endphp

@if($videoUrl !== '')
    <section class="section-block section-tight">
        <div class="container">
            <div class="video-section-card">
                <div class="section-heading">
                    <div>
                        <p class="section-eyebrow">{{ $eyebrow ?? 'Profile Video' }}</p>
                        <h2>{{ $title ?? 'Watch my profile video' }}</h2>
                    </div>
                    <p>{{ $description ?? 'A quick video section for showing an introduction, portfolio clip, or external profile video link.' }}</p>
                </div>

                @if($embedType === 'youtube' || $embedType === 'vimeo')
                    <div class="video-frame">
                        <iframe
                            src="{{ $embedUrl }}"
                            title="Profile video"
                            loading="lazy"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen
                        ></iframe>
                    </div>
                @elseif($embedType === 'file')
                    <div class="video-frame">
                        <video controls playsinline preload="metadata" @if($videoPoster) poster="{{ $videoPoster }}" @endif>
                            <source src="{{ $videoUrl }}">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                @else
                    <div class="video-link-card">
                        <div class="video-link-copy">
                            <p class="section-eyebrow mb-2">External Video</p>
                            <h3>Open the profile video in a new tab</h3>
                            <p class="mb-0">The current video link is stored and available, but this type of URL is better opened directly outside the site.</p>
                        </div>

                        <a href="{{ $videoUrl }}" target="_blank" rel="noopener noreferrer" class="site-btn site-btn-primary">
                            Watch Profile Video
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif
