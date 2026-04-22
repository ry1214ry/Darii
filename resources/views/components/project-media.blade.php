@props([
    'project',
    'mediaClass' => 'card-media',
    'placeholderText' => 'Project Preview',
])

@php
    $mediaClass = trim((string) $mediaClass);
    $videoUrl = $project->demoVideoUrl();
    $embedUrl = $project->demoVideoEmbedUrl();
    $imageUrl = filled($project->image) ? asset('storage/' . ltrim($project->image, '/')) : null;
    $placeholderClass = trim($mediaClass . ' card-media-placeholder');
@endphp

@if($videoUrl)
    @if($embedUrl)
        <iframe
            src="{{ $embedUrl }}"
            class="{{ $mediaClass }} project-video-embed"
            title="{{ $project->title }} demo video"
            loading="lazy"
            allow="autoplay; encrypted-media; picture-in-picture"
            referrerpolicy="strict-origin-when-cross-origin"
            tabindex="-1"
        ></iframe>
    @else
        <video
            class="{{ $mediaClass }} project-video-player"
            autoplay
            muted
            loop
            playsinline
            preload="metadata"
            disablepictureinpicture
            controlslist="nodownload noplaybackrate nofullscreen"
            tabindex="-1"
        >
            <source src="{{ $videoUrl }}">
            Your browser does not support video playback.
        </video>
    @endif
@else
    @if($imageUrl)
        <img
            src="{{ $imageUrl }}"
            class="{{ $mediaClass }}"
            alt="{{ $project->title }}"
        >
    @else
        <div class="{{ $placeholderClass }}">{{ $placeholderText }}</div>
    @endif
@endif
